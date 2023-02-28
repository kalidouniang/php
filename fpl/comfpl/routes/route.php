<?php
/**
 * Annotation Route pour désigner des paramètres dans une URL
 * @author guerin
 *
 */
class Route extends Annotation {
    /*
     * Attributs possibles
     */
//     public $zone;
//     public $controller;
//     public $action;
//      public $verb;
    
    /**
     * D'une une URI formée de paramètres par exemple "client-{idc}-categories-{cat}"
     * @var string
     */
    public $parameters;
    
}
?>