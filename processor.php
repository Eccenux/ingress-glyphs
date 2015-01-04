<?php
	// setup
	$outputBasePath = './website/';
	$svg = file_get_contents('svg/glyphs.svg');
	date_default_timezone_set('Europe/Warsaw');
	
	// lib setup
	require_once('./lib/SvgProcessor.php');
	$processor = new SvgProcessor();
	
	// process
	$cleanSvg = SvgProcessor::cleanup($svg);
	$data = SvgProcessor::readData($cleanSvg);
	SvgProcessor::write($outputBasePath, $cleanSvg, $data);
?>