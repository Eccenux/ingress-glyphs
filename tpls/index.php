<?php
	// setup build time
	$buildFiles = array(
		'./website/jquery-ui/jquery-ui.css',
		'./website/jquery-ui/jquery.js',
		'./website/data.js',
	);
	$buildTime = 0;
	foreach($buildFiles as $filename) {
		$changeTime = filemtime($filename);
		if ($buildTime < $changeTime) {
			$buildTime = $changeTime;
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ingress Glyphs - the hacking language</title>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

	<meta name="author" content="Maciej Jaros">

	<link rel="stylesheet" href="jquery-ui/jquery-ui.css?<?=$buildTime?>">

	<style type="text/css">
	svg text {
		font-size:40.33519745px;
		text-align:center;
		text-anchor:middle;
		fill:#1accd9;fill-opacity:1;
		stroke:none;
		font-family:sans-serif;
	}

	input[type=text],
	body {
		background: black;
		color: #ddd;
		font:12pt sans-serif;
	}

	input[type=text] {
		border-color: #1accd9;
		padding: .5em;
	}
	#search-box {
		position: fixed;
		top:0; left: 0;
		padding: .5em;
	}
	#info-box {
		text-align: right;
	}
	</style>
</head>
<body lang="en">

<div id="search-box">
	<label for="search-text">Glyph</label>
	<input type="text" id="search-text">
</div>
<div id="info-box">
	<input type="button" value="Feedback &amp; Info" id="feedback-button">
</div>
<div id="feedback-dialog" title="Feedback and informations about this page">
	<p>This page should allow you to learn Ingress portal hacking language faster. And you should know your Ingress ABC to make hacking really count ;-).</p>
	<p style="text-align: center;"><img src="media/Hack-lose-shapers-message-gain-chaos_cut.png" alt="Lose. Shapers. Message. Gain. Chaos."></p>
	<p>There is an on-going effort to put all glyphs in here (to be more exacrt - all glyphs that you could find in hacking riddles). If you find something missing then please do not hasitate to either <a href="https://github.com/Eccenux/ingress-glyphs/issues" target="_blank">file a bug report</a>, or <a href="mailto:eccenu&#120;&#43;&#105;&#110;&#103;&#114;&#101;&#115;&#115;&#103;&#108;&#121;&#112;&#104;&#64;&#103;&#109;ail.com?subject=Ingress Glyph request">contact me via e-mail</a>.</p>
	<p>I'm also open to suggestions on improving this site so you can also <a href="https://github.com/Eccenux/ingress-glyphs/issues" target="_blank">request a feature</a> in the same place you post bugs.</p>
</div>

<!-- SVG -->
<?=$cleanSvg?>

<!-- scripts -->
<script src="jquery-ui/jquery.js?<?=$buildTime?>"></script>
<script src="jquery-ui/jquery-ui.js?<?=$buildTime?>"></script>

<script src="data.js?<?=$buildTime?>"></script>
<script>
// autocomplete
(function($) {
	var glyphTexts = [];
	var glyphIds = [];
	for (var glyphId in glyphsMapping) {
		glyphIds.push(glyphId);
		glyphTexts.push(glyphsMapping[glyphId]);
	}
	$( "#search-text" ).autocomplete({
		source: glyphTexts,
		select: function( event, ui ) {
			var i = $.inArray(ui.item.value, glyphTexts);
			var glyphId = glyphIds[i];

			// weird doesn't work on Opera 12...
			//location.href = '#' + glyphId;
			history.pushState({}, glyphId, "#" + glyphId);

			// nice animation effect (which happen to work on Opera 12 too)
			$('html, body').animate({
				scrollTop: $("#" + glyphId).offset().top
			}, 200);
		}
	});
})(jQuery);

// jUI enhance
$("input[type=submit], input[type=button], button").button();

// feedback
$('#feedback-dialog').dialog({
	autoOpen: false,
	width: 500,
	open : function () {
		// resize height to fit in window
		var windowHeight = $(window).height();
		var $this = $(this);
		var widgetHeight = $this.parent().height();
		if (widgetHeight > windowHeight) {
			var contentHeight = $this.height();
			$this.height((windowHeight - (widgetHeight - contentHeight)) * 0.9);
			$this.dialog({ my: "center", at: "center", of: window });
			$('html, body').scrollTop($this.parent().offset().top);
		}
	}
});
$('#feedback-button').click(function (){
	$('#feedback-dialog').dialog('open');
});
</script>

</body>
</html>