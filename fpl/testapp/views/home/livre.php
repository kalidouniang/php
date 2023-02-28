<?php //{layout:../_Layout.php}?><?php $model=FPLGLobal::$view_result->model; ?>
<h2>Livre</h2>
<div>ID <?php html::label_for($model, function($model){return $model->id;})?></div>
<div>Categorie <?php html::label_for($model, function($model){return $model->categorie;})?></div>
<div>&nbsp;</div>
<div>Message <?php html::label(FPLGlobal::$view_bag->message)?></div>
<div>Notice <?php html::label(FPLGlobal::$view_data["notice"])?></div>
