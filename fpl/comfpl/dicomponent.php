<?php
/*
 * dicomponent.php
 */

/**
 * Annotation pour injecter des d�pendances au niveau du contr�leur
 * 
 */
class DIComponent extends Annotation
{
    /**
     * Liste de champs s�par�s par une virgule
     * @var string
     */
    public $field_names;
    
    /**
     * Liste de classes s�par�es par une virgule
     * @var string
     */
    public $class_names;
}
?>