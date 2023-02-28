<?php 
/*
 * config.php
 * (C) 2019 BAG
 * Fichier de configuration général
 */
    require_once '../comfpl/main.php';
    FPLGlobal::$theme="default";
    FPLGlobal::$default_route="home-home-index.do";
    FPLGlobal::$login_route="usermgt-user-login.do";
    FPLGlobal::$namespace = "testapp\\controllers\\";
    
    session_start();
    
    /*
     * bundle bootstrap
     */
    $bootstrap = new bundle();
    $bootstrap->css_set=array(
//         new cssItemBundle("https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css",
//             "sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T",
//             "anonymous"),
        
        new cssItemBundle("https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"),
        new cssItemBundle("http://cdn.phpoll.com/css/animate.css"),
        new cssItemBundle("https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css"),
        new cssItemBundle("https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css")
        
    );
    $bootstrap->script_set=array(
        new scriptItemBundle("https://code.jquery.com/jquery-3.3.1.min.js"),
        new scriptItemBundle("https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js"),
//         new scriptItemBundle("https://code.jquery.com/jquery-3.3.1.slim.min.js",
//             "sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo",
//             "anonymous"),
        new scriptItemBundle("https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js",
            "sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1",
            "anonymous"),
        new scriptItemBundle("https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js",
            "sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM",
            "anonymous")
    );
    FPLGlobal::add_bundle("bootstrap", $bootstrap);
    
    FPLGlobal::$mockup_credentials=array(new Credentials('Geeck','go'),new Credentials('John','doe'));
?>