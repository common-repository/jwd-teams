<?php
/**
 * General Hooks used on both public and admin sides
 *
 * @link       		http://jordachewd.com
 * @since      		1.3.5
 *
 * @package    		JWD_Teams
 * @subpackage 		JWD_Teams/includes
 */
class JWD_Teams_Hooks {
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
	 * Initialize the class and set its properties.
	 *
	 * @since    	1.3.5
	 * @param      	string    $fullname       	The Full Name of this plugin.
	 * @param      	string    $textdomain       The name of the plugin.
	 * @param      	string    $version    		The version of this plugin.
	 */
	public function __construct( $fullname, $textdomain, $version ) {
		$this->fullname = $fullname;
		$this->textdomain = $textdomain;
		$this->version = $version;
	}
	/**
	 * Get FontAwesome icon
	 *
	 * @since    1.3.5
	 * @access   public
	 */
	public function get_fa_icon($icon, $style = '', $title = '') {
		/* Can't go anywhere without $icon argument.  */
		if(!isset($icon)){
			return '<p style="color:red">ERROR: Parameter <b>$icon</b> is missing in <b>get_fa_icon()</b>!<br />( '.plugin_basename(__FILE__).' )</p>';
		}
		/* Setup general vars */
		$output = $icon_style = $icon_title = '';
		if($style) { $icon_style = 'style="'.$style.'"';  }
		if($title) { $icon_title = 'title="'.$title.'"';  }
		/* Get the icon */
		$output .= '<i class="'.$icon.'" '.$icon_style.' '.$icon_title.'></i> '; 
		return $output;
	}
	/**
	 * Register the Team settings items & args.
	 *
	 * @since    1.3.5
	 * @access   public
	 */
	public function get_team_settings($team_grid_style = '') {
		$settings = array(
			/* Settings Tab*/
			'team_design' 	=> array(
				'type'			=> 'radio',
				'default'		=> 'vertical',
				'choices'		=> array(
					'team_design_v'	=> array( 'value' => 'vertical', 'lb_title' => __( 'Vertical', $this->textdomain ), 'lb_icon' => 'fas fa-th-list fa-rotate-90' ), 
					'team_design_h'	=> array( 'value' => 'horizontal', 'lb_title' => __( 'Horizontal', $this->textdomain ), 'lb_icon' => 'fas fa-th-list' )
				)
			),
			'team_settings_grid' => array(
				'type'			=> 'radio',
				'default'		=> 'three_per_row',
				'choices'		=> array(
					'list_one_per_row' 	=> array( 'value' => 'one_per_row', 'lb_title' => __( 'One per row', $this->textdomain ), 'lb_content' => '1' ),
					'list_two_per_row' 	=> array( 'value' => 'two_per_row', 'lb_title' => __( 'Two per row', $this->textdomain ), 'lb_content' => '2' ),
					'list_three_per_row'=> array( 'value' => 'three_per_row', 'lb_title' => __( 'Three per row', $this->textdomain ), 'lb_content' => '3' ),
					'list_four_per_row'	=> array( 'value' => 'four_per_row', 'lb_style' => $team_grid_style, 'lb_title' => __( 'Four per row', $this->textdomain ), 'lb_content' => '4' ),
					'list_five_per_row'	=> array( 'value' => 'five_per_row', 'lb_style' => $team_grid_style, 'lb_title' => __( 'Five per row', $this->textdomain ), 'lb_content' => '5'	)
				)
			),
			'team_settings_img' => array(
				'type'			=> 'radio',
				'default'		=> 'member_img_circle',
				'choices'		=> array( 
					'member_img_circle' => array( 'value' => 'member_img_circle', 'lb_title' => __( 'Disc', $this->textdomain ), 'lb_icon' => 'fas fa-circle' ),
					'member_img_square'	=> array( 'value' => 'member_img_square', 'lb_title' => __( 'Square', $this->textdomain ), 'lb_icon' => 'fas fa-stop' )
				)
			),
			'team_settings_desc' => array(
				'type'			=> 'radio',
				'default'		=> 'member_desc_list',
				'choices'		=> array(
					'member_desc_list' 		=> array( 'value' => 'member_desc_list', 'lb_title' => __( 'List item', $this->textdomain ), 'lb_icon' => 'fas fa-list' ),
					'member_desc_tooltip' 	=> array( 'value' => 'member_desc_tooltip', 'lb_title' => __( 'Tooltip', $this->textdomain ), 'lb_icon' => 'fas fa-comment-dots' )
				)
			),
			'team_txt_align' => array(
				'type'			=> 'radio',
				'default'		=> 'txt_center',
				'choices'		=> array( 
					'team_txt_align_left' 	=> array( 'value' => 'txt_left', 'lb_title' => __( 'Align left', $this->textdomain ), 'lb_icon' => 'fas fa-align-left' ),
					'team_txt_align_center'	=> array( 'value' => 'txt_center', 'lb_title' => __( 'Align center', $this->textdomain ), 'lb_icon' => 'fas fa-align-center' ),
					'team_txt_align_right'	=> array( 'value' => 'txt_right', 'lb_title' => __( 'Align right', $this->textdomain ), 'lb_icon' => 'fas fa-align-right' )
				)
			),
			'team_desc_justify'		=> array( 'type' => 'checkbox', 'id' => 'team_desc_justify', 'value' => 'yes', 'lb_icon' => 'fas fa-align-justify', 'lb_content' => __( 'Justify Description', $this->textdomain ) ),
			'team_more_kill'		=> array( 'type' => 'checkbox', 'id' => 'team_more_kill', 'value' => 'yes', 'lb_content' => sprintf( __('Deactivate %s', $this->textdomain), '"'. __('Read More', $this->textdomain).'"')),
			'team_more_desc'		=> array( 'type' => 'text', 'id' => 'team_more_desc', 'lb_content' => __( 'Change label text', $this->textdomain ), 'default' => __( 'Read More', $this->textdomain ) ),
			'team_settings_title'	=> array( 'type' => 'checkbox', 'id' => 'team_settings_title', 'value' => 'hide', 'lb_content' => __( 'Hide Team Title', $this->textdomain ) ),
			'team_hide_icons'		=> array( 'type' => 'checkbox', 'id' => 'team_hide_icons', 'value' => 'hide', 'lb_content' => __( 'Hide Icons', $this->textdomain ) ),
			'team_settings_credit'	=> array( 'type' => 'checkbox', 'id' => 'team_settings_credit', 'value' => 'hide', 'lb_content' => sprintf( __('Hide %s', $this->textdomain), '"'. __('Powered by', $this->textdomain).'"')),
			/* Colors Tab*/
			'mb_name_color' 		=> array( 'type' => 'colorpicker', 'id' => 'mb_name_color'),
			'mb_position_color' 	=> array( 'type' => 'colorpicker', 'id' => 'mb_position_color' ),
			'mb_contact_color' 		=> array( 'type' => 'colorpicker', 'id' => 'mb_contact_color' ),
			'mb_social_color' 		=> array( 'type' => 'colorpicker', 'id' => 'mb_social_color' ),
			'mb_social_hover' 		=> array( 'type' => 'colorpicker', 'id' => 'mb_social_hover' ),
			'mb_desc_color' 		=> array( 'type' => 'colorpicker', 'id' => 'mb_desc_color' ),
			'mb_tooltip_title' 		=> array( 'type' => 'colorpicker', 'id' => 'mb_tooltip_title' ),
			'mb_tooltip_title_bg'	=> array( 'type' => 'colorpicker', 'id' => 'mb_tooltip_title_bg' ),
			'mb_tooltip_color' 		=> array( 'type' => 'colorpicker', 'id' => 'mb_tooltip_color' ),
			'mb_tooltip_bg' 		=> array( 'type' => 'colorpicker', 'id' => 'mb_tooltip_bg' ),
			/* Sizes Tab*/
			'mb_name_size' 			=> array( 'type' => 'slider', 'unit' => 'px', 'default' => '24', 'id' => 'mb_name_size' ),
			'mb_position_size'		=> array( 'type' => 'slider', 'unit' => 'px', 'default' => '14', 'id' => 'mb_position_size' ),
			'mb_contact_size' 		=> array( 'type' => 'slider', 'unit' => 'px', 'default' => '12', 'id' => 'mb_contact_size' ),
			'mb_social_size' 		=> array( 'type' => 'slider', 'unit' => 'px', 'default' => '14', 'id' => 'mb_social_size' ),
			'mb_desc_size' 			=> array( 'type' => 'slider', 'unit' => 'px', 'default' => '12', 'id' => 'mb_desc_size' ),
			'mb_desc_leng' 			=> array( 'type' => 'slider', 'unit' => 'ch', 'default' => '100', 'id' => 'mb_desc_leng' ) 
		);
		return $settings;
	}
}