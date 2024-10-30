<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      		1.3.5
 * @package    		JWD_Teams
 * @subpackage 		JWD_Teams/includes
 * @author     		JordacheWD <office@jordachewd.ro>
 */
class JWD_Teams {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.3.5
	 * @access   protected
	 * @var      JWD_Teams_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.3.5
	 * @access   protected
	 * @var      string    $textdomain    The string used to uniquely identify this plugin.
	 */
	protected $textdomain;
	/**
	 * The Full Name of this plugin.
	 *
	 * @since    1.3.5
	 * @access   protected
	 * @var      string    $fullname    The Full Name of this plugin.
	 */
	protected $fullname;
	/**
	 * The current version of the plugin.
	 *
	 * @since    1.3.5
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
	/**
	 * The file of the plugin.
	 *
	 * @since    1.3.5
	 * @access   protected
	 * @var      string    $version    The file of the plugin.
	 */
	protected $plugin_file;
	/**
	 * The DIR of the plugin.
	 *
	 * @since    1.3.5
	 * @access   protected
	 * @var      string    $version    The DIR of the plugin.
	 */
	protected $plugin_dir;
	/**
	 * The general hooks of this plugin.
	 *
	 * @since    1.3.5
	 * @access   protected
	 * @var      JWD_Teams_Loader    $hooks    The general hooks of the plugin.
	 */
	protected $hooks;
	/**
	 * Define the core functionality of the plugin.
	 *
	 * @since    1.3.5
	 */
	public function __construct($plugin_file, $plugin_dir) {
		$this->plugin_file = $plugin_file;
		$this->plugin_dir = $plugin_dir;
		/**/
		$this->fullname = $this->get_plugin_data('Name');
		$this->textdomain = $this->get_plugin_data('TextDomain');
		$this->version = $this->get_plugin_data();
		/**/
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}
	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - JWD_Teams_Loader. Orchestrates the hooks of the plugin.
	 * - JWD_Teams_i18n. Defines internationalization functionality.
	 * - JWD_Teams_Admin. Defines all hooks for the admin area.
	 * - JWD_Teams_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.3.5
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-jwdteams-loader.php';
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-jwdteams-i18n.php';
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-jwdteams-admin.php';
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-jwdteams-public.php';
		$this->loader = new JWD_Teams_Loader();
		/**
		 * The class responsible for providing the general hooks.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-jwdteams-hooks.php';
		$this->hooks = new JWD_Teams_Hooks($this->fullname, $this->textdomain, $this->version);
	}
	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the JWD_Teams_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.3.5
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new JWD_Teams_i18n($this->textdomain);
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.3.5
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new JWD_Teams_Admin( $this->fullname, $this->textdomain, $this->version, $this->hooks );
		$plugin_hooks = new JWD_Teams_Admin_Hooks( $this->fullname, $this->textdomain, $this->version, $this->hooks );
		/* FILTERS */
		if (in_array($GLOBALS['pagenow'], array('edit.php', 'post.php', 'post-new.php'))){
			$this->loader->add_filter('admin_footer_text', $plugin_admin, 'custom_admin_footer', 9999);
			$this->loader->add_filter('update_footer', $plugin_admin, 'custom_admin_version', 9999);
		}
		$this->loader->add_filter('manage_jwd_team_posts_columns', $plugin_admin, 'column_head');
		$this->loader->add_filter('plugin_action_links_'.$this->plugin_dir.'/'.$this->plugin_file, $plugin_admin, 'add_action_links');
		/* ACTIONS */
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_admin' );
		$this->loader->add_action('init', $plugin_admin, 'register_cpt'  );
		$this->loader->add_action('init', $plugin_admin, 'mce_button' );
		$this->loader->add_action('manage_jwd_team_posts_custom_column', $plugin_admin, 'column_content' , 10, 2);
		$this->loader->add_action('add_meta_boxes', $plugin_admin, 'register_metaboxes' );
		$this->loader->add_action('save_post', $plugin_admin, 'save_team');
		$this->loader->add_action('admin_bar_menu', $plugin_admin, 'admin_menubar', 999 );
		/* AJAX Calls */
		$this->loader->add_action('wp_ajax_reset_team_settings', $plugin_hooks, 'ajaxGetDefaultSettings' );
		$this->loader->add_action('wp_ajax_mceGetTeams', $plugin_hooks, 'ajaxGetMceTeams' );
	}
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.3.5
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new JWD_Teams_Public( $this->fullname, $this->textdomain, $this->version, $this->hooks  );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'public_enqueue' );
		$this->loader->add_action( 'init', $plugin_public, 'public_shortcode' );
	}
	/**
	 * Retrieve the data of the plugin.
	 *
	 * @since     1.3.5
	 * @return    string    The requested data of the plugin.
	 */
	public function get_plugin_data($data = '') {
		if ( ! function_exists( 'get_plugins' ) ){ require_once( ABSPATH . 'wp-admin/includes/plugin.php' ); }
		$plugin_folder = get_plugins('/'. $this->plugin_dir );
		$plugin_data = $plugin_folder[$this->plugin_file];
		switch ($data){
			case 'Name': return $plugin_data['Name']; break;
			case 'Author': return $plugin_data['Author']; break;
			case 'PluginURI': return $plugin_data['PluginURI']; break;
			case 'TextDomain': return $plugin_data['TextDomain']; break;
			default: return $plugin_data['Version'];
		}
	}
	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.3.5
	 * @return    JWD_Teams_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.3.5
	 */
	public function run() {
		$this->loader->run();
	}
}