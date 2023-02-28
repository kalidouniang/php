<?php
namespace testapp\Controllers\usermgt;

use comfpl\controllers\BaseController;
require_once 'Models/loginModel.php';

/**
 * @Autorisation($access_control_mode=Autorisation::ALLOW_ALL | Autorisation::DENY_ANONYMOUS)
 * @author guerin
 *
 */
class userController extends BaseController
{
    function Login($view_model) {
        $model = \Automapper::map($view_model, new \ReflectionClass("\loginModel"));
        
        if ($this->is_post()) {
            $this->validate_model($model);
            if(! $this->is_model_valid) {
                return $this->View($model,"login");
            } 
             
            if(! \Authentication::authenticate($model->login, $model->pwd)) {
                \FPLGlobal::$view_bag->message="Echec &agrave; la connexion";
                return $this->View($model,"login");
            }
                    
            // l'utilisateur est connecté
            return $this->Redirect(\FPLGlobal::$default_route);
        }
        
        return $this->View($model,"login");
    }
    
    function logout($model) {
        \Authentication::logout();
        return $this->View(new \loginModel(),"login");
    }
}