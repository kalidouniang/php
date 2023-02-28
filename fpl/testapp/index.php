<?php 
/*
 * index.php
 * (C) 2019 BAG
 * Template de page Bootstrap
 */
    require_once "../comfpl/main.php"; 
    require_once 'config.php';
?>
<!doctype html>
<html lang="fr">
<head>
<title>Team-up [Accueil]</title>
<?php require_once 'phpinclude/commonmeta.php';?>
<?php require_once 'phpinclude/commoncss.php';?>
<?php require_once 'phpinclude/theme.php';?>
<?php require_once 'phpinclude/commonscript.php';?>
	<title>Test app [Accueil]</title>
<!-- 	<link rel="stylesheet" href="styles/master.css" type="text/css"/> -->
</head>
<body>
	<?php require_once 'phpinclude/navbar.php';?>
	
	<!-- séparateur -->
	<div class="container-fluid">
		<div class="row">
			<div class="col">&nbsp;</div>
		</div>
	</div>
	
	<!-- contenu principal statique -->
	<div class="container-fluid">
		<div class="row">
			<div class="col-2">
				<div class="card">
					<img class="card-img-top" src="<?php 
			         echo FPLGlobal::get_theme_uri()."/images/homeuser.png"?>"/>
					<div class="text-center">Jean Valjean</div>
					<div class="card container">
						<div class="row">
							<div class="col-8"><small>A&nbsp;faire</small></div>
							<div class="col"><small>8</small></div>
						</div>
						
						<div class="row">
							<div class="col-8"><small>En&nbsp;cours</small></div>
							<div class="col"><small>2</small></div>
						</div>
						
						<div class="row">
							<div class="col-8"><small>Posts</small></div>
							<div class="col"><small>10</small></div>
						</div>
						
					</div>
				</div>
			</div>
			
			<div class="col-5">
				<div class="card">
					<table class="table">
						<tr>
							<td><a href="" class="btn btn-primary"><small>Post</small></a></td>
							<td><a href="" class="btn btn-outline-primary"><small>Photo</small></a></td>
							<td><a href="" class="btn btn-outline-primary"><small>Vid&eacute;o</small></a></td>
							<td><a href="" class="btn btn-outline-primary"><small>Doc</small></a></td>
						</tr>
					</table>
				</div>
				
				<div>&nbsp;</div>
				
				<div class="card">
					Request: <?php echo $_SERVER['REQUEST_URI']?>
				</div>
				
				<div class="card">
					Activite et messages
				</div>
			</div>
			
			<div class="col-2">
				<div class="card">
					<div class="card">
					<img class="card-img-top" src="<?php 
			         echo FPLGlobal::get_theme_uri()."/images/trackproject.png"?>"/>
					<div class="text-center">Projets suivis</div>
					<div class="card container">
						<div class="row">
							<div class="col"><div class="card bg-light"><small>Projet 1</small></div></div>
						</div>
						
						<div class="row">
							<div class="col"><div class="card bg-light"><small>Projet 2</small></div></div>
						</div>						
					</div>
				</div>
				</div>
			</div>
			
		</div>
	</div>
</body>
</html>