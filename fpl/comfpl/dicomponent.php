<?php
/*
 * dicomponent.php
 */

/**
 * Annotation pour injecter des dépendances au niveau du contrôleur
 * 
 */
class DIComponent extends Annotation
{
    /**
     * Liste de champs séparés par une virgule
     * @var string
     */
    public $field_names;
    
    /**
     * Liste de classes séparées par une virgule
     * @var string
     */
    public $class_names;
}
?>