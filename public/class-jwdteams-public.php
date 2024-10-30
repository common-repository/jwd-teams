<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    		JWD_Teams
 * @subpackage 		JWD_Teams/public
 * @author     		JordacheWD <office@jordachewd.ro>
 */
class JWD_Teams_Public {
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
	 * The hooks of this plugin.
	 *
	 * @since    1.3.5
	 * @access   protected
	 * @var      string    $hooks    	Retrive the needed hooks for public side
	 */
	private $hooks;
	/**
	 * The display of this plugin.
	 *
	 * @since    1.3.5
	 * @access   protected
	 * @var      string    $display    The string used to provide a public area view for the plugin.
	 */
	private $display;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    	1.3.5
	 * @param      	string    $fullname       	The Full Name of this plugin.
	 * @param      	string    $textdomain       The name of the plugin.
	 * @param      	string    $version    		The version of this plugin.
	 */
	public function __construct( $fullname, $textdomain, $version, $hooks ) {
		$this->fullname = $fullname;
		$this->textdomain = $textdomain;
		$this->version = $version;
		$this->hooks = $hooks;
		$this->load_dependencies();
	}
	/**
	 * Load the required dependencies for Public Side.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - JWD_Teams_Public_Display. Provide a public area view for the plugin.
	 *
	 * @since    1.3.5
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for markup the admin-facing aspects of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/class-jwdteams-public-display.php';
		$this->display = new JWD_Teams_Public_Display($this->fullname, $this->textdomain, $this->version, $this->hooks);
	}
	/**
	 * Register the stylesheets & scripts for the public-facing side of the site.
	 *
	 * @since    1.3.5
	 */
	public function public_enqueue() {
		global $post;
		/* Load scripts and styles only where the [jwd_team] shortcode is loaded */
		if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'jwd_team')) {
			wp_enqueue_style( $this->textdomain . '-public-style', plugin_dir_url( __FILE__ ) . 'css/jwdteams-public.min.css', array(), $this->version, 'all' );
			if ( is_rtl() ) { wp_enqueue_style( $this->textdomain . '-public-style-rtl', plugin_dir_url( __FILE__ ) . 'css/jwdteams-public-rtl.min.css', array(), $this->version, 'all' ); }
			wp_enqueue_style( $this->textdomain . '-fontawesome', 'https://use.fontawesome.com/releases/v5.3.1/css/all.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->textdomain . '-tipso-style', plugin_dir_url( __FILE__ ) . 'css/jwdteams-tipso.min.css', array(), $this->version, 'all' );
			/* wp_enqueue_script($this->textdomain . '-public-script', plugin_dir_url( __FILE__ ) . 'js/jwdteams-public.min.js', array( 'jquery' ), $this->version, true ); */
			wp_enqueue_script($this->textdomain . '-tipso-script', plugin_dir_url( __FILE__ ) . 'js/jwdteams-tipso.min.js', array( 'jquery' ), $this->version, true );
		}
	}
	/**
	 * Register the Shortcode
	 *
	 * @since    1.3.5
	 */
	public function public_shortcode() { add_shortcode( 'jwd_team', array( $this, 'shortcode') ); }
	/**/
	public function shortcode($atts) {
		ob_start();
		$output = $desc_style = $icon_style= '';
		$hooks = $this->hooks;
		$settings = $hooks->get_team_settings();
		$atts = shortcode_atts(	array( 'id' => ''), $atts, 'jwd_team' ); 
		if( $atts['id'] != '' ){
			/* Check if the Team exists */
			if ( FALSE === get_post_status( $atts['id'] ) ) {
			  $output .= '<div class="jt__error">'. sprintf ( __( 'Sorry! No team registered with this ID: %s', $this->textdomain ) ,'<b>'.$atts['id'].'</b>' ).'</div>';
			} else {
				/* VARS */
				$member_data = get_post_meta( $atts['id'], 'member_data', true ) ? get_post_meta( $atts['id'], 'member_data', true ) : array();
				/* Generate Settings Vars */
				foreach ($settings as $option => $args ){ ${$option} = get_post_meta( $atts['id'], $option, true ); }
				/* Defaults */
				if( $team_desc_justify == 'yes'){ $desc_style = 'style="text-align:justify;"'; } 
				if( $team_hide_icons == 'hide'){ $icon_style = 'display:none'; }
				/* Generate Shortcode */
				$output .= '<div id="jt__team_'.esc_attr( $atts['id'] ).'" class="jt__team_'.esc_attr( $atts['id'] ).' jt__wrapper">';
				/* Get Team Style */
				$output .= $this->display->get_team_style( $atts['id'] );
				if( $team_settings_title != 'hide'){ $output .= '<h1>'.get_the_title( esc_attr( $atts['id'] ) ).'</h1>'; }
				$output .= '<ul class="jt__team jt_'.esc_attr($team_design).'">';
				if (count($member_data) > 0){
					foreach((array)$member_data as $member ){
						if ( isset($member['img']) || isset($member['img_id']) || isset($member['name']) || isset($member['position']) || isset($member['phone']) || isset($member['email']) || isset($member['desc']) ){
							$output .= '<li class="jt__team-item '.esc_attr( $team_settings_grid ).'">';
							$output .= '<ul class="jt__member '.esc_attr( $team_txt_align ).' ">';
								/**/						
								$imgURL = !empty($member['img_id']) ? wp_get_attachment_image_url($member['img_id'], 'large') : ( !empty($member['img']) ? esc_url( $member['img'] ) : '' );
								
								
								$imgBg = !empty($imgURL) ? 'style="background-image:url(\''. $imgURL .'\')"' : '';
								/**/
								if( $team_settings_desc == 'member_desc_tooltip' && $member['desc'] != ''){
									$output .= '<li class="jt__member-img jt__team_tooltip_'.esc_attr( $atts['id'] ) . ' ' . esc_attr( $team_settings_img).'" '.$imgBg.' data-tipso-title="'.esc_attr( $member['name'] ).'" data-tipso="'.$member['desc'].'">';
								} else { 
									$output .= '<li class="jt__member-img '.esc_attr( $team_settings_img).'" '.$imgBg.'>'; 
								}
								$output .= '</li>'; 
								if($member['name'] != ''){ $output .= '<li class="jt__member-name"><h2>'.esc_attr( $member['name'] ).'</h2></li>'; }
								if($member['position'] != ''){ $output .= '<li class="jt__member-position">'.esc_attr( $member['position'] ).'</li>'; }
								if($member['phone'] != ''){ $output .= '<li class="jt__member-phone">'.$hooks->get_fa_icon('fas fa-mobile-alt', $icon_style) . esc_attr( $member['phone'] ).'</li>'; }
								if($member['email'] != ''){ $output .= '<li class="jt__member-email">'.$hooks->get_fa_icon('far fa-envelope', $icon_style) . esc_attr( $member['email'] ).'</li>'; }
								if( $member['fb'] != '' || $member['tw'] != '' || $member['in'] != '' || $member['gp'] != '' || $member['yt'] != '' || $member['it'] != ''){
									 $output .= '<li class="jt__member-socials">';
										if($member['fb'] != ''){ $output .= '<a href="'.esc_url($member['fb']).'" target="blank">'.$hooks->get_fa_icon('fab fa-facebook-f').'</a>'; }
										if($member['tw'] != ''){ $output .= '<a href="'.esc_url($member['tw']).'" target="blank">'.$hooks->get_fa_icon('fab fa-twitter').'</a>'; }
										if($member['in'] != ''){ $output .= '<a href="'.esc_url($member['in']).'" target="blank">'.$hooks->get_fa_icon('fab fa-linkedin-in').'</a>'; }
										if($member['gp'] != ''){ $output .= '<a href="'.esc_url($member['gp']).'" target="blank">'.$hooks->get_fa_icon('fab fa-google-plus-g').'</a>'; }
										if($member['yt'] != ''){ $output .= '<a href="'.esc_url($member['yt']).'" target="blank">'.$hooks->get_fa_icon('fab fa-youtube').'</a>'; }
										if($member['it'] != ''){ $output .= '<a href="'.esc_url($member['it']).'" target="blank">'.$hooks->get_fa_icon('fab fa-instagram').'</a>'; }
									 $output .= '</li>';
								}
								if($team_settings_desc == 'member_desc_list' && $member['desc'] != ''){ 
									$output .= '<li class="jt__member-description" '.$desc_style.'>'; 
									if($team_more_kill != 'yes'){
										if( strlen($member['desc'] ) < $mb_desc_leng){
											$output .= $member['desc'];
										} else {
											$output .= substr( $member['desc'], 0, $mb_desc_leng ).'...'; 
											$output .= '<span class="jt__team_tooltip_'.esc_attr( $atts['id'] ).' jt__tipso_style" data-tipso-title="'.esc_attr( $member['name'] ).'" data-tipso="'.$member['desc'].'"> &nbsp;';
											$output .= esc_attr( $team_more_desc )  . '</span>';	
										}
									} else { $output .= $member['desc']; }
									$output .= '</li>';
								}
							$output .= '</ul></li>';
						} else {
							$output .= '<li class="jt__team-item jt__error" >'.__('Sorry! There are no members registered for this team.', $this->textdomain).'</li>';
						}
					}
				} else {
					$output .= '<li class="jt__team-item jt__error" >'.__('Sorry! There are no members registered for this team.', $this->textdomain).'</li>';
				}
				$output .= '</ul>';
				if( $team_settings_credit != 'hide'){ 
					$output .= '<p class="jt__credit">' . __('Powered by', $this->textdomain) . ' <a href="'.esc_url( 'https://jordachewd.com' ).'" target="blank">'.__('JordacheWD', $this->textdomain).'</a></p>';
				}
				/* Get Tipso Script */
				$output .= $this->display->get_tipso_script( 
					array( 
						'tipso_id'		=> esc_attr( $atts['id'] ), 
						'tipso_bg'		=> $mb_tooltip_bg, 
						'tipso_txt'		=> $mb_tooltip_color,
						'tipso_ttl'		=> $mb_tooltip_title,
						'tipso_ttl_bg'	=> $mb_tooltip_title_bg
					)
				);	
				$output .= '</div>';
			}
		} else {
			$output .= '<div class="jt__error">'.__('<b>Error:</b> The ID parameter of <code>[jwd_team]</code> is missing!', $this->textdomain).'</div>';
		}
		$output .= ob_get_clean();
		return $output;
	}
}