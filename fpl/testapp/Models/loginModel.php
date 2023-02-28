<?php
class loginModel {
  
    /**
     * @Required(error="L'identifiant est requis",property="login")
     * @var string
     */
    public $login;
    
    /**
     * @Required(error="Le mot de passe est requis",property="pwd")
     * @var string
     */
    public $pwd;
    
    public $login_action;
}
?>