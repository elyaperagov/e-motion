<?php

namespace AZ\Api;

class Countries
{
  /**
   * return countries
   *
   * @param array WP_REST_Request
   * @return array $response
   **/
  public static function get(\WP_REST_Request $request)
  {
    $data = [];

    $data['countries'] = self::getCountries();

    $response = new \WP_REST_Response();

    $response->set_data($data);
    $response->header('Content-type', 'application/json');
    $response->set_status(200);
    $response = rest_ensure_response($response);

    return $response;

    die;
  }

  public static function getCountries()
  {
    $countries_list = \TimberHelper::transient('timberloader_countries_list', function () {

      $countries = get_terms(array(
        'taxonomy'      => 'countries',
        'orderby'       => 'name',
        'order'         => 'DESC',
        'hide_empty'    => false,
        'fields'        => 'all',
        'get'           => 'all'
      ));

      $countries_list = array();

      if (!empty($countries)) {
        foreach ($countries as $key => $country) {
          $country->fields = get_fields('term_' . $country->term_id);
          $countries_list[] = $country->name;
        }
      };

      return $countries_list;
    }, 3600);

    return $countries_list;
  }
}
