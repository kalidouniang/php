<?php
/*
 * viewComp.php
 * 18/8/2019 BAG
 */

/**
 * viewComp
 * @author gueri
 *
 */
abstract class viewComp {
    public $id;
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public $viewmodel;
    public function setViewModel($viewmodel)
    {
        $this->viewmodel = $viewmodel;
    }
    
    public $script;
    public $header;
    public $footer;
    
    public function configureJavaScript($script)
    {
        $this->script = $script;
    }
    
    public function configureHeader($header)
    {
        $this->header = $header;
    }
    
    public function configureFooter($footer)
    {
        $this->footer = $footer;
    }
    
    public function renderScript()
    {
        if(isset($this->script) && $this->script!=null)
            $this->script->call($this);
    }
    
    public function renderHeader()
    {
        if(isset($this->header) && $this->header!=null)
            $this->header->call($this);
    }
    
    
    public function renderFooter()
    {
        if(isset($this->footer) && $this->footer!=null)
            $this->footer->call($this);
    }
    
    public abstract function renderBody();
    
    public function render() {
        $this->renderScript();
        $this->renderHeader();
        $this->renderBody();
        $this->renderFooter();
    }
    
    /**
     * Instancie un composant vue
     * @param string $compClass
     * @param string $compid
     * @return viewcomp
     */
    public static function instanciate($compClass,$compid) {
        $cl = new ReflectionClass($compClass);
        $comp = $cl->newInstance();
        $comp ->setId($compid);
        return $comp;
    }
}

?>


