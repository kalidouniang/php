<?php
/*
 * trace/index.php
 */
require_once '../main.php';
require_once 'config.php';
session_start();
TraceListener::set_http_session_id();
if(isset($_POST['clear_trace']))
{
    TraceListener::clear_trace();
}

?>
<html>
	<head>
		<title>Traces</title>
		<?php FPLGlobal::render_bundle_css() ?>
		<?php FPLGlobal::render_bundle_script() ?>
	</head>
	<body>
<form method='post' action='index.php'>
<div class="container">
	<div class="row">
		<div class="col">
			<h4>Traces</h4>
			<input type='submit' value='Actualiser' name='refresh_trace' class='btn'>
			<input type='submit' value='Effacer' name='clear_trace' class='btn'>
		</div>
	</div>
	<?php echo TraceListener::get_all_trace_to_html() ?>
</div>
</form>
	</body>
</html>