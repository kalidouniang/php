<?php $model=FPLGLobal::$view_result->model; ?><html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title><?php echo $model->title; ?></title>
	
    <?php FPLGlobal::render_bundle_css()?> 
	<?php FPLGlobal::render_bundle_script()?>
</head>
<body>
	<h1>Site d&eacute;mo</h1>
	<div><?php html::action_link('home','home', 'index', "index");?></div>
	<div><?php html::action_link('home','home', 'index2', "index2");?></div>
	<div><?php html::action_link('home','home', 'livre', "livre",array("id"=>100,"categorie"=>"abc"));?></div>
	<div><?php FPLGlobal::render_view(); ?></div>
</body>
</html>