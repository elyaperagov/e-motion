<?php

namespace AZ;

class Helpers
{
  public static function isBot()
  {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    if (empty($user_agent)) {
      return false;
    }

    $bots = [
      // Yandex
      'YandexBot', 'YandexAccessibilityBot', 'YandexMobileBot', 'YandexDirectDyn', 'YandexScreenshotBot',
      'YandexImages', 'YandexVideo', 'YandexVideoParser', 'YandexMedia', 'YandexBlogs', 'YandexFavicons',
      'YandexWebmaster', 'YandexPagechecker', 'YandexImageResizer', 'YandexAdNet', 'YandexDirect',
      'YaDirectFetcher', 'YandexCalendar', 'YandexSitelinks', 'YandexMetrika', 'YandexNews',
      'YandexNewslinks', 'YandexCatalog', 'YandexAntivirus', 'YandexMarket', 'YandexVertis',
      'YandexForDomain', 'YandexSpravBot', 'YandexSearchShop', 'YandexMedianaBot', 'YandexOntoDB',
      'YandexOntoDBAPI', 'YandexTurbo', 'YandexVerticals',

      // Google
      'Googlebot', 'Googlebot-Image', 'Mediapartners-Google', 'AdsBot-Google', 'APIs-Google',
      'AdsBot-Google-Mobile', 'AdsBot-Google-Mobile', 'Googlebot-News', 'Googlebot-Video',
      'AdsBot-Google-Mobile-Apps', 'Mediapartner-Google', "facebookexternalhit/", "Facebot", "Lighthouse",
      "Page Speed", "PageSpeed", "Speed Insights",

      // Other
      'Mail.RU_Bot', 'bingbot', 'Accoona', 'ia_archiver', 'Ask Jeeves', 'OmniExplorer_Bot', 'W3C_Validator',
      'WebAlta', 'YahooFeedSeeker', 'Yahoo!', 'Ezooms', 'Tourlentabot', 'MJ12bot', 'AhrefsBot',
      'SearchBot', 'SiteStatus', 'Nigma.ru', 'Baiduspider', 'Statsbot', 'SISTRIX', 'AcoonBot', 'findlinks',
      'proximic', 'OpenindexSpider', 'statdom.ru', 'Exabot', 'Spider', 'SeznamBot', 'oBot', 'C-T bot',
      'Updownerbot', 'Snoopy', 'heritrix', 'Yeti', 'DomainVader', 'DCPbot', 'PaperLiBot', 'StackRambler',
      'msnbot', 'msnbot-media', 'msnbot-news', 'XYZbot',
    ];

    foreach ($bots as $bot) {
      if (stripos($user_agent, $bot) !== false) {
        return true;
      }
    }

    return false;
  }

  public static function insertFile($file)
  {
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    $filetype = wp_check_filetype($file);

    if ($filetype['ext'] === false) {
      return false;
    }

    $tmp = download_url($file);

    $file_array = [
      'name'     => basename($file),
      'tmp_name' => $tmp,
      'error'    => 0,
      'size'     => filesize($tmp),
    ];

    $file_id = media_handle_sideload($file_array, 0);

    @unlink($tmp);

    if (is_wp_error($file_id)) {
      @unlink($file_array['tmp_name']);
      echo $file_id->get_error_messages();

      return false;
    }

    $output = [
      'id' => $file_id,
      'path' => get_attached_file($file_id)
    ];

    return $output;
  }

  public static function captchaCheck($recaptcha_response)
  {
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = '';

    $recaptcha_json = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha_result = json_decode($recaptcha_json);

    $response['recaptcha_json'] = $recaptcha_json;
    $response['success'] = $recaptcha_result->score < 0.5 ? false : true;

    return $response;
  }

  public static function objectToArray($obj)
  {
    if (is_object($obj) || is_array($obj)) {
      $ret = (array) $obj;
      foreach ($ret as &$item) {
        $item = self::objectToArray($item);
      }
      return $ret;
    } else {
      return $obj;
    }
  }
}
