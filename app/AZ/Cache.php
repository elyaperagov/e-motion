<?

namespace AZ;

use MatthiasMullie\Minify;

class Cache
{
  public static function minifyAssets()
  {
    global $wp_scripts, $wp_styles;

    $page = str_replace([get_template_directory(), '/', '.php'], '', get_page_template());
    $page = ($page) ? $page : 'main';
    $dir = get_template_directory() . '/minify';
    $uri_minify = get_template_directory_uri() . '/minify';

    $name_js = 'script_' . $page . '.js';
    $name_css = 'style_' . $page . '.css';
    $path_js = $dir . '/' . $name_js;
    $path_css = $dir . '/' . $name_css;
    $uri_js = $uri_minify . '/' . $name_js;
    $uri_css = $uri_minify . '/' . $name_css;

    if (!is_dir($dir)) {
      mkdir($dir);
    }

    $scripts = [];
    $styles = [];

    foreach ($wp_scripts->queue as $script) {
      $src = $wp_scripts->registered[$script]->src;
      $path = str_replace(get_template_directory_uri(), get_template_directory(), $src);

      if (!file_exists($path)) {
        $path = str_replace(WP_PLUGIN_URL, WP_PLUGIN_DIR, $src);
      }

      if (!file_exists($path)) {
        continue;
      }

      $scripts[] =  $path;

      wp_dequeue_script($script);
    }

    foreach ($wp_styles->queue as $style) {
      $src = $wp_styles->registered[$style]->src;
      $path = str_replace(get_template_directory_uri(), get_template_directory(), $src);

      if (!file_exists($path)) {
        $path = str_replace(WP_PLUGIN_URL, WP_PLUGIN_DIR, $src);
      }

      if (!file_exists($path)) {
        continue;
      }

      $styles[] =  $path;

      wp_dequeue_style($style);
    }

    if (!file_exists($path_js)) {
      $minifierJS = new Minify\JS($scripts);
      $minifierJS->minify($path_js);
    }

    if (!file_exists($path_css)) {
      $minifierCSS = new Minify\CSS($styles);
      $minifierCSS->minify($path_css);
    }

    $time_js = filemtime($path_js);
    $time_css = filemtime($path_css);

    wp_enqueue_script('minified-script', $uri_js, array(), $time_js, true);
    wp_enqueue_style('minified-style', $uri_css, array(), $time_css);
  }

  public static function deleteCache()
  {
    if (!isset($_GET['delete-minified'])) {
      return;
    }

    global $wpdb;

    $dir = get_template_directory() . '/minify';

    if (!is_dir($dir)) {
      mkdir($dir);
    }

    $files = scandir($dir);
    unset($files[0], $files[1]);
    $files = array_values($files);

    foreach ($files as $key => $file) {
      $path = $dir . '/' . $file;
      unlink($path);
    }

    $loader = new \Timber\Loader();
    $loader->clear_cache_timber();
    $loader->clear_cache_twig();

    $query = $wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name LIKE '%s'", '_transient_timber_%');
    $wpdb->query($query);

    $uri = str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);

    wp_redirect($uri);
  }

  public static function adminBarMenu($wp_admin_bar)
  {
    $wp_admin_bar->add_menu(array(
      'id'    => 'delete-cache',
      'title' => 'Очистить кеш',
      'href'  => '?delete-minified=true',
    ));
  }
}
