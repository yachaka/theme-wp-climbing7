function watsonThemePreviewPrimaryColor(newColor) {
	if ( newColor.indexOf('#') != 0 ) {
		newColor = '#' + newColor;
	}
	var colorCSS = watsonFontColorSelectors + ' { color: ' + newColor + '; } ';

	jQuery('#watson-color-styles').text(colorCSS);
}