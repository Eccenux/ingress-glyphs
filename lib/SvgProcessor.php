<?php
	/**
	 * SVG processor partially Glyph specific.
	 *
	 * @author Maciej Nux Jaros
	 */
	class SvgProcessor
	{
		# internal variables
		const mainHtml = 'index.html';
		const dataJS = 'data.js';
		const dataVariable = 'glyphsMapping';
		const mainTplPath = 'tpls/index.php';

		# constructor
		function __construct () {
		}
		
		# methods
		/**
		 * Clean SVG file
		 *
		 * Removes some Inkscape specific stuff that is not need and does some extra transformations.
		 *
		 * @param string $svg SVG string.
		 * @return string Cleaned (pre-proccessed) SVG string.
		 */
		public static function cleanup($svg) {
			// XML header
			$svg = preg_replace('#^<\?xml[^>]+\?>#', '', $svg);
			// dimmensions
			$svg = preg_replace('#\s+height="[^"]+"#', '', $svg, 1);
			$svg = preg_replace('#(\s)width="[^"]+"#', '$1width="100%"', $svg, 1);
			// inkscape namespaces
			$svg = preg_replace(array(
				'#\s+(sodipodi|inkscape):\w+="[^"]+"#',
				'#<(sodipodi|inkscape):\w+\s[^<]+/>#',
				'#\s+xmlns:(sodipodi|inkscape|dc|cc|rdf)="[^"]+"#',
				'#\s*<metadata[\s\S]+?</metadata>#',
			), '', $svg);

			return $svg;
		}

		/**
		 * Read glyph mapping data from SVG.
		 *
		 * @param string $cleanSvg Cleaned (pre-proccessed) SVG string.
		 * @return array Glyph mapping data (groupId => glyphName).
		 */
		public static function readData($cleanSvg) {
			//trigger_error("Not implemented", E_USER_ERROR);
			$data = array(
				'g5645' => 'Mind'
			);
			return $data;
		}

		/**
		 * Save data and embed SVG in HTML.
		 *
		 * @param string $outputBasePath Output path for the glyph website.
		 * @param string $cleanSvg Cleaned (pre-proccessed) SVG string.
		 * @param array $data Glyph mapping data to be put in JS file (as JSON).
		 * @return boolean true if finished without problems.
		 */
		public static function write($outputBasePath, $cleanSvg, $data) {
			$outputBasePath = preg_replace('#/*$#', '/', $outputBasePath);

			// main html (embedded SVG)
			ob_start();
			include dirname(__DIR__) . '/' . self::mainTplPath;
			$html = ob_get_clean();
			file_put_contents($outputBasePath . self::mainHtml, $html);

			// data file (JSON with glyph mapping)
			$json = 'var ' . self::dataVariable . ' = ' . json_encode($data);
			file_put_contents($outputBasePath . self::dataJS, $json);

			return true;
		}
	}

?>