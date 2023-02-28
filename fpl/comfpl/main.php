<?php
use comfpl\views\JavaScriptResult;
use comfpl\views\JsonResult;
use comfpl\views\PartialViewResult;
use comfpl\views\RedirectResult;
use comfpl\views\RedirectToRouteResult;
use comfpl\views\ViewResult;
use comfpl\views\XmlResult;

require_once 'annotations.php';
require_once 'routes/route.php';
require_once 'views/viewresult.php';
require_once 'controllers/basecontroller.php';
require_once 'models/Entity.php';
require_once 'HTML.php';
require_once 'automapper.php';
require_once 'bundle.php';
require_once 'security.php';
require_once 'dicomponent.php';
require_once 'viewscript.php';
require_once 'trace.php';

/*
 * fpl framework PHP léger
 * (C) 2019 BAG
 *
 * main.php
 */

/**
 * Fournit des services à Router.php
 *
 * @author guerin
 *        
 */
class FPLGlobal
{

    public static $FPLVersion = "1.0.0";

    /**
     * Route par défaut par ex.
     * home-home-index.do
     *
     * @var string
     */
    public static $default_route;

    /**
     * Route pour l'authentification par ex.
     * usermgt-user-login.do
     *
     * @var string
     */
    public static $login_route;

    /**
     * Crédits de connexion pré-configurés
     *
     * @var array
     */
    public static $mockup_credentials = array();

    /*
     * Sécurité
     */
    /**
     * Vérifie si l'utiilsateur satisfait aux requêtes d'autorisation du contrôleur et/ou de l'action
     *
     * @param Autorisation $ct_auth
     * @param Autorisation $ac_auth
     */
    public static function check_autorisation($ct_auth, $ac_auth)
    {
        $id = FPLGlobal::get_user_identity();
        $allow = true;

        if ($ct_auth != null) {
            $mode = $ct_auth->access_control_mode;
            if(isset($ct_auth->deny_list))
                $ct_auth->deny_list_array=explode(',', $ct_auth->deny_list);
            else 
                $ct_auth->deny_list_array=array();
            
            if(isset($ct_auth->allow_list))
                $ct_auth->allow_list_array=explode(',', $ct_auth->allow_list);
            else
                $ct_auth->allow_list_array=array();
                
            // recherche les deny
            if (($mode & \Autorisation::DENY_ANONYMOUS) != 0 && $id->is_anonymous)
                $allow = false;

            if (($mode & \Autorisation::DENY_ALL) != 0)
                $allow = false;

            if (($mode & \Autorisation::DENY_LIST_USERS) != 0 && in_array($id->login, $ct_auth->deny_list_array))
                $allow = false;

            // recherche les autorisations
            if (($mode & \Autorisation::ALLOW_ALL) != 0)
                $allow = false;

            if (($mode & \Autorisation::ALLOW_ANONYMOUS) != 0 && $id->is_anonymous)
                $allow = true;

            if (($mode & \Autorisation::ALLOW_LIST_USERS) != 0 && in_array($id->login, $ct_auth->allow_list_array))
                $allow = true;
        }
        if ($ac_auth != null) {
            $mode = $ac_auth->access_control_mode;

            if(isset($ac_auth->deny_list))
                $ac_auth->deny_list_array=explode(',', $ac_auth->deny_list);
            else
                $ac_auth->deny_list_array=array();
                    
            if(isset($ac_auth->allow_list))
                    $ac_auth->allow_list_array=explode(',', $ac_auth->allow_list);
            else
                $ac_auth->allow_list_array=array();
            
            // recherche les deny
            if (($mode & \Autorisation::DENY_ANONYMOUS) != 0 && $id->is_anonymous)
                $allow = false;

            if (($mode & \Autorisation::DENY_ALL) != 0)
                $allow = false;

            if (($mode & \Autorisation::DENY_LIST_USERS) != 0 && in_array($id->login, $ac_auth->deny_list_array))
                $allow = false;

            // recherche les autorisations
            if (($mode & \Autorisation::ALLOW_ALL) != 0)
                $allow = false;

            if (($mode & \Autorisation::ALLOW_ANONYMOUS) != 0 && $id->is_anonymous)
                $allow = true;

            if (($mode & \Autorisation::ALLOW_LIST_USERS) != 0 && in_array($id->login, $ac_auth->allow_list_array))
                $allow = true;
        }
        
        return $allow;
    }

    /**
     * Obtient l'identité de l'utilisateur
     *
     * @return Identity
     */
    public static function get_user_identity()
    {
        $id = Authentication::user_identity();
        return $id;
    }

    /*
     * Bundles
     */
    public static $bundles = array();

    /**
     * Ajoute un bundle
     *
     * @param string $bundle_name
     * @param cssItemBundle $bundle
     */
    public static function add_bundle($bundle_name, $bundle)
    {
        FPLGlobal::$bundles[$bundle_name] = $bundle;
    }

    /**
     * Effectue le rendu des bundles CSS (contexte <head>)
     */
    public static function render_bundle_css()
    {
        foreach (FPLGlobal::$bundles as $bundle_name => $bundle) {
            $bundle->render_css_set();
        }
    }

    /**
     * Effectue le rendu des bundles javascript (contexte head)
     */
    public static function render_bundle_script()
    {
        foreach (FPLGlobal::$bundles as $bundle_name => $bundle) {
            $bundle->render_script_set();
        }
    }

    /*
     * Thèmes
     */
    public static $theme = "default";

    /**
     * Donne l'URI du thème courant
     *
     * @return string
     */
    public static function get_theme_uri()
    {
        return "themes/" . FPLGlobal::$theme;
    }

    /*
     * Actions
     */

    /**
     * Rend une action de type PartialViewResult
     *
     * @param string $zone
     * @param string $controller
     * @param string $action
     * @param string $params_array
     */
    public static function render_action($zone, $controller, $action, $params_array = null)
    {
        $route = FPLGlobal::get_route_uri($zone, $controller, $action, $params_array);
        $target_action = FPLGlobal::get_action($route, $route);

        $model = new Entity();
        require_once FPLGlobal::get_controller_path($target_action);

        $action_method = FPLGlobal::get_action_method($target_action, $route, $model);
        $ac_mt = $action_method["ac_mt"];
        $ct_ob = $action_method["ct_ob"];

        $action_result = $ac_mt->invoke($ct_ob, $model);
        if ($action_result instanceof PartialViewResult) {
            echo $action_result->view_source;
        }
        if ($action_result instanceof JsonResult) {
            echo $action_result->json_data;
        }
        if ($action_result instanceof JavaScriptResult) {
            echo $action_result->javascript_text;
        }
    }

    /**
     * Retourne le résultat d'une action de type PArtialViewResult
     *
     * @param string $zone
     * @param string $controller
     * @param string $action
     * @param array $params_array
     * @return string|NULL
     */
    public static function action($zone, $controller, $action, $params_array = null)
    {
        $route = FPLGlobal::get_route_uri($zone, $controller, $action, $params_array);
        $target_action = FPLGlobal::get_action($route, $route);

        $model = new Entity();
        require_once FPLGlobal::get_controller_path($target_action);

        $action_method = FPLGlobal::get_action_method($target_action, $route, $model);
        $ac_mt = $action_method["ac_mt"];
        $ct_ob = $action_method["ct_ob"];

        $action_result = $ac_mt->invoke($ct_ob, $model);
        if ($action_result instanceof PartialViewResult) {
            return $action_result->view_source;
        }
        if ($action_result instanceof JsonResult) {
            return $action_result->json_data;
        }
        if ($action_result instanceof JavaScriptResult) {
            return $action_result->javascript_text;
        }
        return null;
    }

    /**
     * Namespace des contrôleurs pour l'application
     *
     * @var string
     */
    public static $namespace;

    /**
     * décode la requête et identifie zone / controller / action
     *
     * @param string $route
     *            Requête (REQUEST_URI)
     * @param string $default_route
     *            Route par défaut
     * @return Array
     */
    public static function get_action($route, $default_route)
    {
        $routes = explode("/", $route);
        $route = $routes[count($routes) - 1];
        $action_name = explode("-", strstr($route, ".do", true));

        $zone = "";
        $controller = "";
        $action = "";

        if (count($action_name) == 3) {
            $zone = $action_name[0];
            $controller = $action_name[1];
            $action = $action_name[2];
        } else if (count($action_name) == 2) {
            $zone = null;
            $controller = $action_name[0];
            $action = $action_name[1];
        } else {
            $zone = null;
            $controller = null;
            $action = $action_name[0];
        }

        return array(
            'zone' => $zone,
            'controller' => $controller,
            'action' => $action
        );
    }

    /**
     * Décode la route (REQUEST URI) en fonction de l'annotation de l'action et identifie les paramètres
     *
     * @param string $route
     *            Request URI
     * @param string $route_at
     *            Route indiquée dans l'action par annotation
     * @param Entity $model
     *            Modèle recevant les paramètres de la route
     */
    public static function get_params($route, $route_at, $model)
    {
        // trouver les paramètres dans l'attribut de route
        $mask = '#\{([a-zA-Z0-9]+)\}#';
        $val_at = array();
        preg_match_all($mask, $route_at, $val_at);

        // rechercher les valeurs de paramètres dans la route
        $prs = $val_at[0];
        foreach ($prs as $p) {
            $index_p = strstr($route_at, $p, true);

            $val_mask = "#" . $index_p . "([a-zA-Z0-9]+)#";
            $val_match = array();
            preg_match($val_mask, $route, $val_match);

            $val_value = $val_match[1];
            $route_at = str_replace($p, $val_value, $route_at);

            $p_name = str_replace('{', '', $p);
            $p_name = str_replace('}', '', $p_name);
            $model->$p_name = $val_value;
        }
    }

    /**
     * Compose la route à partir des paramètres indiqués en annotation
     *
     * @param string $route_at
     * @param array $params_array
     * @return string
     */
    public static function get_route_params($route_at, $params_array)
    {
        $mask = '#\{([a-zA-Z0-9])+\}#';
        $val_at = array();
        preg_match_all($mask, $route_at, $val_at);

        // rechercher les valeurs de paramètres dans la route
        $prs = $val_at[0];
        $route = $route_at;
        foreach ($prs as $p) {
            $p_name = $p;
            $p_name = str_replace("{", "", $p_name);
            $p_name = str_replace("}", "", $p_name);

            $route = str_replace($p, $params_array[$p_name], $route);
        }

        return $route;
    }

    /**
     * Donne le chemin d'un contrôlleur .php (zone / controlleur)
     *
     * @param array $target_action
     * @return string
     */
    public static function get_controller_path($target_action)
    {
        return "controllers/" . $target_action['zone'] . "/" . $target_action['controller'] . "Controller.php";
    }

    /**
     * Instancie le contrôleur, cible l'action et décode la route qui peut contenir des paramètres
     *
     * @param array $target_action
     * @param string $route
     * @param Entity $model
     * @return mixed[]
     */
    public static function get_action_method($target_action, $route, $model)
    {
        $cl_name = FPLGlobal::$namespace . $target_action['zone'] . "\\" . $target_action['controller'] . "controller";
        $ct_cl = new ReflectionClass($cl_name);
        $ct_ob = $ct_cl->newInstance();

        $ac_mt = $ct_cl->getMethod($target_action['action']);

        // existence de dépendances au niveau du contrôleur
        $di_components = null;
        $ct_di = new ReflectionAnnotatedClass($cl_name);
        $ct_di_at = $ct_di->getAnnotation("DIComponent");
        
        if ($ct_di_at instanceof \DIComponent) {
            $di_components = $ct_di_at;
        
            $di_fields = explode(",", $di_components->field_names);
            $di_classes = explode(",", $di_components->class_names);
            
            for($i = 0; $i < count($di_fields); $i++)
            {
                $di_field = $di_fields[$i];
                $di_instance = (new ReflectionClass($di_classes[$i]))->newInstance();
                $ct_ob->$di_field = $di_instance;
            }
        }
        
        // existence d'une route avec paramètres ?
        $route_at = new ReflectionAnnotatedMethod($cl_name, $target_action['action']);
        $route_v = "";
        if ($route_at->hasAnnotation("Route")) {
            $route_v = $route_at->getAnnotation("Route");
            $route_at = $target_action['zone'] . "-" . $target_action['controller'] . '-' . $target_action['action'] . '.do-' . $route_v->parameters;
            FPLGlobal::get_params($route, $route_at, $model);
        }

        // existence de requêtes d'autorisation au niveau du contrôleur ou de l'action
        $ct_auth = null;
        $ct_auth_ac = new ReflectionAnnotatedClass($cl_name);
        $ct_auth_at = $ct_auth_ac->getAnnotation("Autorisation");

        if ($ct_auth_at instanceof \Autorisation) {
            $ct_auth = $ct_auth_at;
        }

        $ac_auth = null;
        $route_auth = new ReflectionAnnotatedMethod($cl_name, $target_action['action']);
        if ($route_auth->hasAnnotation("Autorisation")) {
            $ac_auth = $route_auth->getAnnotation("Autorisation");
        }
        return array(
            "ct_ob" => $ct_ob,
            "ac_mt" => $ac_mt,
            "route_v" => $route_v,
            "ct_auth" => $ct_auth,
            "ac_auth" => $ac_auth,
            "di_components" => $di_components
        );
    }

    /**
     * Compose la partie d'une route qui comprend des paramètres
     *
     * @param string $route
     * @param array $params_array
     * @return string
     */
    public static function get_action_route_value($route, $params_array)
    {
        $target_action = FPLGlobal::get_action($route, $route);

        $cl_name = FPLGlobal::$namespace . $target_action['zone'] . "\\" . $target_action['controller'] . "controller";

        // existence d'une route avec paramètres ?
        $route_at = new ReflectionAnnotatedMethod($cl_name, $target_action['action']);

        $route_value = "";
        if ($route_at->hasAnnotation("Route")) {
            $route_v = $route_at->getAnnotation("Route");
            $route_at = $route_v->parameters;
            $route_value = FPLGlobal::get_route_params($route_at, $params_array);
        }

        return $route_value;
    }

    /**
     * Compose l'URI complète d'une route avec paramètres
     *
     * @param string $zone
     * @param string $controller
     * @param string $action
     * @param array $params_array
     * @return string
     */
    public static function get_route_uri($zone, $controller, $action, $params_array = null)
    {
        $uri = "$zone-$controller-$action.do";
        if (isset($params_array) && $params_array != null) {
            $uri .= "-" . FPLGlobal::get_action_route_value($uri, $params_array);
        }
        return $uri;
    }

    /**
     * Décode le post HTTP et instancie une entité
     *
     * @return Entity
     */
    public static function get_request_data()
    {
        $model = new Entity();
        foreach ($_POST as $k => $v) {
            if ($k != null && isset($k))
                $model->$k = $v;
        }

        foreach ($_GET as $k => $v) {
            if ($k != null && isset($k))
                $model->$k = $v;
        }

        return $model;
    }

    /*
     * Gestion des vues et des modèles
     */
    public static $action_result;

    // résultat de l'action
    public static $view_result;

    // résultat de l'action si ViewResult
    public static $view_path;

    // chemin (relatif) de la vue

    /**
     * Effectue le rendu de la vue courante (view_path)
     */
    public static function render_view()
    {
        require_once FPLGlobal::$view_path;
    }

    /**
     * Inclus une vue partielle
     *
     * @param string $zone
     * @param string $partial_view_name
     *            chemin / nom de la vue sans extension
     */
    public static function render_partial_view($zone, $partial_view_name)
    {
        $partial_view_path = 'views/' . $zone . "/" . $partial_view_name . ".php";
        require_once $partial_view_path;
    }

    /**
     * Retourne le contenu d'une vue partielle
     *
     * @param string $zone
     * @param string $partial_view_name
     * @return string
     */
    public static function partial_view($zone, $partial_view_name)
    {
        $partial_view_path = 'views/' . $zone . "/" . $partial_view_name . ".php";
        $text = file_get_contents($partial_view_path);
        return $text;
    }

    /**
     * Exécute le rendu d'une vue avec ou sans gabarit
     *
     * @param array $target_action
     */
    public static function process_view_result($target_action)
    {
        FPLGlobal::$view_result = FPLGlobal::$action_result;
        FPLGLobal::$view_path = 'views/' . $target_action['zone'] . "/" . FPLGLobal::$action_result->view_name . ".php";
        $view_text = file_get_contents(FPLGLobal::$view_path);

        // Recherche un gabarit {layout:path-to-layout}
        $lay_match = array();
        $lay_model = '#\{layout:(.+)\}#';
        preg_match($lay_model, $view_text, $lay_match);
        if (isset($lay_match) && count($lay_match) == 2) {
            $layout_path = $lay_match[1];
            require_once 'views/' . $target_action['zone'] . "/" . $layout_path;
        } else {
            FPLGlobal::render_view();
        }
    }

    public static $view_bag;

    // entité
    public static $view_data; // array
    
    public static function process_result($target_action){
        $redir=true;
        if (FPLGLobal::$action_result instanceof ViewResult) {
            if ($redir) {
                FPLGlobal::process_view_result($target_action);
            }
        }
        if(FPLGlobal::$action_result instanceof RedirectResult) {
            header("location:".(FPLGlobal::$action_result->uri));
        }
        if(FPLGlobal::$action_result instanceof RedirectToRouteResult) {
            header("location:".(FPLGlobal::get_route_uri(
                FPLGlobal::$action_result->zone, FPLGlobal::$action_result->controller, FPLGlobal::$action_result->action)));
        }
        if(FPLGlobal::$action_result instanceof JsonResult){
            header("Content-Type: application/json");
            echo FPLGlobal::$action_result->json_data;
        }
        if(FPLGlobal::$action_result instanceof XmlResult){
            header("Content-Type: text/xml");
            echo FPLGlobal::$action_result->xml_data;
        }
        if(FPLGlobal::$action_result instanceof JavaScriptResult){
            header("Content-Type: application/javascript");
            echo FPLGlobal::$action_result->javascript_text;
        }
        if(FPLGlobal::$action_result instanceof PartialViewResult){
            echo FPLGlobal::$action_result->view_source;
        }
    }
    
}
?>