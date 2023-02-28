<?php

/*
 * security.php
 * (C) 2019 BAG
 * Autorisations et authentification
 */

/**
 * Définit des requêtes d'autorisation au niveau du contrôleur ou de l'action
 * @author guerin
 *
 */
class Autorisation extends Annotation
{

    /**
     * Accepte tous les utilisateurs
     */
    public const ALLOW_ALL = 1;

    /**
     * Accepte les utilisateurs anonymes
     */
    public const ALLOW_ANONYMOUS = 2;

    /**
     * Accepte une liste d'utilisateurs
     */
    public const ALLOW_LIST_USERS = 4;

    /**
     * Refuse tous les utilisateurs 
     */
    public const DENY_ALL = 8;
    
    /**
     * Refuse les utilisateurs anonymes
     */
    public const DENY_ANONYMOUS = 16;

    /**
     * Refuse une liste d'utilisateurs
     */
    public const DENY_LIST_USERS = 32;

    /**
     * Combinaison de modes d'accès ex. DENY_ALL | ALLOW_ANONYMOUS
     * @var integer
     */
    public $access_control_mode;

    /**
     * Liste d'utilisateurs acceptés
     * @var string
     */
    public $allow_list;
    public $allow_list_array;
    
    /**
     * Liste d'utilisateurs refusés
     * @var string
     */
    public $deny_list;
    public $deny_list_array;
}

/**
 * Identité générique
 *
 * @author guerin
 *        
 */
class Identity
{

    public $login;

    public $display_name;

    public $is_anonymous;

    public $is_authenticated;
}

class Credentials
{

    public $login;

    public $pwd;

    public function __construct($login = null, $pwd = null)
    {
        $this->login = $login;
        $this->pwd = $pwd;
    }
}

abstract class AuthenticationProvider
{

    /**
     * Vérifie les crédits de connexion et retourne un objet Identity
     *
     * @param string $login
     * @param string $pwd
     * @return Identity
     */
    public abstract function check_credentials($login, $pwd);
}

class GenericAuthenticationProvider extends AuthenticationProvider
{

    //public $connection_string;

private $db_connection;

public function __construct() {
    $this->db_connection = get_default_connection();
}

    /**
     *
     * {@inheritdoc}
     * @see AuthenticationProvider::check_credentials()
     */
    public function check_credentials($login, $pwd)
    {
        /*
         * vérifie si les credentials sont dans la configuration
         */ 
        foreach (FPLGlobal::$mockup_credentials as $cred) {
            if ($cred->login === $login && $cred->pwd === $pwd) {
                $id = new Identity();
                $id->login = $login;
                $id->display_name = $login;
                $id->is_anonymous = false;
                $id->is_authenticated = true;
    
                return $id;
            }
        }
    
        /*
         * vérifie en base de données
         */
        $cx = null;
        $result = null;
        try {
            $cx = mysqli_connect($this->db_connection["cx_server"], $this->db_connection["cx_login"], $this->db_connection["cx_pwd"], $this->db_connection["cx_dbname"]);
            $query = "select utilisateur_nom from utilisateur where utilisateur_login='$login' and utilisateur_pwd='$pwd'";
            $result = mysqli_query($cx, $query);
        
            if( ($row=mysqli_fetch_assoc($result))!=null) {
                $id = new Identity();
                $id->login = $login;
                $id->display_name = $row["utilisateur_nom"];;
                $id->is_anonymous = false;
                $id->is_authenticated = true;
                
                return $id;
            }
        }catch(Throwable $t){
            throw $t;
        }finally {        
            if($result!=null)
                mysqli_free_result($result);
            if($cx!=null)
                mysqli_close($cx);
        }
               
        // utilisateur inconnu
        $id = new Identity();
        $id->display_name = "";
        $id->is_anonymous = true;
        $id->is_authenticated = false;
        $id->login = "";
    
        return $id;
    }
}

class Authentication
{

    public const AUTH_SCHEME_GENERIC = 0;

    private const AUTH_SESSION_KEY = "user_identity";

    /**
     * Obtient l'identité de l'utilisateur ou null si pas d'authentification
     *
     * @return Identity
     */
    public static function user_identity()
    {
        if (isset($_SESSION[Authentication::AUTH_SESSION_KEY]))
            return $_SESSION[Authentication::AUTH_SESSION_KEY];

            $id = new Identity();
            $id->display_name = "";
            $id->is_anonymous = true;
            $id->is_authenticated = false;
            $id->login = "";
            
            $_SESSION[Authentication::AUTH_SESSION_KEY] = $id;
            return $id;
    }

    public static function authenticate($login, $pwd, $authentication_scheme = Authentication::AUTH_SCHEME_GENERIC)
    {
        $authProvider = null;
        switch ($authentication_scheme) {
            case Authentication::AUTH_SCHEME_GENERIC:
            default:
                $authProvider = new GenericAuthenticationProvider();
                break;
        }

        $id = $authProvider->check_credentials($login, $pwd);
        $_SESSION[Authentication::AUTH_SESSION_KEY] = $id;

        if ($id->is_authenticated) {
            return true;
        } else {
            return false;
        }
    }
    
    public static function logout() {
        $id = new Identity();
        $id->display_name = "";
        $id->is_anonymous = true;
        $id->is_authenticated = false;
        $id->login = "";
        
        $_SESSION[Authentication::AUTH_SESSION_KEY] = $id;
    }
}

?>