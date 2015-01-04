<!DOCTYPE html>
<html>
<head>
    <title>Ingress Glyphs - the hacking language</title>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

	<meta name="author" content="Maciej Jaros">

	<link rel="stylesheet" href="jquery-ui/jquery-ui.css">

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
		padding: 1em;
	}
	</style>
</head>
<body lang="en">

<div id="search-box">
	<label for="search-text">Glyph</label>
	<input type="text" id="search-text">
</div>

<!-- SVG -->
<?=$cleanSvg?>

<!-- scripts -->
<script src="jquery-ui/jquery.js"></script>
<script src="jquery-ui/jquery-ui.js"></script>

<script src="data.js"></script>
<script>
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
</script>

</body>
</html>