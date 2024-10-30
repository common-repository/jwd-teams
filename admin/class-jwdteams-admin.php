<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    		JWD_Teams
 * @subpackage 		JWD_Teams/admin
 * @author     		JordacheWD <office@jordachewd.ro>
 */
class JWD_Teams_Admin {
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
	 * The admin specific hooks of this plugin.
	 *
	 * @since    1.3.5
	 * @access   protected
	 * @var      string    $display    Retrive admin specific hooks of this plugin.
	 */
	protected $adminHooks;
	/**
	 * Initialize the Admin Enqueue Scripts class and set its properties.
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
	 * Load the required dependencies for Admin Side.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - JWD_Teams_Admin_Hooks. Provide a admin area view for the plugin.
	 *
	 * @since    1.3.5
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible with admin specific hooks of this plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/class-admin-hooks.php';
		$this->adminHooks = new JWD_Teams_Admin_Hooks($this->fullname, $this->textdomain, $this->version, $this->hooks);
	}
	/**
	 * Register the Stylesheets & Scripts for the admin area.
	 *
	 * @since    1.3.5
	 */
	public function enqueue_admin() {
		global $post_type;
		if( $post_type == 'jwd_team' ){
			wp_enqueue_media();
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( $this->textdomain . '-style', plugin_dir_url( __FILE__ ) . 'css/jwdteams-admin.min.css', array(), $this->version, 'all' );
			if ( is_rtl() ) { wp_enqueue_style( $this->textdomain . '-style-rtl', plugin_dir_url( __FILE__ ) . 'css/jwdteams-admin-rtl.min.css', array(), $this->version, 'all' ); }
			wp_enqueue_style( $this->textdomain . '-fontawesome', 'https://use.fontawesome.com/releases/v5.3.1/css/all.css', array(), $this->version, 'all' );
			wp_enqueue_script($this->textdomain . '-script', plugin_dir_url( __FILE__ ) . 'js/jwdteams-admin.min.js', array('jquery', 'jquery-ui-sortable', 'wp-color-picker', 'jquery-ui-slider'), $this->version, false );
		}
		wp_enqueue_style( $this->textdomain . '-mce', plugin_dir_url( __FILE__ ) . 'css/jwdteams-mce.min.css', array(), $this->version, 'all' );
		wp_localize_script( $this->textdomain . '-script', 'jt__getTeams', 
			array( 
				'ajax_url' 			=> admin_url( 'admin-ajax.php' ),
				'reset_nonce' 		=> wp_create_nonce( 'resetTeamSettings_nonce' ),
				'mceNonce' 			=> wp_create_nonce( 'mceButton_nonce' ),
				'imgFrameTitle'		=> __('Choose Member Image', $this->textdomain),
				'imgBtnText'		=> __('Use This Image', $this->textdomain),
				'confirmMsg'		=> __('Are you sure you want to REMOVE this item?', $this->textdomain),
				'confirmReset'		=> __('Are you sure you want to RESET all settings for this team to the default values?', $this->textdomain),
				'afterReset'		=> __('<b>The settings are now restored to default.</b> Please UPDATE this team to apply the default settings!', $this->textdomain),
				'mceEmptyMsg'		=> sprintf( __('There are no Team Showcases registered yet!<br />Please go to %s to add your first Team Showcase.', $this->textdomain), '<a href="'.admin_url('edit.php?post_type=jwd_team').'" style="font-weight:bold;">' . __('JWD Teams', $this->textdomain) . '</a>' ),
				'mceLabel'			=> __('Select Team', $this->textdomain),
				'mceTitle'			=> __('Add Team', $this->textdomain)
			)
		);
	}
	/**
	 * Register plugin's custom post types
	 *
	 * @since    1.3.5
	 */
	public function register_cpt() {
		$labels = array(
			'name'               => __( 'All Teams', $this->textdomain ),
			'singular_name'      => __( 'Team', $this->textdomain ),
			'add_new'            => __( 'Add New Team', $this->textdomain ),
			'add_new_item'       => __( 'Add New Team', $this->textdomain ),
			'edit_item'          => __( 'Edit Team', $this->textdomain ),
			'new_item'           => __( 'New Team', $this->textdomain ),
			'all_items'          => __( 'All Teams', $this->textdomain ),
			'view_item'          => __( 'View Team', $this->textdomain ),
			'search_items'       => __( 'Search Team', $this->textdomain ),
			'not_found'          => __( 'No Teams found', $this->textdomain ),
			'not_found_in_trash' => __( 'No Teams found in the Trash', $this->textdomain ), 
			'parent_item_colon'  => '',
			'menu_name'          => __( 'JWD Teams', $this->textdomain )
		);
		$args = array(
			'labels'        		=> $labels,
			'description'   		=> __( 'Holds Team Members specific data', $this->textdomain ),
			'public'        		=> true,
			'menu_position' 		=> 999,
			'supports'      		=> array( 'title' ),
			'exclude_from_search' 	=> true,
			'menu_icon'   			=> 'dashicons-id-alt',
		);
		register_post_type( 'jwd_team', $args );
	}
	/**
	 * Team ShortCode in Teams Panel :: Column Head
	 *
	 * @since    1.3.5
	 */
	public function column_head($defaults) {
		$defaults['jwdtm_shortcode'] = __('Shortcode', $this->textdomain);
		return $defaults;
	}
	/**
	 * Team ShortCode in Teams Panel :: Column Content
	 *
	 * @since    1.3.5
	 */
	public function column_content($column_name, $post_ID) {
		if ($column_name == 'jwdtm_shortcode') { echo '<code>[jwd_team id="'.$post_ID.'"]</code>';}
	}
	/**
	 * Create MCE Editor Buttons
	 *
	 * @since    1.3.5
	 */
	/* Init the MCE button */
	public function mce_button() {
		if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
			add_filter('mce_external_plugins', array( $this, 'add_buttons' ));
			add_filter('mce_buttons', array( $this, 'register_buttons' ));
		}
	}	
	/* Add the button */
	public function add_buttons($plugin_array) {
		$plugin_array['jwdteams'] = plugins_url( '/js/jwdteams-mce.min.js', __FILE__  );
		return $plugin_array;
	}
	/* Register the button */
	public function register_buttons($buttons) {
		array_push( $buttons, 'addjwdteam' ); 
		return $buttons;
	}
	/**
	 * Register Metaboxes
	 *
	 * @since    1.3.5
	 */
	public function register_metaboxes() {
		$hooks = $this->hooks;
		add_meta_box( 'jwdteams_members', $hooks->get_fa_icon('fas fa-users') . '&nbsp;' . __( 'Team Members', $this->textdomain ), array( $this, 'team_members' ), 'jwd_team', 'normal', 'high' );
		add_meta_box( 'jwdteams_settings', $hooks->get_fa_icon('fas fa-cogs') . '&nbsp;' . __( 'Team Settings', $this->textdomain ), array( $this, 'team_settings' ), 'jwd_team', 'side', 'default' );
		add_meta_box( 'jwdteams_custom_css', $hooks->get_fa_icon('fab fa-css3') . '&nbsp;'.__( 'Team Custom CSS', $this->textdomain ), array( $this, 'team_custom_css' ), 'jwd_team', 'normal', 'default' );
	}
	/**
	 * Team Members Metabox
	 *
	 * @since    1.3.5
	 */
	public function team_members($post){
		/* Define Nonce */
		wp_nonce_field( plugin_basename( __FILE__ ) , 'team_members_nonce' );
		/* Define Vars */
		ob_start();
		$data = get_post_meta($post->ID, 'member_data' ,true);
		$adminHooks = $this->adminHooks;
		$output = '<div class="jt__wrapper">';
		/* Display Fields */
		$count = 0;
		if ( !empty($data) ){
			foreach((array)$data as $member ){
				if(isset($member) && !empty($member)){ 
					$output .= $adminHooks->get_member_fileds($count, $member); 
					$count++; 
				} else {
					$output .= '<p style="color:red">ERROR: Empty <b>'.$member.'</b> variable in <b>team_members()</b>! <u> '.plugin_basename(__FILE__).' </u></p>'; 
				}
			}
		}  
		$output .= '</div><span class="jt__addnew button button-primary button-hero">'. __('Add Member', $this->textdomain) . '</span>';
		$output .= ob_get_clean();
		echo $output;
		/**
		 * Add New Button Script*/
?>
		<script type="text/javascript">
		/* <![CDATA[ */
			jQuery(document).ready(function() {
				var mbID = <?php echo $count; ?>; 
				jQuery(".jt__addnew").click(function() {
					jQuery('.jt__wrapper').append(' <?php echo implode('', explode('\n', $adminHooks->get_member_fileds('mbID'))); ?> '.replace(/mbID/g, mbID) );
					mbID++;
				});
			});
		/* ]]> */
		</script>
<?php
	}
	/**
	 * Team Settings Metabox
	 *
	 * @since    1.3.5
	 */
	public function team_settings( $post ) {
		wp_nonce_field( plugin_basename( __FILE__ ) , 'team_settings_nonce' );
		ob_start();
		/* Define Vars */
		$output 			= '';
		$hooks 				= $this->hooks;
		$adminHooks 		= $this->adminHooks;
		$team_grid_style 	= ( get_post_meta( $post->ID, 'team_design', true ) == 'horizontal' ) ? 'display:none;' : 'display:inline-block;';
		$settings 			= $hooks->get_team_settings($team_grid_style);
		/* Generate Settings Args */
		foreach ($settings as $option => $option_args ){
			$optval = get_post_meta( $post->ID, $option, true ); 
			if( !$optval && isset($option_args['default'])){ $optval = $option_args['default']; }
			switch($option_args['type']){
				case 'radio': 
					foreach ($option_args['choices'] as $choice => $choice_args){
						$option_args['choices'][$choice]['checked'] = checked( $optval, $choice_args['value'], false ); 
					}
				break;
				case 'checkbox': $option_args['checked'] = checked( $optval, $option_args['value'], false ); break;
				default: $option_args['value'] = esc_attr( $optval );
			}
			${$option . '_args'} = $option_args;
		}
		/* Output TOP section */
		$output .= '<p style="margin-top:1.75em;"><code style="color:#f00;">[jwd_team id="'.$post->ID.'"]</code></p>';
		$output .= '<p style="margin-bottom:1.5em;">'.__('Use this shortcode above to display this team in any post or page.',$this->textdomain).'</p>';
		/* Output TABS Selector */
		$output .= '<ul class="jt__tabs"><li class="active">' . __('Settings', $this->textdomain) . '</li><li>' . __('Colors', $this->textdomain) . '</li><li>' . __('Sizes', $this->textdomain) . '</li></ul>';
		$output .= '<ul class="jt__tab">';
		/* Output SETTINGS Tab */
		$output .= '<li class="active">';
		$output .= $adminHooks->get_settings_filed('team_design', $team_design_args, null, null, null, __( 'Layout format', $this->textdomain ) );
		$output .= $adminHooks->get_settings_filed('team_settings_grid', $team_settings_grid_args, 'team_settings_grid', null, null, __( 'Grid' , $this->textdomain ) . ' <i style="font-weight:normal;font-size:90%;">(' . __('items per row', $this->textdomain ) . ')</i>');
		$output .= $adminHooks->get_settings_filed('team_settings_img', $team_settings_img_args, null, null, null, __( 'Image Shape', $this->textdomain ) );
		$output .= $adminHooks->get_settings_filed('team_settings_desc', $team_settings_desc_args , 'team_settings_align', null, null, __( 'Display description as', $this->textdomain ) );
		$output .= $adminHooks->get_settings_filed('team_txt_align', $team_txt_align_args, null, null, null, __( 'Text align', $this->textdomain ) );
		$output .= $adminHooks->get_settings_filed('team_desc_justify', $team_desc_justify_args, null, 'margin-top:1em;' );
		$output .= '<h4>' . __( 'Other settings', $this->textdomain ) . '</h4>';
		$output .= $adminHooks->get_settings_filed('team_more_kill', $team_more_kill_args );
		$output .= $adminHooks->get_settings_filed('team_more_desc', $team_more_desc_args ) . '<br /><br /><br />';
		$output .= $adminHooks->get_settings_filed('team_settings_title', $team_settings_title_args );
		$output .= $adminHooks->get_settings_filed('team_hide_icons', $team_hide_icons_args );
		$output .= $adminHooks->get_settings_filed('team_settings_credit', $team_settings_credit_args );
		$output .= '</li>';
		/* Output COLORS Tab */
		$output .= '<li>';
		$output .= $adminHooks->get_settings_filed('mb_name_color', $mb_name_color_args, null, null, 'far fa-user', __( 'Name', $this->textdomain ) );
		$output .= $adminHooks->get_settings_filed('mb_position_color', $mb_position_color_args, null, null, 'far fa-building', __( 'Position', $this->textdomain ) );
		$output .= $adminHooks->get_settings_filed('mb_contact_color', $mb_contact_color_args, null, null, 'far fa-envelope', __( 'Phone & Email', $this->textdomain ) );
		$output .= $adminHooks->get_settings_filed('mb_social_color', $mb_social_color_args, null, null, 'far fa-share-square', __( 'Social Icons', $this->textdomain ));
		$output .= $adminHooks->get_settings_filed('mb_social_hover', $mb_social_hover_args, null, null, 'fas fa-share-square', __( 'Social Icons Hover', $this->textdomain ) );
		$output .= $adminHooks->get_settings_filed('mb_desc_color', $mb_desc_color_args, null, null, 'far fa-address-card', __( 'Description', $this->textdomain ));
		$output .= $adminHooks->get_settings_filed('mb_tooltip_title', $mb_tooltip_title_args, null, null, 'far fa-id-card', __( 'Tooltip Title', $this->textdomain ));
		$output .= $adminHooks->get_settings_filed('mb_tooltip_title_bg', $mb_tooltip_title_bg_args, null, null, 'far fa-id-card', __( 'Tooltip Title Background', $this->textdomain ));
		$output .= $adminHooks->get_settings_filed('mb_tooltip_color', $mb_tooltip_color_args, null, null, 'far fa-comment-dots', __( 'Tooltip Text', $this->textdomain ));
		$output .= $adminHooks->get_settings_filed('mb_tooltip_bg', $mb_tooltip_bg_args, null, null, 'fas fa-comment-dots', __( 'Tooltip Text Background', $this->textdomain ));
		$output .= '</li>';
		/* Output SIZES Tab */
		$output .= '<li>';
		$output .= $adminHooks->get_settings_filed('mb_name_size', $mb_name_size_args, null, null, 'far fa-user',  __( 'Name', $this->textdomain ) . ' <i style="font-size:90%;font-weight:normal;">(px)</i>');
		$output .= $adminHooks->get_settings_filed('mb_position_size', $mb_position_size_args, null, null, 'far fa-building', __( 'Position', $this->textdomain ) . ' <i style="font-size:90%;font-weight:normal;">(px)</i>');
		$output .= $adminHooks->get_settings_filed('mb_contact_size', $mb_contact_size_args, null, null, 'far fa-envelope', __( 'Phone & Email', $this->textdomain ) . ' <i style="font-size:90%;font-weight:normal;">(px)</i>');
		$output .= $adminHooks->get_settings_filed('mb_social_size', $mb_social_size_args, null, null, 'far fa-share-square', __( 'Social Icons', $this->textdomain ) . ' <i style="font-size:90%;font-weight:normal;">(px)</i>');
		$output .= $adminHooks->get_settings_filed('mb_desc_size', $mb_desc_size_args, null, null, 'far fa-address-card', __( 'Description', $this->textdomain ) . ' <i style="font-size:90%;font-weight:normal;">(px)</i>');
		$output .= $adminHooks->get_settings_filed('mb_desc_leng', $mb_desc_leng_args, null, null, 'far fa-address-card', __( 'Description Length', $this->textdomain ) . ' <i style="font-size:90%;font-weight:normal;">('.__('characters',$this->textdomain).')</i>' );
		$output .= '</li></ul>';
		$output .= '<br /><span class="jt__resetTeamSettings button button-small" >'.__('Restore to Default', $this->textdomain).'</span>';
		$output .= '<span class="resetTeamSettings_spinner spinner" style="float:none;"></span>';
		$output .= ob_get_clean();
		echo $output;
	}
	/**
	 * Team Custom CSS Metabox
	 *
	 * @since    1.3.5
	 */
	public function team_custom_css($post){
		wp_nonce_field( plugin_basename( __FILE__ ) , 'team_custom_css_nonce' );
		ob_start();
		$custom_css = get_post_meta( $post->ID, 'team_custom_css', true );
		echo $this->adminHooks->get_customcss_box($post->ID, $custom_css) . ob_get_clean();
	}
	/**
	 * Save Team Metaboxes
	 *
	 * @since    1.3.5
	 */
	public function save_team($post_id){
		/* Don't save if the user hasn't submitted the changes */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
		/* Verify that the input is coming from the proper form */
		if ( !isset( $_POST['team_members_nonce'] ) || !wp_verify_nonce( $_POST['team_members_nonce'], plugin_basename( __FILE__ ) ) ) { return; }  
		if ( !isset( $_POST['team_settings_nonce'] ) || !wp_verify_nonce( $_POST['team_settings_nonce'], plugin_basename( __FILE__ ) ) ) { return; }  
		if ( !isset( $_POST['team_custom_css_nonce'] ) || !wp_verify_nonce( $_POST['team_custom_css_nonce'], plugin_basename( __FILE__ ) ) ) { return; }  
		/* Vars */
		$hooks = $this->hooks;
		$settings = $hooks->get_team_settings();
		/* Let's go now */
		if ( 'jwd_team' == $_POST['post_type'] ) {
			/* Make sure the user has permissions to this post */
			if ( !current_user_can( 'edit_page', $post_id ) ) { return; }
			/* Members Data */
			if ( isset($_POST['member_data']) ){ update_post_meta($post_id,'member_data', $_POST['member_data']); } else { delete_post_meta($post_id, 'member_data'); }
			/* Custom CSS */
			if ( isset($_POST['team_custom_css']) ){ update_post_meta($post_id,'team_custom_css', $_POST['team_custom_css']); }
			/* Update Settings */
			foreach ($settings as $option => $option_args ){
				switch($option_args['type']){
					case 'checkbox': if ( isset($_POST[$option]) ){ update_post_meta($post_id, $option, $_POST[$option]); } else { delete_post_meta($post_id, $option); } break;
					case 'text': if ( isset($_POST[$option]) && !empty( $_POST[$option] )){ update_post_meta($post_id, $option, sanitize_text_field( $_POST[$option] ) ); } break;
					default: if ( isset($_POST[$option]) ){ update_post_meta($post_id, $option, $_POST[$option]); }
				}
			}
		}
	} 
	/**
	 * Custom Admin Footer
	 *
	 * @since    1.3.5
	 */
	public function custom_admin_footer($text) {
		$post_type = filter_input(INPUT_GET, 'post_type');
		if (! $post_type){ $post_type = get_post_type(filter_input(INPUT_GET, 'post')); }
		if ('jwd_team' == $post_type){ return $this->adminHooks->get_rating_box(); }
		return $text;
	}
	/**
	 * Custom Admin version in Footer
	 *
	 * @since    1.3.5
	 */
	public function custom_admin_version($text) {
		$post_type = filter_input(INPUT_GET, 'post_type');
		if (! $post_type){ $post_type = get_post_type(filter_input(INPUT_GET, 'post')); }
		if ('jwd_team' == $post_type){ 
			return $this->fullname . ' v' . $this->version . '&nbsp;' . sprintf ( __('%1$s %2$s',$this->textdomain), __('Powered by', $this->textdomain) ,'<a href="'.esc_url( 'https://jordachewd.com' ).'" target="blank">'.__('JordacheWD', $this->textdomain).'</a>'); 
		}
		return $text;
	}
	/**
	 * Add "Get Started" link in plugin's description. 
	 * Visible on Plugins admin section.
	 *
	 * @since    1.3.5
	 */
	public function add_action_links( $links ) {
	   $links[] = '<a href="'. esc_url( get_admin_url( null, 'edit.php?post_type=jwd_team' ) ) .'">' . __('Get Started', $this->textdomain) . '</a>';
	   return $links;
	}
	/**
	 * Set up the options link to the WP Top Bar.
	 * Available only if current user can manageoptions (Is Admin).
	 *
	 * @since    1.4
	 */
	public function admin_menubar( $wp_admin_bar ) {
		if( current_user_can( 'manage_options' )){
			$args = array( 
				'id' 		=> $this->textdomain . '_adminmenubar', 
				'parent'	=> 'appearance', 
				'title' 	=> $this->fullname, 
				'href' 		=> get_admin_url() .'edit.php?post_type=jwd_team', 
			);
			$wp_admin_bar->add_node( $args );
		}
	} 
}