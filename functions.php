<?php

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */

if (!class_exists('Timber')) {
	add_action('admin_notices', function () {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url(admin_url('plugins.php#timber')) . '">' . esc_url(admin_url('plugins.php')) . '</a></p></div>';
	});

	add_filter('template_include', function ($template) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});
	return;
}

Timber::$dirname = ['templates', 'views'];

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;

class StarterSite extends Timber\Site
{

	private $controllers = [
		'404',
		'archive',
		'attachment',
		'author',
		'category',
		'date',
		'embed',
		'frontpage',
		'home',
		'index',
		'page',
		'paged',
		'search',
		'single',
		'singular',
		'tag',
		'taxonomy',
	];

	public function __construct()
	{
		add_action('init', [$this, 'extend']);
		$this->define_vars();
		$this->filters();
		$this->actions();
		$this->requires();
		parent::__construct();
	}

	public function extend()
	{
		AZ\Session::start();
		AZ\Cache::deleteCache();
		$this->themeSupport();
		$this->registerPostTypes();
		$this->registerTaxonomies();
		$this->registerMenus();
		$this->addUserRoles();
		$this->addImagesSizes();
		$this->addToACF();
		$this->getSitemap();
		$this->getInstances();
		$this->hideBar();
	}

	/**
	 * Add all filters here
	 */
	public function filters()
	{
		add_filter('timber_context', [$this, 'addToContext']);
		add_filter('acf/settings/show_admin', [$this, 'hideACFAdmin']);
		add_filter('wpcf7_autop_or_not', '__return_false');
		add_filter('upload_mimes', [$this, 'addMimeTypes']);
		add_filter('big_image_size_threshold', '__return_zero');

		foreach ($this->controllers as $controller) {
			add_filter("{$controller}_template_hierarchy", [$this, 'locateControllers']);
		}
	}

	/**
	 * Add all actions here
	 */
	public function actions()
	{
		add_action('wp_enqueue_scripts', [$this, 'enqueueJquery'], 1);
		add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
		add_action('wp_enqueue_scripts', [$this, 'enqueueStyles']);
		add_action('wp_enqueue_scripts', ['AZ\\Cache', 'minifyAssets']);
		add_action('admin_bar_menu', ['AZ\\Cache', 'adminBarMenu'], 200);
		add_action('customize_register', ['AZ\\Settings', 'registerThemeCustomizer']);
		add_action('login_enqueue_scripts', ['AZ\\Settings', 'updateLoginLogo']);
		add_action('admin_head', ['AZ\\Settings', 'adminCss']);
		add_action('rest_api_init', ['AZ\\Api\\Routes', 'getInstance']);
		add_action('admin_notices', ['AZ\\Admin', 'showNotice']);

		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('admin_print_scripts', 'print_emoji_detection_script');
		remove_action('wp_print_styles', 'print_emoji_styles');
		remove_action('admin_print_styles', 'print_emoji_styles');
		remove_action('wp_head', 'noindex', 1);
		remove_action('wp_head', '_wp_render_title_tag', 1);
	}

	/**
	 * Define theme vars
	 */
	public function define_vars()
	{
		define('EM_DOMAIN', 'EM_DOMAIN');
		define('EM_VERSION', '1.1');
	}

	/**
	 * Add required files
	 */
	public function requires()
	{
		require_once __DIR__ . '/vendor/autoload.php';
	}

	/**
	 * Use this method to add theme support
	 */
	public function themeSupport()
	{
		add_theme_support('automatic-feed-links');
		add_theme_support('title-tag');
		add_theme_support('post-thumbnails');
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			)
		);
		add_theme_support('menus');
	}

	public function registerPostTypes()
	{
		require __DIR__ . '/app/PostTypes.php';
	}

	public function registerTaxonomies()
	{
		require __DIR__ . '/app/Taxonomies.php';
	}

	public function registerMenus()
	{
		require __DIR__ . '/app/Menus.php';
	}

	public function addUserRoles()
	{
		require __DIR__ . '/app/UserRoles.php';
	}

	/**
	 * Use this method to add variables to the context
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function addToContext($context)
	{
		$context['site'] = $this;

		$context['menu'] = new Timber\Menu('main-menu');
		$context['footer_menu'] = new Timber\Menu('footer-menu');
		$context['mobile_menu'] = new Timber\Menu('mobile-menu');
		$context['left_menu'] = new Timber\Menu('left-menu');
		$context['right_menu'] = new Timber\Menu('right-menu');

		$context['current_url'] = get_permalink();
		$context['canonical'] = wp_get_canonical_url();

		$context['theme_settings'] = get_theme_mods();
		$context['theme_settings_json'] = json_encode($context['theme_settings']);

		// Site-wide Settings
		if (function_exists('get_field')) {
			$context['site_settings'] = get_fields('option');
			$context['site_settings']['theme'] = $context['theme'];
			$context['site_settings_json'] = json_encode($context['site_settings']);
		}

		$context['lang_data'] = AZ\Language::getData();
		$context['is_bot'] = AZ\Helpers::isBot();

		return $context;
	}

	/**
	 * Use this method to register custom image sizes
	 */
	public function addImagesSizes()
	{
		// add_image_size( 'hero', 3000, 9999 );
	}

	/**
	 * Use this method to add to Advanced Custom Fields
	 */
	public function addToACF()
	{
		if (function_exists('acf_add_options_page')) {

			acf_add_options_page([
				'page_title' 	=> 'General Settings',
				'menu_title'	=> 'Site Settings',
				'menu_slug' 	=> 'site-general-settings',
				'capability'	=> 'edit_posts',
				'redirect'		=> false
			]);
		}
	}

	public function enqueueStyles()
	{
		wp_enqueue_style('poppins', 'https://fonts.googleapis.com/css?family=Poppins:100,300,400,400i,700,700i', array(), '');
		wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), EM_VERSION);
		wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/fonts/fontawesome/css/all.css', array(), EM_VERSION);
		wp_enqueue_style('fancybox', get_template_directory_uri() . '/assets/css/jquery.fancybox.min.css', array(), EM_VERSION);
		wp_enqueue_style('vue-select', get_template_directory_uri() . '/assets/css/vue-select.css', array(), EM_VERSION);
		wp_enqueue_style('fullpage', get_template_directory_uri() . '/assets/css/fullpage.css', array(), EM_VERSION);
		wp_enqueue_style('main-style', get_template_directory_uri() . '/assets/css/style.css', array(), EM_VERSION);
	}

	public function enqueueJquery()
	{
		wp_deregister_script('jquery');
		wp_enqueue_script('jquery', get_template_directory_uri() . '/assets/js/jquery.min.js', array(), '3.4.0', false);
	}

	public function enqueueScripts()
	{
		wp_enqueue_script('vue', get_template_directory_uri() . '/assets/js/vue.js', array(), EM_VERSION, true);
		// wp_enqueue_script('vue-min', get_template_directory_uri() . '/assets/js/vue.min.js', array(), EM_VERSION, true);
		wp_enqueue_script('axios', get_template_directory_uri() . '/assets/js/axios.min.js', array(), EM_VERSION, true);
		wp_enqueue_script('vue-select', get_template_directory_uri() . '/assets/js/vue-select.js', array(), EM_VERSION, true);
		wp_enqueue_script('vue-the-mask', get_template_directory_uri() . '/assets/js/vue-the-mask.js', array(), EM_VERSION, true);
		wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array(), EM_VERSION, true);
		wp_enqueue_script('fancybox', get_template_directory_uri() . '/assets/js/jquery.fancybox.min.js', array(), EM_VERSION, true);
		wp_enqueue_script('typed', get_template_directory_uri() . '/assets/js/typed.min.js', array(), EM_VERSION, true);
		wp_enqueue_script('scroll', get_template_directory_uri() . '/assets/js/scroll.js', array(), EM_VERSION, true);
		wp_enqueue_script('fullpage', get_template_directory_uri() . '/assets/js/fullpage.js', array(), EM_VERSION, true);
		wp_enqueue_script('common-js', get_template_directory_uri() . '/assets/js/common.js', array(), EM_VERSION, true);
		if (is_front_page()) {
			wp_enqueue_script('modeles', get_template_directory_uri() . '/assets/js/modeles.js', array(), EM_VERSION, true);
		} else {
			wp_enqueue_script('app', get_template_directory_uri() . '/assets/js/app.js', array(), EM_VERSION, true);
		}
	}

	public function hideACFAdmin()
	{
		// get the current site url
		$site_url = get_bloginfo('url');

		// an array of protected site urls
		$protected_urls = [
			'https://example.com',
			'https://staging.example.com'
		];

		// check if the current site url is in the protected urls array
		if (in_array($site_url, $protected_urls)) {
			// hide the acf menu item
			return false;
		} else {
			// show the acf menu item
			return true;
		}
	}

	public function hideBar()
	{
		if (env('WP_ENV') !== 'production') {
			show_admin_bar(false);
		}
	}

	public function addMimeTypes($mime_types)
	{
		$mime_types['csv'] = 'text/csv';
		$mime_types['svg'] = 'text/plain'; // image/svg+xml
		return $mime_types;
	}

	public function getSitemap()
	{
		$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

		if ($path === '/sitemap.xml') {
			require_once __DIR__ . '/controllers/sitemap.php';
			die;
		}
	}

	public function locateControllers(array $templates)
	{
		array_walk($templates, function (&$template) {
			$template = 'controllers/' . $template;
		});
		return $templates;
	}
}

new StarterSite();
