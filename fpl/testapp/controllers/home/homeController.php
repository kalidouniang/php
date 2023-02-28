<?php
namespace testapp\Controllers\home;

use comfpl\controllers\BaseController;
use comfpl\views\PartialViewResult;
use comfpl\views\JsonResult;
use comfpl\views\JavaScriptResult;
require_once 'Models/simpleModel.php';

/**
 * @Autorisation(access_control_mode=Autorisation::DENY_ANONYMOUS)
 * @author guerin
 *
 */
class homeController extends BaseController
{
    /**
     * @Autorisation(access_control_mode=Autorisation::DENY_LIST_USERS, deny_list='John')
     */
    public function index($view_model)
    {
        $model = \Automapper::map($view_model, new \ReflectionClass("\simpleModel"));

        $model->title = "Accueil";
        $model->villes = array(
            "P" => "Paris",
            "M" => "Marseille",
            "T" => "Toulon"
        );

        if (! $this->is_post()) {
            if ($model->idc == null || $model->idc == "")
                $model->idc = "abc_cache_def";
            
            if (! isset($model->contact))
                    $model->contact = "email";
        } else {
            if (isset($_FILES['resume'])) {
                $model->resume = $_FILES['resume']['name'] . ' ' . $_FILES['resume']['type'] . ' ' . $_FILES['resume']['size'];
            }
            
            $this->validate_model($model);
        }
        if ($model->d_accord == "true")
            return $this->View($model, "Index2");
        else
            return $this->View($model, "Index");
    }

    public function index2($model)
    {
        $model->title = "Page 2";
        $model->message = "Pass&eacute; dans Index2";
        return $this->View($model, "Index2");
    }

    /**
     *
     * @Route(parameters="id-{id}-cat-{categorie}")
     */
    public function livre($model)
    {
        $vmodel = new \Entity();
        $vmodel->title = "Livre";
        $vmodel->id = $model->id;
        $vmodel->categorie = $model->categorie;

        \FPLGlobal::$view_bag->message = "Vous avez choisi un livre pas cher";
        \FPLGlobal::$view_data["notice"] = "En savoir plus";

        return $this->View($vmodel, "livre");
    }

    /**
     *
     * @Route(parameters="id-{id}")
     * @param \Entity $model
     * @return \comfpl\views\PartialViewResult
     */
    public function partialheader($model)
    {
        $view_source = "<div style='border: 1 solid black;'>Stock partiel pour id $model->id</div>";
        return new PartialViewResult($view_source);
    }
    
    function get_livres($page=null,$limit=null){
        $p = array();
        //$p[0]=array("id"=>0,'titre'=>"page=$page limit=$limit");
        $p[0]=array("id"=>0,'titre'=>"the log the 80&quot;");
        $p[1]=array("id"=>1,"titre"=>utf8_encode("Les grandes découvertes"));
        $p[2]=array("id"=>2,"titre"=>"The man behind the chips");
        $p[3]=array("id"=>3,"titre"=>"Green IT");
        $p[4]=array("id"=>4,"titre"=>utf8_encode("Sacré waffer"));
        $p[5]=array("id"=>5,"titre"=>utf8_encode("du germanium pour les plantes"));
        $p[6]=array("id"=>6,"titre"=>utf8_encode("Silice au pays des merveilles"));
        
        $r=array();
        if($page!=null && $limit!=null)
        {
            $start = ($page - 1) * $limit;
            //$p[0]=array("id"=>0,'titre'=>"page=$page limit=$limit start=$start");
        } else {
            $start=0;
            $limit=count($p);
        }
        for($i=$start;$i<$start+$limit && $i<count($p);$i++)
            $r[]=$p[$i];
        
        return array("records"=>$r,"total"=>count($p));
    }
    
    /**
     * Action get_livre_data
     * @param \Entity $model
     * @return \comfpl\views\JsonResult
     */
    public function get_livre_data($model) {
        $page = isset($model->page)?$model->page:null;
        $limit=isset($model->limit)?$model->limit:null;
        
        $p=$this->get_livres($page,$limit);
        $js = json_encode($p);
        
        return new JsonResult($js);
    }
    
    public function dire_js_bonjour($model){
        return new JavaScriptResult("alert('coucou');");
    }
}

?>