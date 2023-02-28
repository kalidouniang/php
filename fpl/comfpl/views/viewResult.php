<?php
namespace comfpl\views;

/*
 * viewResult.php
 * (C) 2019 BAG
 */

/**
 * ActionResult générique et abstraite
 * @author guerin
 *
 */
abstract class ActionResult
{
}

/**
 * associé à baseController::View($view)
 * @author guerin
 *
 */
class ViewResult extends ActionResult
{
    public $view_name;
    public $model;
    public $is_model_valid;
    public $validation_errors;

    /**
     * Retourne le résumé de toutes les erreurs de validation
     * @return string
     */
    public function get_validation_summary() {
        if($this->is_model_valid!=false || $this->validation_errors==null)
            return "";
        
        $summary="";
        foreach($this->validation_errors as $v) {
            foreach($v as $vv)
                $summary.="<li>".$vv."</li>";
        }
        
        return $summary;
    }
    
    /**
     * Retourne le message d'erreur fournit si le champ n'est pas valide et sinon le résumé des erreurs pour ce champ
     * @param string $property
     * @param string $validation_msg
     * @return string
     */
    public function get_validation_for($property,$validation_msg) {
        if($this->is_model_valid!=false || 
            $this->validation_errors==null || 
            !isset($this->validation_errors[$property]))
            return "";
            
        if($validation_msg!=null)
            return $validation_msg;
        
        $summary="";
        foreach($this->validation_errors[$property] as $v) {
            $summary.="<li>".$v."</li>";
        }
        
        return $summary;
    }
    
    // entité
    public function __construct($model = null, $view_name = null,$is_model_valid=null,$validation_errors=null)
    {
        $this->model = $model;
        $this->view_name = $view_name;
        $this->is_model_valid=$is_model_valid;
        $this->validation_errors=$validation_errors;
    }
}

/**
 * associé à baseController::Redirect($url)
 * @author guerin
 *
 */
class RedirectResult extends ActionResult
{

    public $uri;

    public function __construct($uri)
    {
        $this->uri = $uri;
    }
}

/**
 * associé à baseController::RedirectToRoute($route)
 * @author gueri
 *
 */
class RedirectToRouteResult extends ActionResult
{
    public $zone;
    public $controller;
    public $action;

    public function __construct($zone, $controller, $action)
    {
        $this->zone = $zone;
        $this->controller = $controller;
        $this->action = $action;
    }
}

/**
 * associé à baseController::Json($json)
 * @author gueri
 *
 */
class JsonResult extends ActionResult
{

    public $json_data;

    /**
     * 
     * @param string $json_data flux json
     */
    public function __construct($json_data)
    {
        $this->json_data = $json_data;
    }
}

/**
 * associé à baseController::Xml($json)
 * @author gueri
 *
 */
class XmlResult extends ActionResult
{
    
    public $xml_data;
    
    /**
     *
     * @param string $json_data flux json
     */
    public function __construct($xml_data)
    {
        $this->xml_data = $xml_data;
    }
}

/**
 * associé à baseController::JavaScript($text)
 * @author gueri
 *
 */
class JavaScriptResult extends ActionResult
{

    public $javascript_text;

    public function __construct($javascript_text)
    {
        $this->javascript_text = $javascript_text;
    }
}

/**
 * associé à baseController::PartialView($view_text)
 * @author guerin
 *
 */
class PartialViewResult extends ActionResult
{
    public $view_source;

    public function __construct($view_source)
    {
        $this->view_source = $view_source;
    }
}
?>