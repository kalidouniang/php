<?php
/*
 * html.php
 * (C) 2019 BAG
 */

/**
 * Helper HTML facilite la génération d'extraits HTML
 * @author guerin
 *
 */
class html
{
    public const RENDER_STYLE_HTML=0;
    public const RENDER_STYLE_BOOTSTRAP=1;
    
    /**
     * Définit le style de rendu (HTML ou Bootstrap)
     * @var integer
     */
    public static $render_style=html::RENDER_STYLE_HTML;
    
    /*
     * actions
     */
    public static function action_link($zone,$controller,$action,$text,$params_name=null) {
        echo "<a href='".FPLGlobal::get_route_uri($zone, $controller, $action,$params_name)."'>$text</a>";
    }
    public static function action_href($zone,$controller,$action,$params_name=null) {
        echo FPLGlobal::get_route_uri($zone, $controller, $action,$params_name);
    }
    public static function get_action_href($zone,$controller,$action,$params_name=null) {
        return FPLGlobal::get_route_uri($zone, $controller, $action,$params_name);
    }
    /*
     * form
     */
    public static function begin_form($zone,$controller,$action,$method="post",$params_name=null,$additional_view_data=array()) {
        $role="";
        if(html::$render_style==html::RENDER_STYLE_BOOTSTRAP)
            $role=" role='form'";
        
        $attr = html::get_additional_view_data($additional_view_data);
        echo "<form method='$method' action='".html::get_action_href($zone, $controller, $action,$params_name) ."'$attr$role>";
    }
    
    public static function end_form() {
        echo "</form>";
    }
    
    /*
     * contrôles de formulaire
     */
    private static function get_additional_view_data($additional_view_data) {
        $attr = "";
        foreach($additional_view_data as $k=>$v) {
            $attr.=" $k='$v'";
        }
        return $attr;
    }
    
    public static function label($label,$additional_view_data=array()) {
        $cls ="";
        if(html::$render_style==html::RENDER_STYLE_BOOTSTRAP) {
            if(! isset($additional_view_data['class']) || strstr('form-',$additional_view_data['class'])==false)
                $cls=" class='form-label'";
        }
        $attr = html::get_additional_view_data($additional_view_data);
        echo "<label$attr$cls>". $label ."</label>";
    }
    
    public static function label_for($data,$f_prop,$prop_name=null,$for=null,$additional_view_data=array()) {
        $cls ="";
        if(html::$render_style==html::RENDER_STYLE_BOOTSTRAP) {
            if(! isset($additional_view_data['class']) || strstr('form-',$additional_view_data['class'])==false)
                $cls=" class='form-label'";
        }
        
        $attr = html::get_additional_view_data($additional_view_data);
        $for_attr=$for!=null?" for='$for'":"";
        echo "<label$attr$for_attr$cls>". $f_prop($data) ."</label>";
    }
    public static function edit_for($data,$f_prop,$prop_name,$additional_view_data=array()) {
        $cls ="";
        if(html::$render_style==html::RENDER_STYLE_BOOTSTRAP)
            $cls=" class='form-control'";
        
        $attr = html::get_additional_view_data($additional_view_data);
        $v = $f_prop($data);
        echo "<input type='text' name='$prop_name' id='$prop_name' value='$v'$attr$cls>";
    }
    public static function password_for($data,$f_prop,$prop_name,$additional_view_data=array()) {
        $cls ="";
        if(html::$render_style==html::RENDER_STYLE_BOOTSTRAP)
            $cls=" class='form-control'";
            
            $attr = html::get_additional_view_data($additional_view_data);
            $v = $f_prop($data);
            echo "<input type='password' name='$prop_name' id='$prop_name' value='$v'$attr$cls>";
    }
    public static function hidden_for($data,$f_prop,$prop_name,$additional_view_data=array()) {
        $attr = html::get_additional_view_data($additional_view_data);
        $v = $f_prop($data);
        echo "<input type='hidden' name='$prop_name' id='$prop_name' value='$v'$attr>";
    }
    public static function fileupload_for($data,$f_prop,$prop_name,$additional_view_data=array()) {
        $cls ="";
        if(html::$render_style==html::RENDER_STYLE_BOOTSTRAP)
            $cls=" class='form-control'";
        
        $attr = html::get_additional_view_data($additional_view_data);
        $v = $f_prop($data);
        echo "<input type='file' name='$prop_name' id='$prop_name' value='$v'$attr$cls>";
    }
    public static function textedit_for($data,$f_prop,$prop_name,$additional_view_data=array()) {
        $cls ="";
        if(html::$render_style==html::RENDER_STYLE_BOOTSTRAP)
            $cls=" class='form-control'";
        
        $attr = html::get_additional_view_data($additional_view_data);
        $v = $f_prop($data);
        echo "<textarea name='$prop_name' id='$prop_name' $attr$cls>$v</textarea>";
    }
    public static function list_for($data,$f_options,$prop_name,$selected_value=null,$additional_view_data=array()) {
        $cls ="";
        if(html::$render_style==html::RENDER_STYLE_BOOTSTRAP)
            $cls=" class='form-control'";
        
        $attr = html::get_additional_view_data($additional_view_data);
        $options = $f_options($data); // array
        echo "<select name='$prop_name' id='$prop_name'$attr$cls>";
        foreach($options as $k => $v) {
            $selected="";
            
            if(!is_array($selected_value))
                $selected = ($k!=null && $k==$selected_value)?" selected='true'":"";
            else
            {
                foreach($selected_value as $val)
                    if($val==$k)
                    {
                        $selected=" selected='true'";
                        break;
                    }
            }
            echo "<option value='$k'$selected>$v</option>";
        }
        echo "</select>";
    }
    public static function checkbox_for($data,$f_prop,$prop_name,$checked=false,$additional_view_data=array()) {
        $cls ="";
        if(html::$render_style==html::RENDER_STYLE_BOOTSTRAP)
            $cls=" class='form-check-input'";
        
        $attr = html::get_additional_view_data($additional_view_data);
        $v = $f_prop($data);
        $chk_att = $checked || $checked!=""?" checked":"";
        echo "<input type='checkbox' name='$prop_name' id='$prop_name' value='$v'$chk_att$attr$cls>";
    }
    public static function radio_for($data,$f_prop,$prop_name,$checked=null,$additional_view_data=array()) {
        $cls ="";
        if(html::$render_style==html::RENDER_STYLE_BOOTSTRAP)
            $cls=" class='form-check-input'";
        
        $attr = html::get_additional_view_data($additional_view_data);
        $v = $f_prop($data);
        $chk_att = $checked==$v?" checked":"";
        echo "<input type='radio' name='$prop_name' id='$prop_name' value='$v'$chk_att$attr$cls>";
    }
    public static function button_for($data,$f_prop,$prop_name,$onclick,$additional_view_data=array()) {
        $cls ="";
        if(html::$render_style==html::RENDER_STYLE_BOOTSTRAP)
            $cls=" class='form-control'";
        
        $attr = html::get_additional_view_data($additional_view_data);
        $v = $f_prop($data);
        echo "<input type='button' name='$prop_name' id='$prop_name' value='$v' onclick='$onclick'$attr$cls>";
    }
    public static function action_button_for($data,$f_prop,$prop_name,$zone,$controller,$action,$params_name=null,$additional_view_data=array()) {
        $cls ="";
        if(html::$render_style==html::RENDER_STYLE_BOOTSTRAP)
            $cls=" class='form-control'";
        
        $attr = html::get_additional_view_data($additional_view_data);
        $v = $f_prop($data);
        echo "<input type='button' name='$prop_name' id='$prop_name' value='$v' ".
            "onclick='this.form.action=\"".
            html::get_action_href($zone, $controller, $action,$params_name) .
            "\";this.form.submit();'$attr$cls>";
    }
    /*
     * Validation
     */
    public static function validation_summary($additional_view_data=array("style"=>"color:red")) {
        $attr = html::get_additional_view_data($additional_view_data);
        echo "<ul$attr>".FPLGlobal::$view_result->get_validation_summary()."</ul>";
    }
    public static function validation_for($property,$validation_msg=null,$additional_view_data=array("style"=>"color:red")) {
        $attr = html::get_additional_view_data($additional_view_data);
        $msg=FPLGlobal::$view_result->get_validation_for($property,$validation_msg);
        
        echo "<span$attr>".$msg."</span>";
    }
}
?>