<?php //{layout:../_Layout.php}?><?php $model=FPLGLobal::$view_result->model; ?>
<?php FPLGlobal::render_patial_view("home", "shared/header")?>
<h2>Vue Index 2</h2>
<div>Donnees recues: <?php echo print_r($model);?></div>
<?php FPLGlobal::render_action("home", "home", "partialheader",array("id"=>123))?>

<div class="container-fluid">
	<div>Liste de livres</div>
	<div class="row" style="margin-top: 10px">
		<div class="col-12">
			<table id="grid"></table>
		</div>
	</div>
</div>
<script>
	<?php //FPLGlobal::render_action("home", "home", "dire_js_bonjour")?>
	var data='<?php FPLGlobal::render_action("home", "home", "get_livre_data")?>';
	var grid;

	$(document).ready(function () {
         grid = $('#grid').grid({
             primaryKey: 'id',
             dataSource: '<?php html::action_href("home" , "home", "get_livre_data")?>',
             uiLibrary: 'bootstrap4',
             columns: [
                 { field: 'id', width: 50, title:'ID' },
                 { field: 'titre',title:'Titre' },
             ]
             ,
             pager: { limit: 5, sizes: [2, 5, 10, 20] }
         });
         
     });
	
</script>


<div>
	<div>Actions rapides</div>
<?php echo FPLGlobal::partial_view("home", "shared/header")?>
<?php html::action_link('home','home', 'index', "index");?>
</div>
