/**
 * MCE Button Script
 */
(function($) {
	'use strict';
	/* Setup responsive panel size */
	var jwdTeamScreen = $( window ).width();
	var jwdTeamPanelSize = 310;
	if( jwdTeamScreen > 719 ){ jwdTeamPanelSize = 480; }
	/* Create the MCE plugin */
    tinymce.create('tinymce.plugins.JWDTeams', {
        init : function(ed, url) {
            ed.addButton('addjwdteam', {
                title : 'JWD Teams',
				icon: 	'jwdtm-icon dashicons-id-alt',
				onclick: function() {
					/* Get the registered teams before anything else */
					var mceData = { 'action': 'mceGetTeams', 'mceNonce': jt__getTeams.mceNonce };
					/* AJAX call for teams list */
					$.post( jt__getTeams.ajax_url, mceData, function(jwdTeams) {
						var jwdTeamItems = [];
						var jwdTeamsCollection = new tinymce.ui.Collection().set(jwdTeams);
						jwdTeamsCollection.each(function(item){
							jwdTeamItems.push({ text: item.text, value: item.value });  
						});
						/* Setup panel body */
						if(jwdTeamItems != ''){
							var jwdTeamsBody = [{ type : 'listbox', name : 'listbox', label : jt__getTeams.mceLabel, values : jwdTeamItems }];
						} else {
							var jwdTeamsBody = [{ type: 'container', html : '<p style="text-align:center;">'+jt__getTeams.mceEmptyMsg+'</p>' }];
						}
						/* Display the Teams shortcode generator */
						ed.windowManager.open({
							title: 		jt__getTeams.mceTitle,
							minWidth: 	jwdTeamPanelSize,
							classes: 	'JWDTeams-panel',
							body: 		jwdTeamsBody,
							onsubmit: 	function(e) { 
								if( jwdTeamItems != '' ){ ed.insertContent('[jwd_team id="' + e.data.listbox + '"]'); } 
							}
						});
					}); 
				}
            });
        },
    });
    /* And finally register the plugin */
    tinymce.PluginManager.add('jwdteams', tinymce.plugins.JWDTeams);
})( jQuery );
/*END*/