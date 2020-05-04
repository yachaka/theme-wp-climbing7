/**
 * WordPress plugin.
 */

(function() {

	// Language packs don't seem to work in older versions of WP!
	//tinymce.PluginManager.requireLangPack('ttfsyntax');

	tinymce.create('tinymce.plugins.WatsonThemePlugin', {
		mceTout : 0,

		init : function(ed, url) {

			var buttons =
				{ "alert": { commandName: "alert", title: 'Alert message box' },
				  "error": { commandName: "error", title: 'Error message box' },
				  "success": { commandName: "success", title: 'Success message box' },
				  "note": { commandName: "note", title: 'Note message box' },
				  "footnote": { commandName: "footnote", title: 'Footnote' },
				  "sidenote": { commandName: "sidenote", title: 'Sidenote' },
				  "run in": { commandName: "run_in", title: 'Run in' },
				  "excerpt": { commandName: "excerpt", title: 'Excerpt' },
				  "end": { commandName: "end", title: 'End' } };

			// Register custom commands
			for (var key in buttons) {
				var commandName = buttons[key].commandName;
				// Tricky JS callback wrapping to create a new scope
				(function(currentCommand) {
					ed.addCommand(currentCommand, function() {
						ed.focus();
						ed.formatter.toggle(currentCommand);
					});
				})(commandName);
			}

			// Register custom buttons
			for (var key in buttons) {
				var commandName = buttons[key].commandName;
				ed.addButton(commandName, {
					title : buttons[key].title,
					cmd : commandName,
					label : key,
					text : key,
					'class' : 'ttf-class'
				});
			}

			ed.onInit.add(function(ed) {
				// Hide the 3rd row of buttons if the advanced editor is off
				if ( getUserSetting('hidetb', '0') == '0' ) {
					jQuery( '#' + ed.id + '_toolbar3' ).hide();
				}

				// On click, toggle the 3rd row of buttons with the rest of the advanced editor
				jQuery( '#' + ed.id + '_wp_adv').click(function() {
					if ( jQuery( '#' + ed.id + '_toolbar2' ).is( ':visible' ) ) {
						jQuery( '#' + ed.id + '_toolbar3' ).show();
					} else {
						jQuery( '#' + ed.id + '_toolbar3' ).hide();
					}
				});

				// Register formatting options for easy toggling
				ed.formatter.register('alert', {block : 'div', wrapper : 1, remove : 'all', classes : 'alert'});
				ed.formatter.register('error', {block : 'div', wrapper : 1, remove : 'all', classes : 'alert error'});
				ed.formatter.register('success', {block : 'div', wrapper : 1, remove : 'all', classes : 'alert success'});
				ed.formatter.register('note', {block : 'div', wrapper : 1, remove : 'all', classes : 'alert note'});
				ed.formatter.register('footnote', {block : 'p', remove : 'all', classes : 'footnote'});
				ed.formatter.register('sidenote', {block : 'p', remove : 'all', classes : 'footnote sidenote'});
				ed.formatter.register('run_in', {inline : 'span', classes : 'run-in'});
				ed.formatter.register('excerpt', {block : 'p', remove : 'all', classes : 'excerpt'});
				ed.formatter.register('end', {block : 'p', remove : 'all', classes : 'end'});
			});

			// Here, all of our custom formatting buttons are toggled
			// between active/inactive based on the current selection
			ed.onNodeChange.add(function(ed, cm, n, co) {
				cm.setActive('alert', jQuery(n).parents('.alert').length);
				cm.setActive('error', jQuery(n).parents('.alert.error').length);
				cm.setActive('success', jQuery(n).parents('.alert.success').length);
				cm.setActive('note', jQuery(n).parents('.alert.note').length);
				cm.setActive('footnote', jQuery(n).parents('.footnote').length);
				cm.setActive('sidenote', jQuery(n).parents('.footnote.sidenote').length);
				cm.setActive('run_in', ed.formatter.match( 'run_in' ));
				cm.setActive('excerpt', jQuery(n).parents( 'p.excerpt' ).length);
				cm.setActive('end', jQuery(n).parents( 'p.end' ).length);
			});
		},

		createControl : function(n, cm) {
			return null;
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
				return {
						longname : 'The Theme Foundry Watson Theme plugin',
						author : 'The Theme Foundry',
						authorurl : 'http://thethemefoundry.com',
						version : "1.0"
				};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('watson', tinymce.plugins.WatsonThemePlugin);
})();