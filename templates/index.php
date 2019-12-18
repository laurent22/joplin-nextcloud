<?php

if (isset($_['cssFiles'])) {
	foreach ($_['cssFiles'] as $cssFile) {
		style('joplin', $cssFile['filenameNoExt']);
	}
}

if (isset($_['jsFiles'])) {
	foreach ($_['jsFiles'] as $jsFile) {
		script('joplin', $jsFile['filenameNoExt']);
	}
}

?>

<?php echo $_['pageHtml']; ?>