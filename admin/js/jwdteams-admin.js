/**
 * Admin Area Scripts
 */
(function( $ ) {
	$(document).ready(function(){ 
	/** 
	 * Uploading team member image  
	 */
		$('body').on('click', '.jt__changeimg', function( event ){
			event.preventDefault();
			var memberImage;
			var jt__container = $(this).attr('data-jtcontainer');
			/* If the media frame already exists, reopen it. */
			if ( memberImage ) { memberImage.open(); return; }
			/* Create the media frame. */
			memberImage = wp.media.frames.memberImage = wp.media({
				title: jt__getTeams.imgFrameTitle,
				button: { text: jt__getTeams.imgBtnText},
				library: { type: 'image' },
				multiple: false
			});
			/* When an image is selected, run a callback. */
			memberImage.on( 'select', function() {
				memberImage.setState('cropper');
				/* We set multiple to false so only get one image from the uploader */
				attachment = memberImage.state().get('selection').first().toJSON();
				/* Do something with attachment.id and/or attachment.url here */
				$('#'+jt__container).find('img').attr('src', attachment.url);
				$('#'+jt__container).find('.jt__teams-attachment').val(attachment.url);
				$('#'+jt__container).find('.jt__teams-attachmentid').val(attachment.id);
			});
			/* Finally, open the modal */
			memberImage.open();
		});
	/** 
	 * Sortable Members 
	 */
		$('.jt__wrapper').sortable({ placeholder: "jt__teams-highlight" });
		/* Remove Member */
		$('body').on('click', '.jt__remove', function(e) {
			e.preventDefault();
			var is_ok  = confirm(jt__getTeams.confirmMsg);
			if (is_ok){ $(this).parent().parent().remove(); }
		});
		/* Remove Member image */
		$('body').on('click', '.jt__removeimg', function(e) {
			e.preventDefault();
			var avatar = $(this).parent();
			var fakeimg = $(this).attr('data-avatar');
			var is_ok  = confirm(jt__getTeams.confirmMsg);
			if (is_ok){ 
				avatar.find('img').attr('src', fakeimg);
				avatar.find('.jt__teams-attachment, .jt__teams-attachmentid').val('');
			}
		});
	/** 
	 * Format & Grid Selectors 
	 */
		/* Default selected grid */
		var jt_selected_grid = 'list_two_per_row'; 
		/* Update Selected Grid */
		$('.team_settings_grid input[type=radio]').each(function () {
			 if($(this).is(':checked')){
				jt_selected_grid = $(this).attr('id'); 
			}  
		});
		/* Grid options for Vertical Format  */
		$(".team_design_v").on('click', function() {
			$(".list_four_per_row, .list_five_per_row").css('display','inline-block');
			$(".team_settings_grid input:checkbox") .removeAttr('checked');
			$(".team_settings_align input:checkbox") .removeAttr('checked');
			$(".list_three_per_row").removeAttr( 'style' );
			$("#list_three_per_row").attr('checked', 'checked');
			$("#team_txt_align_center").attr('checked', 'checked');
		});
		/* Grid options for Horizontal Format  */
		$(".team_design_h").on('click', function() {
			$(".list_four_per_row, .list_five_per_row").css('display','none');
			$(".list_three_per_row").css('border-radius' , '0 4px 4px 0');
			$(".team_settings_grid input:checkbox") .removeAttr('checked');
			$(".team_settings_align input:checkbox") .removeAttr('checked');
			$("#team_txt_align_left").attr('checked', 'checked');
			if(jt_selected_grid == 'list_four_per_row' || jt_selected_grid == 'list_five_per_row'){
				$("#list_two_per_row").attr('checked', 'checked');
			} else {
				$("#"+jt_selected_grid).attr('checked', 'checked');
			}
		});
		/* Tabs Selector*/
		$(".jt__tabs li").on('click', function() {
			var thisTab = $(this);
			if (!thisTab.hasClass('active')) {
				var theCont = thisTab.parent().parent(); 
				var tabNum = thisTab.index();
				var nthChild = tabNum+1;
				theCont.find('.jt__tabs li.active').removeClass('active');
				thisTab.addClass('active');
				theCont.find('.jt__tab li.active').removeClass('active');
				theCont.find('.jt__tab li:nth-child('+nthChild+')').addClass('active');
			}
		});
		/* Color Piker */
		$('.jt__color_picker').wpColorPicker();
		/* UI Slider */
		$.fn.teamSlider = function($unit, $default) {
			/* Quit if no unit value provided. */
			var unit = $unit;
			if(!unit){ alert('Parameter $unit is missing for teamSlider().'); return; }
			/* Vars */
			var inputVal 	= '';
			var defVal 		= $default;
			var jtSlider 	= $(this);
			var handle 		= jtSlider.find( ".jt__slider-handle" );
			var input 		= jtSlider.parent().find( ".jt__slider-input" );
			if(defVal){ inputVal = defVal; } else { inputVal = input.val(); }
			/* Build the slider. */
			switch(unit){
				case 'ch':
				jtSlider.slider({
					min: 50, max: 500, step: 50, value: inputVal,
					create: function() { handle.text( inputVal ); },
					slide: function( event, ui ) { handle.text( ui.value); input.val( ui.value ); }
				});
				break;
				default:
				jtSlider.slider({
					range: "min", min: 8, max: 50, value: inputVal,
					create: function() { handle.text( inputVal ); },
					slide: function( event, ui ) { handle.text( ui.value); input.val( ui.value ); }
				});
			}
		}
		/* Apply Slider function for each settings slider */
		$('.jt__slider-bg').each(function(){
			var unit = $(this).attr('data-jt_unit');
			$(this).teamSlider(unit);
		});
		/* Reset Team Settings to default*/
		$(".jt__resetTeamSettings").on('click', function(e) {
			e.preventDefault();
			var settings = $('#jwdteams_settings .jt__tab');
			var spinner = $('.resetTeamSettings_spinner');
			var data = { 'action': 'reset_team_settings', 'reset_nonce': jt__getTeams.reset_nonce };
			/* Avoid accidents */
			var is_ok  = confirm(jt__getTeams.confirmReset);
			if (!is_ok){ return; }
			/* Reset Settings */
			spinner.addClass('is-active');
			$.post( jt__getTeams.ajax_url, data, function(response) {
				$.each( response, function( key, value ) {
					var changeThis = settings.find('#'+key);
					switch(value['type']){
						case 'radio':
							changeThis.find('input:radio').each( function () { $(this).prop('checked', false); });
							changeThis.find('input:radio[value='+value['def']+']').prop('checked', true);
							/* Grid specific setup */
							$(".list_three_per_row").removeAttr( 'style' );
							$(".list_four_per_row, .list_five_per_row").css('display','inline-block');
						break;
						case 'checkbox':
							if(value['def']){ 
								changeThis.prop('checked', true); 
							} else { 
								changeThis.prop('checked', false); 
							}
						break;
						case 'text':
							changeThis.val(value['def']);
						break;
						case 'colorpicker':
							var cp = changeThis.parent().parent();
							if(value['def']){
								cp.find('.wp-color-result').css('background-color', value['def']);
							} else {
								cp.find('.wp-color-result').removeAttr('style');
							}
							changeThis.val(value['def']);
						break;
						case 'slider':
							var theSlider = changeThis.parent().find('.jt__slider-bg');
							var theHandle = changeThis.parent().find('.jt__slider-handle');
							theSlider.teamSlider( value['unit'], value['def'] );
							theHandle.text(value['def']);
							changeThis.val(value['def']);
						break;
					}
				});
				spinner.removeClass('is-active');
				$('#jwdteams_settings .inside').append('<div class="jt__alert jt__alert-success" role="alert">'+jt__getTeams.afterReset+'</div>');
			});
		});
		/* Remove the Alerts if settings inputs are clicked */
		$('.jt__tab, .jt__tab .jt__slider-handle, .jt__tab .wp-picker-container').on('click', function(){
			$('.jt__alert').remove();
		});
	});	
})( jQuery );