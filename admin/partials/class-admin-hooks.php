<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link    		http://jordachewd.com
 * @since     		1.3.5
 *
 * @package    		JWD_Teams
 * @subpackage 		JWD_Teams/admin/partials
 */
class JWD_Teams_Admin_Hooks {
	/**
	 * The Full Name of this plugin.
	 *
	 * @since    1.3.5
	 * @access   protected
	 * @var      string    $fullname    The Full Name of this plugin.
	 */
	private $fullname;
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.3.5
	 * @access   private
	 * @var      string    $textdomain    The ID of this plugin.
	 */
	private $textdomain;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.3.5
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	/**
	 * The general hooks of this plugin.
	 *
	 * @since    1.3.5
	 * @access   protected
	 * @var      string    $hooks    	Retrive the general hooks of the plugin
	 */
	private $hooks;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    	1.3.5
	 * @param      	string    $fullname       	The Full Name of this plugin.
	 * @param      	string    $textdomain       The name of the plugin.
	 * @param      	string    $version    		The version of this plugin.
	 */
	public function __construct($fullname, $textdomain, $version, $hooks ) {
		$this->fullname = $fullname;
		$this->textdomain = $textdomain;
		$this->version = $version;
		$this->hooks = $hooks;
	}
	/**
	 * Return Member Fields
	 *
	 * @since    1.3.5
	 */
	public function get_member_fileds($count, $member = null) {
		ob_start();
		/* Define Vars */
		$html = '';
		$hooks = $this->hooks;
		$fields = array( 'img' => 'url', 'img_id' => 'attr', 'name' => 'attr', 'position' => 'attr', 'phone' => 'attr', 'email' => 'attr', 'desc' => 'textarea', 'fb' => 'url', 'tw' => 'url', 'in' => 'url', 'gp' => 'url', 'yt' => 'url', 'it' => 'url' );
		$default_img = plugins_url('/img/jwd_team_avatar.gif', dirname(__FILE__));
		/* Define Field Vars*/
		foreach($fields as $field => $val){
			${ 'mb_'.$field } = 'member_data['.$count.']['.$field.']';
			if(isset($member) && !empty($member)){ 
				switch($val){
					case 'url': if( !empty($member[$field])){ ${$field} = esc_url( $member[$field]); } else { ${$field} = ''; }; break;	
					case 'textarea': if( !empty($member[$field])){ ${$field} = esc_textarea( $member[$field]); } else { ${$field} = ''; }; break;	
					default: if( !empty($member[$field])){ ${$field} = esc_attr( $member[$field]); } else { ${$field} = ''; };
				}
			} else { ${$field} = ''; }
		}
		/* Check for empty vars */
		if ($img != '' ){ $avatar = $img; } else { $avatar = $default_img; }
		if ($name != '' ){ $head = $name; } else { $head = __('New Member', $this->textdomain);}
		/* Get the fields  */
		$html .= '<div id="jt__member_'.$count.'" class="jt__teams">';
		$html .= '<h2 class="jt__teams-head">'.$head.'<span class="jt__remove button button-small" title="'.__('Delete this member',$this->textdomain).'">'.$hooks->get_fa_icon('fas fa-user-times').' &nbsp;'.__('Remove', $this->textdomain).'</span></h2>';
		$html .= '<ul class="jt__teams-list">';
			$html .= '<li id="jt__teams-img-'.$count.'" class="jt__teams-img">';
				$html .= $this->get_member_filed( 'img', array( 'count' => $count, 'img' => $img, 'img_id' => $img_id, 'mb_img' => $mb_img, 'mb_img_id' => $mb_img_id, 'avatar' => $avatar, 'default_img' => $default_img ));
			$html .= '</li>';
			$html .= '<li class="jt__teams-details">';
				$html .= '<ul class="jt__teams-details-list">';
					$html .= '<li class="jt__teams-details-item mb_name">' . $this->get_member_filed( 'text', null, $mb_name, $name, __( 'Full Name', $this->textdomain), 'far fa-user' ) . '</li>';
					$html .= '<li class="jt__teams-details-item mb_position">' . $this->get_member_filed( 'text', null, $mb_position, $position, __( 'Position', $this->textdomain), 'far fa-building' ) . '</li>';
					$html .= '<li class="jt__teams-details-item mb_phone">' . $this->get_member_filed( 'text', null, $mb_phone, $phone, __( 'Phone', $this->textdomain), 'fas fa-mobile-alt' ) . '</li>';
					$html .= '<li class="jt__teams-details-item mb_email">' . $this->get_member_filed( 'text', null, $mb_email, $email, __( 'Email', $this->textdomain), 'far fa-envelope' ) . '</li>';
					$html .= '<li class="jt__teams-details-item mb_desc">' . $this->get_member_filed( 'textarea', null, $mb_desc, $desc, __( 'Description', $this->textdomain), 'far fa-address-card' ) . '</li>';
				$html .= '</ul>';
			$html .= '</li>';
			$html .= '<li class="jt__teams-socials">';
				$html .= '<ul class="jt__teams-socials-list">';
					$html .= '<li class="jt__teams-socials-item">' . $this->get_member_filed( 'text', null, $mb_fb, $fb, __( 'Facebook URL', $this->textdomain), 'fab fa-facebook-f' ) . '</li>';
					$html .= '<li class="jt__teams-socials-item">' . $this->get_member_filed( 'text', null, $mb_tw, $tw, __( 'Twitter URL', $this->textdomain), 'fab fa-twitter' ) . '</li>';
					$html .= '<li class="jt__teams-socials-item">' . $this->get_member_filed( 'text', null, $mb_in, $in, __( 'LinkedIn URL', $this->textdomain), 'fab fa-linkedin-in' ) . '</li>';
					$html .= '<li class="jt__teams-socials-item">' . $this->get_member_filed( 'text', null, $mb_gp, $gp, __( 'Google+ URL', $this->textdomain), 'fab fa-google-plus-g' ) . '</li>';
					$html .= '<li class="jt__teams-socials-item">' . $this->get_member_filed( 'text', null, $mb_yt, $yt, __( 'YouTube URL', $this->textdomain), 'fab fa-youtube' ) . '</li>';
					$html .= '<li class="jt__teams-socials-item">' . $this->get_member_filed( 'text', null, $mb_it, $it, __( 'Instagram URL', $this->textdomain), 'fab fa-instagram' ) . '</li>';
				$html .= '</ul>';
			$html .= '</li>';
		$html .= '</ul>';
		$html .= '<p class="jt__teams-details-info">'.__('Leave any field empty if you don&#39;t want to display it.', $this->textdomain).'</p>';
		$html .= '</div>';
		$html .= ob_get_clean();
		return $html;
	}
	/**
	 * Get member field item
	 *
	 * @since    1.3.5
	 * @access   private
	 */
	private function get_member_filed( $type = '', $args = array(), $name = '', $value = '',  $title = '', $icon = '') {
		ob_start();
		/* Can't go anywhere without these arguments.  */
		if( empty($type) ) { return '<p style="color:red">ERROR: Parameter <b>$type</b> is missing in <b>get_member_filed()</b>!<br />( '.plugin_basename(__FILE__).' )</p>'; }
		if( $type != 'img' ){
			if( empty($name) ) { return '<p style="color:red">ERROR: Parameter <b>$name</b> is missing in <b>get_member_filed()</b>!<br />( '.plugin_basename(__FILE__).' )</p>'; }
		} else {
			if( !isset($args) || empty($args)){ return '<p style="color:red">ERROR: Parameter <b>$args</b> is missing in <b>get_member_filed()</b>!<br />( '.plugin_basename(__FILE__).' )</p>'; }
		}
		/* Setup general vars */
		$output = $fd_placeholder = '';
		$hooks = $this->hooks;
		if($title) { $fd_title = 'title="'.$title.'"'; $fd_placeholder = 'placeholder="'.$title.'"'; } 
		if($icon) { $fd_icon = $hooks->get_fa_icon($icon, null, $title); }
		switch ($type){
			case 'text': 
				$output .= $fd_icon.'<input type="text" name="'.$name.'" value="'.$value.'" '.$fd_placeholder.' />';
			break;	
			case 'textarea':
				$output .= $fd_icon .'<textarea name="'.$name.'" '.$fd_placeholder.'>'.$value.'</textarea>';
			break;
			case 'img':
				/* Required arguments needed for this field. */
				$required = array( 'count', 'img', 'img_id', 'mb_img', 'mb_img_id', 'avatar', 'default_img');
				/* Check the required args */
				if( $this->check_required_args('get_member_filed(img)', $required, $args) ){ return $this->check_required_args('get_member_filed(img)', $required, $args); };
				/* Output the field */
				$output .= '<div class="jt__teams-img-avatar jt__changeimg" data-jtcontainer="jt__teams-img-'.$args['count'].'"><img src="'.$args['avatar'].'" title="'.__('Add Image',$this->textdomain).'"/></div>';
				$output .= '<input class="jt__teams-attachment" name="'.$args['mb_img'].'" type="text" value="'.$args['img'].'" size="5" />';
				$output .= '<input class="jt__teams-attachmentid" name="'.$args['mb_img_id'].'" type="text" value="'.$args['img_id'].'" size="5"/>';
				$output .= '<span data-jtcontainer="jt__teams-img-'.$args['count'].'" class="jt__changeimg button button-primary" title="'.__('Add Image',$this->textdomain).'">'.$hooks->get_fa_icon('far fa-image').'</span>';
				$output .= '<span data-avatar="'.$args['default_img'].'" class="jt__removeimg button" title="'.__('Delete Image',$this->textdomain).'">'.$hooks->get_fa_icon('fas fa-trash').'</span>';
				$output .= '<div class="jt__teams-img-info">'.__( 'Use square shaped image larger than 200px.', $this->textdomain).'</div>';
			break;
			default: 
				$output .= '<p style="color:red">Unknown <b>$type</b> value ('.$type.') for <b>'.$name.'</b> in <b>get_member_filed()</b>!<br />( '.plugin_basename(__FILE__).' )</p>';
		}
		$output .= ob_get_clean();
		return $output;
	}
	/**
	 * Get Settings Field item
	 *
	 * @since    1.3.5
	 */
	public function get_settings_filed($name, $args = array(), $group_class = '', $group_style = '', $field_icon = '', $field_title = '') {
		ob_start();
		/* Can't go anywhere without these arguments.  */
		if( !isset($name) || empty($name)) { return '<p style="color:red">ERROR: Parameter <b>$name</b> is missing in <b>get_settings_filed()</b>! <u> '.plugin_basename(__FILE__).' </u></p>'; }
		if( !isset($args) || empty($args)){ return '<p style="color:red">ERROR: Parameter <b>$args</b> is missing for <b>'.$name.'</b> in <b>get_settings_filed()</b>! <u> '.plugin_basename(__FILE__).' </u></p>';}
		if( !isset($args['type']) || empty($args['type'])){return '<p style="color:red">ERROR: Argument <b>type</b> is missing for <b>'.$name.'</b> in <b>get_settings_filed()</b>! <u> '.plugin_basename(__FILE__).' </u></p>'; }
		/* Setup general vars */
		$output = '';
		$hooks = $this->hooks;
		if(!empty($group_style)){ $g_style = 'style="'.$group_style.'"'; } else { $g_style = ''; }
		if(!empty($field_icon) || !empty($field_title)){
			if($field_icon){ $fd_icon = $hooks->get_fa_icon($field_icon) . ' &nbsp;'; } else { $fd_icon = '';}
			if($field_title) { $output .= '<h4>' . $fd_icon . $field_title . '</h4>'; }
		}
		/* Ok, Now we can go on. */
		switch ($args['type']){
			case 'radio': 
				/* Can't do anything without any choices.  */
				if( !isset($args['choices']) || empty($args['choices'])) { 
					return '<p style="color:red">ERROR: Argument <b>choices</b> is missing for <b>'.$name.'</b> in <b>get_settings_filed()</b>! <u> '.plugin_basename(__FILE__).' </u></p>'; 
				}
				$output .= '<div id="'.$name.'" class="jt__switch-field ' . $group_class . '" ' . $g_style . '>';
				/* Required arguments needed for this field. */
				$required = array('value');
				/* Check items and setup vars */
				foreach ($args['choices'] as $choice => $radios){
					/* Check the required args */
					if( $this->check_required_args('get_settings_filed('.$name.')', $required, $radios) ){ 
						return $this->check_required_args('get_settings_filed('.$name.')', $required, $radios); 
					};
					/* Setup field vars */
					foreach ($radios as $radio => $val){
						if( isset($radio) && !empty($val) ){ ${$radio} = $val; } else { 
							/* Required args cannot be empty. */
							if($this->check_empty_args('get_settings_filed('.$name.')', $radio, $required, $val)){ 
								return $this->check_empty_args('get_settings_filed('.$name.')', $radio, $required, $val); 
							}
							${$radio} = '';	
						}
					}
					/* Check the vars */
					if( !isset($lb_content) && !isset($lb_icon) ) {
						return '<p style="color:red">ERROR: Both <b>$lb_content</b> and <b>$lb_icon</b> arguments are missing for <b>'.$name.'</b> in <b>get_settings_filed()</b>! <u> '.plugin_basename(__FILE__).' </u></p>'; 
					} 
					if( isset($lb_title) && !empty($lb_title) ){ $title = 'title="'.$lb_title.'"'; } else { $title = ''; }
					if( isset($lb_style) && !empty($lb_style) ){ $style = 'style="'.$lb_style.'"'; } else { $style = ''; }
					if( isset($lb_content) && !empty($lb_content) ){ $content = $lb_content; } else { $content = ''; }
					if( isset($lb_icon) && !empty($lb_icon) ){ $icon = $hooks->get_fa_icon($lb_icon); } else { $icon = ''; }
					/* Output the field */
					$output .= '<input type="radio" id="'.$choice.'" name="'.$name.'" value="'.$value.'" '.$checked.' />';
					$output .= '<label for="'.$choice.'" class="'.$choice.'" ' . $title . ' ' . $style . '>'.$icon.$content.'</label>'; 
				}
				$output .= '</div>';
			break;	
			case 'checkbox':
				/* Required arguments needed for this field. */
				$required = array('id', 'value');
				$output .= '<div class="jt__switch-field ' . $group_class . '" ' . $g_style . '>';
				/* Check the required args */
				if( $this->check_required_args('get_settings_filed('.$name.')', $required, $args) ){ 
					return $this->check_required_args('get_settings_filed('.$name.')', $required, $args); 
				};
				/* Setup field vars */
				foreach ($args as $checkbox => $val){
					if( isset($checkbox) && !empty($val) ){ ${$checkbox} = $val; } else { 
						/* Required args cannot be empty. */
						if($this->check_empty_args('get_settings_filed('.$name.')', $checkbox, $required, $val)){ 
							return $this->check_empty_args('get_settings_filed('.$name.')', $checkbox, $required, $val); 
						}
						${$checkbox} = '';	
					}
				}
				/* Check the vars */
				if( !isset($lb_content) && !isset($lb_icon) ) {
					return '<p style="color:red">ERROR: Both <b>$lb_content</b> and <b>$lb_icon</b> arguments are missing for <b>'.$name.'</b> in <b>get_settings_filed()</b>! <u> '.plugin_basename(__FILE__).' </u></p>'; 
				} 
				if( isset($lb_title) && !empty($lb_title) ){ $title = 'title="'.$lb_title.'"'; } else { $title = ''; }
				if( isset($lb_content) && !empty($lb_content) ){ $content = $lb_content; } else { $content = ''; }
				if( isset($lb_icon) && !empty($lb_icon) ){ $icon = $hooks->get_fa_icon($lb_icon, 'font-size:120%;'); } else { $icon = ''; }
				/* Output the field */
				$output .= '<input type="checkbox" id="'.$id.'" name="'.$name.'" value="'.$value.'" '.$checked.' />';
				$output .= '<label for="'.$id.'" class="jt_checkbox" ' . $title . ' style="font-size:90%;">'.$icon.$content.'</label></div>';
			break;	
			case 'text':
				/* Required arguments needed for this field. */
				$required = array('id');
				/* Check the required args */
				if( $this->check_required_args('get_settings_filed('.$name.')', $required, $args) ){ 
					return $this->check_required_args('get_settings_filed('.$name.')', $required, $args); 
				};
				/* Setup field vars */
				foreach ($args as $text => $val){ if( isset($text) && !empty($val) ){ ${$text} = $val; } else { ${$text} = ''; } }
				/* Check the vars */
				if( isset($id) && !empty($id) ){ $inpid = 'id="'.$id.'"'; } else { $inpid = ''; }
				if( isset($value) && !empty($value) ){ $inpval = 'value="'.$value.'"'; } else { $inpval = ''; }
				if( isset($inp_class) && !empty($inp_class) ){ $inpclass = 'class="'.$inp_class.'"'; } else { $inpclass = ''; }
				if( isset($lb_class) && !empty($lb_class) ){ $class = 'class="'.$lb_class.'"'; } else { $class = ''; }
				/* Output the field */
				$output .= '<input type="text" '.$inpid.' name="'.$name.'" '.$inpval.' '.$inpclass.' style="width:100%;display:block" />';
				if( isset($lb_content) && !empty($lb_content) ){ $output .= '<i ' . $class . ' style="font-size:90%;">'.$lb_content.'</i>'; }
			break;
			case 'colorpicker':
				/* Setup field vars */
				foreach ($args as $color => $val){ if( isset($color) && !empty($val) ){ ${$color} = $val; } else { ${$color} = ''; } }
				/* Check the vars */
				if( isset($id) && !empty($id) ){ $inpid = 'id="'.$id.'"'; } else { $inpid = ''; }
				if( isset($value) && !empty($value) ){ $inpval = 'value="'.$value.'"'; } else { $inpval = ''; }
				/* Output the field */
				$output .= '<input type="text" '.$inpid.' name="'.$name.'" '.$inpval.' class="jt__color_picker" />';
			break;
			case 'slider':
				/* Required arguments needed for this field. */
				$required = array('unit', 'value', 'id');
				/* Check the required args */
				if( $this->check_required_args('get_settings_filed('.$name.')', $required, $args) ){ 
					return $this->check_required_args('get_settings_filed('.$name.')', $required, $args); 
				};
				/* Setup field vars */
				foreach ($args as $slider => $val){ 
					if( isset($slider) && !empty($val) ){ ${$slider} = $val; } else { 
						/* Required args cannot be empty. */
						if($this->check_empty_args('get_settings_filed('.$name.')', $slider, $required, $val)){ 
							return $this->check_empty_args('get_settings_filed('.$name.')', $slider, $required, $val); 
						}
						${$slider} = ''; 
					}
				}
				/* Check the vars */
				if( isset($id) && !empty($id) ){ $inpid = 'id="'.$id.'"'; } else { $inpid = ''; }
				/* Output the field */
				$output .= '<div class="jt__slider"><div class="jt__slider-bg" data-jt_unit="'.$unit.'"><div class="jt__slider-handle ui-slider-handle"></div></div>';
				$output .= '<input type="text" '.$inpid.' name="'.$name.'" value="'.$value.'" class="jt__slider-input" readonly /></div>';
			break;
			default: 
				$output .= '<p style="color:red">Unknown <b>$type</b> value ('.$args['type'].') for <b>'.$name.'</b> in <b>get_settings_filed()</b>! <u> '.plugin_basename(__FILE__).' </u></p>';
		}
		$output .= ob_get_clean();
		return $output;
	}
	/**
	 * Check required arguments
	 *
	 * @since    1.3.5
	 * @access   private
	 */
	private function check_required_args($name = '', $required = array(), $checkthis = array() ) {
		/* Can't go anywhere without these arguments.  */
		if( !isset($name) || empty($name)) { return '<p style="color:red">ERROR: Parameter <b>$name</b> is missing in <b>check_required_args()</b>!<br />( '.plugin_basename(__FILE__).' )</p>'; }
		if( !isset($required) || empty($required)) { return '<p style="color:red">ERROR: Parameter <b>$required</b> of <b>'.$name.'</b> is missing in <b>check_required_args()</b>!<br />( '.plugin_basename(__FILE__).' )</p>'; }
		if( !isset($checkthis) || empty($checkthis)){return '<p style="color:red">ERROR: Parameter <b>$checkthis</b> of <b>'.$name.'</b> is missing in <b>check_required_args()</b>!<br />( '.plugin_basename(__FILE__).' )</p>'; }
		/* Check the required args */
		foreach ($required as $must){
			if ( !array_key_exists( $must, $checkthis )){
				return '<p style="color:red">ERROR: Argument <b>'.$must.'</b> of <b>'.$name.'</b> is missing!<br />( '.plugin_basename(__FILE__).' )</p>';
			}
		}
	}
	/**
	 * Check empty arguments
	 *
	 * @since    1.3.5
	 * @access   private
	 */
	private function check_empty_args($name, $arg , $required = array(), $val ) {
		/* Can't go anywhere without these arguments.  */
		if( !isset($name) || empty($name)) { return '<p style="color:red">ERROR: Parameter <b>$name</b> is missing in <b>check_empty_args()</b>!<br />( '.plugin_basename(__FILE__).' )</p>'; }
		if( !isset($val) ) { return '<p style="color:red">ERROR: Parameter <b>$val</b> of <b>'.$name.'</b> is missing in <b>check_empty_args()</b>!<br />( '.plugin_basename(__FILE__).' )</p>'; }
		if( !isset($arg) || empty($arg)){return '<p style="color:red">ERROR: Parameter <b>$checkthis</b> of <b>'.$name.'</b> is missing in <b>check_empty_args()</b>!<br />( '.plugin_basename(__FILE__).' )</p>'; }
		if( !isset($required) || empty($required)) { return '<p style="color:red">ERROR: Parameter <b>$required</b> of <b>'.$name.'</b> is missing in <b>check_empty_args()</b>!<br />( '.plugin_basename(__FILE__).' )</p>'; }
		/* Check the args */
		if ( in_array($arg, $required) && empty($val)){
			return '<p style="color:red">ERROR: '.$val.' -- Argument <b>'.$arg.'</b> of <b>'.$name.'</b> cannot be empty!<br />( '.plugin_basename(__FILE__).' )</p>';
		}
	}
	/**
	 * Get the registered Teams in order to be sent to MCE shortcode generator.
	 *
	 * @since    1.3.5
	 */
	public function ajaxGetMceTeams() {
		/* Security Check */
		check_ajax_referer( 'mceButton_nonce', 'mceNonce' );
		/* Ok, Now we can go on. */
		$jwdTeams = array();
		$jwdTeam_posts = get_posts( array( 'post_type' => 'jwd_team', 'posts_per_page' => -1 ) );
		foreach($jwdTeam_posts as $jwdTeam){
			setup_postdata( $jwdTeam );
			array_push($jwdTeams, array( 'text' => get_the_title( $jwdTeam->ID ), 'value' => $jwdTeam->ID ));
		}
		wp_reset_postdata();
		/* Return results */
		wp_send_json($jwdTeams);
	}
	/**
	 * Get the default settings to be used in JS.
	 * Via AJAX.
	 *
	 * @since    1.3.5
	 */
	public function ajaxGetDefaultSettings() {
		/* Security Check */
		check_ajax_referer( 'resetTeamSettings_nonce', 'reset_nonce' );
		/* Ok, Now we can go on. */
		$return = array();
		$settings = $this->hooks->get_team_settings();
		foreach ($settings as $option => $val){
			if(!isset($val['default'])){ $value = ''; } else { $value = $val['default']; }
			if($val['type'] == 'slider'){
				$return[$option] = array('type' => $val['type'], 'def' => $value, 'unit' => $val['unit']);
			} else {
				$return[$option] = array('type' => $val['type'], 'def' => $value);
			}
		}
		/* Return results */
		wp_send_json($return);
	}
	/**
	 * Get the rating box.
	 *
	 * @since    1.3.5
	 */
	public function get_rating_box() {
		ob_start();
		$output ='<div class="jt__rating"><div class="jt__rating-left">'; 
		$output .= '<h5>' . __('If you like our plugin', $this->textdomain) . '</h5>';
		$output .= '<h2><span>' . __("Don't forget to rate", $this->textdomain) . '</span>&nbsp;&nbsp;';
		$output .= '<a href="' . esc_url( 'https://wordpress.org/support/plugin/jwd-teams/reviews/' ) .'" target="blank" >';
		$output .= '<span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span></a></h2></div>';
		$output .= '<div class="jt__rating-right" style="background-image:url('. plugins_url('/img/jordachewd_200x200px.png', dirname(__FILE__)).')"><h3>' . __('Thank you!', $this->textdomain) . '</h3>';
		$output .= '<h5>' . __('It helps us to become better for you!', $this->textdomain) .'</h5></div></div>';
		$output .= ob_get_clean();
		return $output;
	}
	/**
	 * Get the rating box.
	 *
	 * @since    1.3.5
	 */
	public function get_customcss_box($postID, $custom_css) {
		$css_editor = array( 
			'textarea_name' 	=> 'team_custom_css', 
			'editor_class'		=> 'jt__custom_css',
			'media_buttons' 	=> false, 
			'tinymce' 			=> false, 
			'quicktags' 		=> false,
			'textarea_rows' 	=> '8', 
			'wpautop' 			=> false 
		);
		$output ='<p style="color:#c00;margin:.25em 0;">' . sprintf( __('Please DO NOT include %s tag!', $this->textdomain ), '<code>&lt;style&gt;</code>') . '</p>';
		ob_start();
		$output .= wp_editor( $custom_css , 'team_custom_css', $css_editor ) . ob_get_clean();
		$output .= '<p>' . sprintf( __( 'Use %1$s or %2$s to target this team.', $this->textdomain ), '<code>#jt__team_'.$postID.'</code>', '<code>.jt__team_'.$postID.'</code>' ) . '</p>';
		$output .= '<p><b>'.__('Example:', $this->textdomain) . '</b><br />#jt__team_'. $postID .' { color:#f00; }<br />&nbsp;.jt__team_'. $postID .' { color:#f00; };</p>';
		return $output;
	}
}
?>