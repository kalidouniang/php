<?php
class simpleModel {
    /**
     * @TypeNumeric(error="l'age n'est pas numerique",property="age")
     * @TypeInteger(error="l'age doit &ecirc;tre un entier",property="age")
     */
    public $age; 
    
    /**
     * 
     * @Required(error="le nom est requis",property="nom")
     */
    public $nom; 
    public $ville; 
    
    public $villes; 
    public $d_accord; 
    
    public $contact; 
    
    /**
     * @Lengthvalid(error="au moins 10 cars la demande",length=10,property="demande")
     * @Rexvalid(error="pas le bon format de demande",format="demande",property="demande")
     */
    public $demande; 
    public $idc; 
    
    public $resume;
}
?>