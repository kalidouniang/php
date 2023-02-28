<?php
/*
 * baseController.php
 * (C) 2019 BAG
 */
namespace comfpl\controllers;

use comfpl\views\ViewResult;
use comfpl\views\RedirectResult;
use comfpl\views\RedirectToRouteResult;
use comfpl\views\XmlResult;

/**
 * Contrôleur de base
 *
 * @author guerin
 *        
 */
class BaseController
{

    /*
     * Action results
     */
    public function View($model = null, $view_name = null)
    {
        return new ViewResult($model, $view_name,$this->is_model_valid,$this->validation_errors);
    }

    public function Redirect($uri)
    {
        return new RedirectResult($uri);
    }

    public function RedirectToRoute($zone, $controller, $action)
    {
        return new RedirectToRouteResult($zone, $controller, $action);
    }

    /*
     * Modèle et validation
     */
    public $is_model_valid;

    public $validation_errors;
    function add_error($error,$property) {
        if(!isset($this->validation_errors[$property]) || $this->validation_errors[$property]==null)
            $this->validation_errors[$property]=array();
        $this->validation_errors[$property][] = $error;
        $this->is_model_valid = false;
    }
    
    public function is_post() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    
    /**
     * Valide un modèle en suivant les annotations
     * @param \Entity $model
     */
    function validate_model($model) {
        $this->is_model_valid = true;
        $this->validation_errors = array();
        $p_c = new \ReflectionObject($model);

        $fields = $p_c->getProperties();
        foreach ($fields as $field) {

            $field_name = $field->name;
            $field_value = $model->$field_name;

            try {
                $p_m = new \ReflectionAnnotatedProperty($field->class, $field->name);
                $check_anos = $p_m->getAllAnnotations();
                foreach ($check_anos as $chk) {
                    if ($chk instanceof \TypeInteger) {
                        if (! (is_numeric($field_value) && is_integer($field_value*1))) {
                            $this->add_error($chk->error, $chk->property);
                        }
                    }
                    if ($chk instanceof \TypeNumeric) {
                        if (! (is_numeric($field_value))) {
                            $this->add_error($chk->error, $chk->property);
                        }
                    }
                    if ($chk instanceof \Required) {
                        if (! ($field_value!=null && $field_value!="")) {
                            $this->add_error($chk->error, $chk->property);
                        }
                    }
                    if ($chk instanceof \TypeDate) {
                        if (! validateDate($field_value, $chk->format)) {
                            $this->add_error($chk->error, $chk->property);
                        }
                    }
                    if ($chk instanceof \Rexvalid) {
                        if (preg_match("#".$chk->format."#", $field_value)==0) {
                            $this->add_error($chk->error, $chk->property);
                        }
                    }
                    if ($chk instanceof \Lengthvalid) {
                        if (strlen($field_value)<$chk->length) {
                            $this->add_error($chk->error, $chk->property);
                        }
                    }
                }
            }catch(\Exception $exp) {
            }
        }
    }
}

?>