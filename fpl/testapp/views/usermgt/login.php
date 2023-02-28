<?php $model=FPLGLobal::$view_result->model; html::$render_style=html::RENDER_STYLE_BOOTSTRAP; ?><html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Team-up Connexion</title>
	<?php FPLGlobal::render_bundle_css()?>
	<?php FPLGlobal::render_bundle_script()?>
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col"><h3>Team-up</h3></div>
		</div>
		<?php html::begin_form('usermgt','user', 'login','post') ?>
		<div class="row">
			<div class="col">
    		<div class="form-group">
        		<?php html::label_for(null,function($model) { return "Identifiant:"; },null,"login")?>
        		<?php html::edit_for($model, function ($model) { return $model->login;}, "login")?>
        	</div>
        	<div class="form-group">
        		<?php html::label_for(null,function($model) { return "Mot de passe:"; },null,"pwd")?>
        		<?php html::password_for($model, function ($model) { return $model->pwd;}, "pwd")?>
        	</div>
        	<div class="row">
				<div class="col">
	  			  	<div><?php html::validation_summary()?></div>	
		        	<?php html::action_button_for($model, function($model) { return "Connexion";}, 
                    "login_action", "usermgt", "user", "Login")?>
                </div>
            </div>
            
            <div class="row">
				<div class="col">
					<div class="">
	  			  	<?php echo isset(\FPLGlobal::$view_bag->message)?\FPLGlobal::$view_bag->message:""?>
	  			  	</div>
                </div>
            </div>
		</div>
		</div>
		<?php html::end_form()?>
	</div>
	
</body>
</html>