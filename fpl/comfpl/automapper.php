<?php
/*
 * automapper.php
 * (C) 2019 BAG
 * map (converti) un objet dans une classe donnée
 */

/**
 * Convertit un objet dans une classe donnée
 * @author guerin
 *
 */
class Automapper {
    /**
     * Convertit un objet dans une classe données en suivant un schéma
     * @param object $src
     * @param ReflectionClass $t_class
     * @param number $schema 0 target vers src 1 src vers target
     * @return object
     */
    public static function map($src,ReflectionClass $t_class,$schema=0) {
        $t_object = $t_class->newInstance();
        
        // $schema = 0 : $target vers $src
        // $schema = 1 : $src vers $target
        if($schema==0)
        {
            // seules les propriétés de $t_class sont initialisées à partir de la source
            $p_list = $t_class->getProperties();
            foreach($p_list as $prop) {
                $p_name = $prop->getName();
                if(isset($src->$p_name))
                    $t_object->$p_name = $src->$p_name;
            }
        } else {
            // toutes les propriétés de $src sont initialisées
            $s_class = new ReflectionObject($src);
            $p_list = $s_class->getProperties();
            foreach($p_list as $prop) {
                $p_name = $prop->getName();
                $t_object->$p_name = $src->$p_name;
            }
        }
        return $t_object;
    }
}
?>