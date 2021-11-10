<?php

namespace AZ\Api;

class Cities
{
  /**
   * return cities
   *
   * @param array WP_REST_Request
   * @return array $response
   **/
  public static function get(\WP_REST_Request $request)
  {
    $data = [];

    $data['cities'] = self::getCities();

    $response = new \WP_REST_Response();

    $response->set_data($data);
    $response->header('Content-type', 'application/json');
    $response->set_status(200);
    $response = rest_ensure_response($response);

    return $response;

    die;
  }

  public static function getCities()
  {
    $cities_list = \TimberHelper::transient('timberloader_cities_list', function () {

      $cities = get_terms(array(
        'taxonomy'      => 'cities',
        'orderby'       => 'name',
        'order'         => 'ASC',
        'hide_empty'    => false,
        'fields'        => 'all',
        'get'           => 'all'
      ));

      $cities_list = array();

      if (!empty($cities)) {
        foreach ($cities as $key => $city) {
          $city->fields = get_fields('term_' . $city->term_id);
          if (isset($city->fields['country']->name)) {
            $cities_list[$city->fields['country']->name][] = $city->name;
          }
        }
      };

      return $cities_list;
    }, 3600);

    return $cities_list;
  }
}
