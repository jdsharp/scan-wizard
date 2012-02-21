<?php

function sw_pdf_page_count($file)
{
	$cmd  = SW_PDFTK_BIN . ' ' . $file . ' dump_data';
	$data = shell_exec( $cmd );
	if ( $data ) {
		if ( preg_match('/NumberOfPages:\s*(\d+)/', $data, $matched ) ) {
			return (int)$matched[1];
		}
	}
	
	return 0;
}

function sw_pdf_page($file, $page, $output)
{
	//echo "FILE: $file($page) OUTPUT: $output\n";
	
	//pdftk foo.pdf cat 2-3 output abstract.pdf dont_ask
	$cmd  = SW_PDFTK_BIN . ' ' . $file . ' cat ' . $page . ' output ' . $output . ' dont_ask';
	$data = shell_exec( $cmd );
	var_dump($data);
	if ( $data ) {
		return true;
	}
	return false;
}

function sw_get_pdf_thumbnail($file, $page = 1)
{
	sw_get_pdf_image($file, $page, 72, 150);
}

function sw_get_pdf_image($file, $page, $resolution = 150, $size = 800)
{
	$md5      = md5($file);
	$base     = SW_TEMP . DIRECTORY_SEPARATOR . $md5 . '_' . $page;
	$cachePdf = $base . '.pdf';
	$cacheImg = $base . '-' . $resolution . '-' . $size . '.jpg';
	if ( !file_exists( $cachePdf ) ) {
		// Cache miss
		echo "MISS\n";
		if ( !sw_pdf_page($file, $page, $cachePdf) ) {
			return false;
		}
	}
	
	if ( !file_exists( $cacheImg ) ) {
		echo "CONVERT\n";
		$cmd = SW_CONVERT_BIN . ' -density ' . $resolution . ' ' . $cachePdf . ' ' . $cacheImg;
		echo $cmd . "\n";
		$data = shell_exec( $cmd );
		if ( $data ) {
			// Resize the image now if it's too big
			list($width, $height) = getImageSize($cacheImg);
			return file_get_contents($cacheImg);
		}
	}
	return false;
}

function sw_list_inbox()
{
	$dh = dir( SW_INBOX );
	$files = array();
	while ( ($entry = $dh->read()) !== false ) {
		if ( substr( strtolower($entry), -4 ) == '.pdf' ) {
			$files[$entry] = array( sw_pdf_page_count( SW_INBOX . DIRECTORY_SEPARATOR . $entry ), $entry);
		}
	}
	$dh->close();
	return $files;
}

function sw_list_outbox()
{
	
}