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
				'#\s+(sodipodi|inkscape):[\w\-]+="[^"]+"#',
				'#<(sodipodi|inkscape):[\w\-]+\s[^<]+/>#',
				'#\s+xmlns:(sodipodi|inkscape|dc|cc|rdf)="[^"]+"#',
				'#\s*<metadata[\s\S]+?</metadata>#',
			), '', $svg);

			// dangerous in general (glyph SVG specific)
			$svg = preg_replace(array(
				// for tspan we should actual check if a tspan is the only element in text, but we don't care here
				'#</?tspan[^>]*>#',
				// element not needed for static HTML
				'#<g[^>]+id="edit-info"[\s\S]+?</g>#',
			), '', $svg);
			// remove text styling (common style will be in CSS)
			$svg = preg_replace('#(text[^>]+?)\sstyle="[^>"]+"#', '$1', $svg);
			// remove useless attributes from glyph-plate usage
			$svg = preg_replace('@(use)[^>]+(xlink:href="#glyph-plate")[^/>]+@', '$1 $2 ', $svg);

			return $svg;
		}

		/**
		 * Read glyph mapping data from SVG.
		 *
		 * @param string $cleanSvg Cleaned (pre-proccessed) SVG string.
		 * @return array Glyph mapping data (groupId => glyphName).
		 */
		public static function readData($cleanSvg) {
			$glyphData = array();

			$doc = new DOMDocument();
			$doc->loadXML($cleanSvg);
			$elements = $doc->getElementsByTagName('text');

			if (!is_null($elements)) {
				foreach ($elements as $element) {
					$glyphId = $element->parentNode->getAttribute('id');
					$glyphName = $element->nodeValue;
					$glyphData[$glyphId] = $glyphName;
				}
			}
			return $glyphData;
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