<?php
/*
 * config.php
 * bundle bootstrap
 */
$bootstrap = new bundle();
$bootstrap->css_set=array(
    new cssItemBundle("https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"),
    new cssItemBundle("http://cdn.phpoll.com/css/animate.css"),
    new cssItemBundle("https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css"),
    new cssItemBundle("https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"),
    new cssItemBundle("summernote/summernote-lite.css")
    
);
$bootstrap->script_set=array(
    new scriptItemBundle("https://code.jquery.com/jquery-3.3.1.min.js"),
    new scriptItemBundle("https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js"),
    new scriptItemBundle("https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js",
        "sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1",
        "anonymous"),
    new scriptItemBundle("https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js",
        "sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM",
        "anonymous"),
    new scriptItemBundle("https://unpkg.com/gijgo@1.9.13/js/messages/messages.fr-fr.js"),
    new scriptItemBundle("summernote/summernote.js"),
    new scriptItemBundle("summernote/lang/summernote-fr-FR.js"),
    
);
FPLGlobal::add_bundle("bootstrap", $bootstrap);

/*
 * FileTraceWriter
 */
function get_filetracewriter_path() {
    return "c:/TEMP/";
}

/*
 * trace
 */
function get_trace_config() {
    return "FileTraceWriter";
}
?>