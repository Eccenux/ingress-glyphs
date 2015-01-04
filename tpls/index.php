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
		font-family:Sans;
	}
	</style>
</head>
<body lang="en">

	<div style="position: fixed; top:0; left: 0;">
		<label for="search-text">Glyph</label>
		<input type="text" id="search-text">
	</div>

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
		location.href = '#' + glyphIds[i];
	}
});
</script>

</body>
</html>