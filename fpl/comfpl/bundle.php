<?php

/*
 * bundle.php
 * (C) 2019 BAG
 */

/**
 * Défini un bundle (ensemble de scripts)
 *
 * @author guerin
 *        
 */
class scriptItemBundle
{

    public $script_src;

    public $script_type;

    public $script_integrity;

    public $script_crossorigin;

    public function __construct($script_src, $script_integrity = null, $script_crossorigin = null, $script_type = null)
    {
        $this->script_src = $script_src;
        $this->script_type = $script_type;
        $this->script_integrity = $script_integrity;
        $this->script_crossorigin = $script_crossorigin;
    }
}

/**
 * Défini un bundle (ensemble de css)
 *
 * @author guerin
 *        
 */
class cssItemBundle
{

    public $css_src;

    public $css_integrity;

    public $css_crossorigin;

    public function __construct($css_src, $css_integrity = null, $css_crossorigin = null)
    {
        $this->css_src = $css_src;
        $this->css_integrity = $css_integrity;
        $this->css_crossorigin = $css_crossorigin;
    }
}

/**
 * Regroupe un ensemble de CSS et de Scripts
 *
 * @author guerin
 *        
 */
class bundle
{

    public $bundle_name;

    public $script_set;

    // array
    public $css_set;

    public function __construct()
    {
        $this->script_set = array();
        $this->css_set = array();
    }

    public function render_css_set()
    {
        foreach ($this->css_set as $css) {
            $integrity = isset($css->css_integrity) && $css->css_integrity != null ? " integrity='$css->css_integrity'" : "";
            $crossorigin = isset($css->css_crossorigin) && $css->css_crossorigin != null ? " crossorigin='$css->css_crossorigin'" : "";

            echo "<link rel='stylesheet' href='$css->css_src'$integrity$crossorigin/>";
        }
    }

    public function render_script_set()
    {
        foreach ($this->script_set as $script) {
            $integrity = isset($script->script_integrity) && $script->script_integrity != null ? " integrity='$script->script_integrity'" : "";
            $crossorigin = isset($script->script_crossorigin) && $script->script_crossorigin != null ? " crossorigin='$script->script_crossorigin'" : "";
            $type = isset($script->script_type) && $script->script_type != null ? " type='$script->script_type'" : "";
            echo "<script src='$script->script_src'$integrity$crossorigin$type></script>";
        }
    }
}
?>