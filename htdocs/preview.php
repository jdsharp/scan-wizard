<?php
require_once( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'bootstrap.php' );

header('Content-type: text/plain');

$uri = $_SERVER['REQUEST_URI'];
if ( preg_match('#\.php/([^:]+)(?::(\d+))?$#', $uri, $matched ) ) {
	$file = $matched[1];
	$page = $matched[2];
	
	// TODO: Sanitize the path
	$file = str_replace('..', '', $file);
	$file = SW_ROOT . DIRECTORY_SEPARATOR . $file;
	
	$data = sw_get_pdf_thumbnail($file, $page);
	echo "LENGTH: " . strlen($data);
}