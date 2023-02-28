<?php
/**
 * Classe d'entité générique qui recevra les données d'un post
 * @author guerin
 *
 */
class Entity{
    
}

class TypeInteger extends Annotation {
    public $error;
    public $property;
}
class TypeNumeric extends Annotation {
    public $error;
    public $property;
}
class Required extends Annotation {
    public $error;
    public $property;
}
class TypeDate extends Annotation {
    public $error;
    public $format;
    public $property;
}
class Rexvalid extends Annotation {
    public $error;
    public $format;
    public $property;
}
class Lengthvalid extends Annotation {
    public $error;
    public $length;
    public $property;
}

function validateDate($date, $format = 'd/m/Y') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

?>