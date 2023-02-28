<?php
/*
 * viewscript.php
 */

class ViewScript 
{
    /**
     * Code HTML du fragment à traiter par script de substitution
     * @var string
     */
    public $view_source;
    
    /**
     * Code HTML de sortie
     * @var string
     */
    public $view_html;
    
    /**
     * Tableau associatif de variables
     * @var array
     */
    public $view_context;
    
    public function __construct()
    {
        $this->view_source="";
    }
    
    public function set_view_source_from_file($filename)
    {
        $this->view_source = file_get_contents($filename);
    }
    
    public function render_view()
    {
        $this->view_html = $this->view_source;
        $var_match = array();
        $var_model = '#\{\{(.+)\}\}#';
        preg_match($var_model, $this->view_html, $var_match);
        $this->view_html = preg_replace_callback($var_model, function ($match) 
        { 
            return $this->view_context[$match[1]];
        }, $this->view_source);
        
        return $this->view_html;
    }
}
?>