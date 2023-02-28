<?php
/*
 * automapper.php
 * (C) 2019 BAG
 * map (converti) un objet dans une classe donn�e
 */

/**
 * Convertit un objet dans une classe donn�e
 * @author guerin
 *
 */
class Automapper {
    /**
     * Convertit un objet dans une classe donn�es en suivant un sch�ma
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
            // seules les propri�t�s de $t_class sont initialis�es � partir de la source
            $p_list = $t_class->getProperties();
            foreach($p_list as $prop) {
                $p_name = $prop->getName();
                if(isset($src->$p_name))
                    $t_object->$p_name = $src->$p_name;
            }
        } else {
            // toutes les propri�t�s de $src sont initialis�es
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