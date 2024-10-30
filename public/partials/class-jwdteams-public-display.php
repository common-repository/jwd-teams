<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       		http://jordachewd.com
 * @since      		1.3.5
 *
 * @package    		JWD_Teams
 * @subpackage 		JWD_Teams/public/partials
 */
class JWD_Teams_Public_Display {
	/**
	 * The Full Name of this plugin.
	 *
	 * @since    1.3.5
	 * @access   protected
	 * @var      string    $fullname    The Full Name of this plugin.
	 */
	protected $fullname;
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
	 * The hooks of this plugin.
	 *
	 * @since    1.3.5
	 * @access   protected
	 * @var      string    $hooks    	Retrive the needed hooks for public side
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
	 * Get Tipso Script Hook
	 *
	 * @since    1.3.5
	 * @access   public
	 */
	public function get_tipso_script($args){
		ob_start();
		$output = '';
		if( isset($args['tipso_id']) && !empty($args['tipso_id'])){ $tipso_id = $args['tipso_id']; } else { $tipso_id = ''; }
		if( isset($args['tipso_bg']) && !empty($args['tipso_bg'])){ $tipso_bg = $args['tipso_bg']; } else { $tipso_bg = ''; }
		if( isset($args['tipso_txt']) && !empty($args['tipso_txt'])){ $tipso_txt = $args['tipso_txt']; } else { $tipso_txt = ''; }
		if( isset($args['tipso_ttl']) && !empty($args['tipso_ttl'])){ $tipso_ttl = $args['tipso_ttl']; } else { $tipso_ttl = ''; }
		if( isset($args['tipso_ttl_bg']) && !empty($args['tipso_ttl_bg'])){ $tipso_ttl_bg = $args['tipso_ttl_bg']; } else { $tipso_ttl_bg = ''; }
		if( $tipso_id != '' ){ 
			$output .= '<script type="text/javascript">';
				$output .= 'jQuery(document).ready(function(){ ';
					$output .= 'jQuery(".jt__team_tooltip_'.$tipso_id.'").jwdteams_tipso({';
					$output .= 'width : 320,';
					if($tipso_bg != ''){ $output .= 'background: "'.$tipso_bg.'",'; }
					if($tipso_txt != ''){ $output .= 'color: "'.$tipso_txt.'",'; }
					if($tipso_ttl != ''){ $output .= 'titleColor: "'.$tipso_ttl.'",'; }
					if($tipso_ttl_bg != ''){ $output .= 'titleBackground: "'.$tipso_ttl_bg.'",'; }
					$output .= 'tooltipHover : true';
					$output .= '});';
				$output .= '});';
			$output .= '</script>';
		}
		$output .= ob_get_clean();
		return $output;
	}
	/**
	 * Get Team Style Hook
	 *
	 * @since    1.3.5
	 * @access   public
	 */
	public function get_team_style($id){
		/* Can't go anywhere without an $id */
		if(empty($id)){ return; }
		/* Vars */
		ob_start();
		$settings = $this->hooks->get_team_settings();
		$team = '#jt__team_'.esc_attr( $id ).' .jt__member';
		foreach ($settings as $option => $args ){ ${$option} = get_post_meta( $id, $option, true ); }
		$team_custom_css = get_post_meta( $id, 'team_custom_css', true); 
		/* Buid the style */
		$output = '<style type="text/css">';
		if(!empty( $mb_name_color )) { 		$output .= $team . '-name h2{color:'.$mb_name_color.';}'; } 
		if(!empty( $mb_name_size )) { 		$output .= $team . '-name h2{font-size:'.$mb_name_size.'px;}'; } 
		if(!empty( $mb_position_color )) { 	$output .= $team . '-position{color:'.$mb_position_color.';}'; } 
		if(!empty( $mb_position_size )) { 	$output .= $team . '-position{font-size:'.$mb_position_size.'px;}'; } 
		if(!empty( $mb_contact_color )) { 	$output .= $team . '-phone, '.$team . '-email { color:'.$mb_contact_color.';}'; } 
		if(!empty( $mb_contact_size )) { 	$output .= $team . '-phone, '.$team . '-email { font-size:'.$mb_contact_size.'px;}'; } 
		if(!empty( $mb_social_color )) { 	$output .= $team . '-socials a{color:'.$mb_social_color.';}'; } 
		if(!empty( $mb_social_hover )) { 	$output .= $team . '-socials a:hover{color:'.$mb_social_hover.';}'; } 
		if(!empty( $mb_social_size )) { 	$output .= $team . '-socials a{font-size:'.$mb_social_size.'px;}'; } 
		if(!empty( $mb_desc_color )) { 		$output .= $team . '-description {color:'.$mb_desc_color.';}'; } 
		if(!empty( $mb_desc_size )) { 		$output .= $team . '-description {font-size:'.$mb_desc_size.'px;}'; } 
		/* Include team custom CSS */
		if(!empty( $team_custom_css )) { 	$output .= esc_attr($team_custom_css); } 
		$output .= '</style>';
		$output .= ob_get_clean();
		return $output;
	}
}
?>