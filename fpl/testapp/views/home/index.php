<?php $model=FPLGLobal::$view_result->model; html::$render_style=html::RENDER_STYLE_BOOTSTRAP; ?><html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Vue Index</title>
	<?php FPLGlobal::render_bundle_css()?>
	<?php FPLGlobal::render_bundle_script()?>
</head>
<body>
	<?php require_once 'phpinclude/navbar.php';?>
	<div class="container-fluid">
	<div class="row">
    	<div class="col">
        	<h3>Accueil</h3>
        	<div>
        		<?php html::label_for($model, function ($model) { return $model->title;})?>
        		<?php html::action_link('home','home', 'index2', "Index 2") ?>
        	</div>
        	</div>
	</div>
	<?php html::begin_form('home','home', 'index','post',array("enctype"=>"multipart/form-data")) ?>
	<div class="row">
		<div class="col-6">
    		<div class="form-group">
        		<?php html::label_for(null,function($model) { return "Nom:"; },null,"nom")?>
        		<?php html::edit_for($model, function ($model) { return $model->nom;}, "nom",array("style"=>"background-color:#E0E0E0"))?>
        	</div>
        	<div class="form-group">
        		Ville:
        		<?php html::list_for($model, function ($model) { return $model->villes; }, "ville", $model->ville,array("size"=>3))?>
        	</div>
        	<div class="form-group">Age:
        		<?php html::edit_for($model, function ($model) { return $model->age;}, "age")?>
        		<?php html::validation_for("age","<ul><li>&nbsp;</li></ul>")?>
        		</div>
		</div>
		<div class="col-6">
			<div class="form-group">
        		<?php html::label_for(null,function($model) { return "Demande:";},null,"demande",array("class"=>"form-control"))?>
        		<?php html::textedit_for($model, function ($model) { return $model->demande;}, "demande",array("rows"=>5,"cols"=>30))?>
        	</div>
			<div class="form-group">
        		<?php html::label_for(null,function($model) { return "Fichier:";},null,"resume",array("class"=>"form-control"))?>
        		<?php html::fileupload_for($model, function ($model) { return $model->resume;}, "resume")?>
        	</div>
        	<div class="form-check">
        		<?php html::radio_for($model, function($model) { return 'email'; }, "contact",$model->contact)?>
        		<?php html::label_for(null,function($model) { return "Contact par email";},null,"contact",array("class"=>"form-check-label"))?>
        	</div>
        	<div class="form-check">
        		<?php html::radio_for($model, function($model) { return 'mobile'; }, "contact",$model->contact)?>
        		<?php html::label_for(null,function($model) { return "Contact par mobile";},null,"contact",array("class"=>"form-check-label"))?>
        		
        	</div>
        	<div class="form-check">
        		<?php html::checkbox_for($model, function($model) { return 'true'; }, "d_accord",$model->d_accord)?>
        		<?php html::label_for(null,function($model) { return "D'accord pour changer";},null,"d_accord",array("class"=>"form-check-label"))?>
        	</div>
		</div>
	</div>
	<div class="row">
		<div class="col">
	    	<div><?php html::validation_summary()?></div>	
        	<?php html::hidden_for($model, function ($model) { return $model->idc;}, "idc")?>
        	<input type="submit" value="Envoyer" name="index2">
        	<?php html::action_button_for($model, function($model) { return "Envoyer action";}, "btn", "Home", "Home", "Index")?>
		</div>
	</div>
	
	<?php html::end_form()?>
	</div>
</body>
</html>