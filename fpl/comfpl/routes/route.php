<?php
/**
 * Annotation Route pour d�signer des param�tres dans une URL
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
     * D'une une URI form�e de param�tres par exemple "client-{idc}-categories-{cat}"
     * @var string
     */
    public $parameters;
    
}
?>