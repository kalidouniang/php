<?php
/*
 * Router.php
 * (C) 2019 BAG
 * Active le contrôleur et l'action en fonction de la route
 * Rend la vue et son gabarit s'il est défini
 */
require_once '../comfpl/main.php';
require_once 'config.php';

/*
 * Run action
 */
$route = $_SERVER['REQUEST_URI'];
$target_action = FPLGlobal::get_action($route, FPLGlobal::$default_route);
$model = FPLGlobal::get_request_data();

require_once FPLGlobal::get_controller_path($target_action);

$action_method = FPLGlobal::get_action_method($target_action, $route, $model);
$ac_mt = $action_method["ac_mt"];
$ct_ob = $action_method["ct_ob"];

FPLGlobal::$view_bag = new Entity();
FPLGlobal::$view_data = array();

if (! FPLGlobal::check_autorisation($action_method["ct_auth"], $action_method["ac_auth"])) {
    header("location:" . FPLGlobal::$login_route);
    die();
}
FPLGLobal::$action_result = $ac_mt->invoke($ct_ob, $model);

/*
 * Render action result
 */
FPLGlobal::process_result($target_action);
?>