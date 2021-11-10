<?php

namespace AZ\Api;

class Models
{
  /**
   * return models
   *
   * @param array WP_REST_Request
   * @return array $response
   **/
  public static function get(\WP_REST_Request $request)
  {
    $data = [];

    $data['models'] = self::getModels();

    $response = new \WP_REST_Response();

    $response->set_data($data);
    $response->header('Content-type', 'application/json');
    $response->set_status(200);
    $response = rest_ensure_response($response);

    return $response;

    die;
  }

  public static function getModels()
  {
    $models = \TimberHelper::transient('timberloader_models', function () {

      $models = array();

      $models = get_terms(array(
        'taxonomy'      => 'model',
        'orderby'       => 'order_clause',
        'order'         => 'ASC',
        'hide_empty'    => true,
        'fields'        => 'all',
        'meta_query'    => array(
          'relation' => 'OR',
          'order_clause' => array(
            'key'     => 'order'
          ),
          array(
            'key'     => 'order',
            'compare' => 'NOT EXISTS'
          ),
        )
      ));

      $models = array_values($models);

      return $models;
    }, 3600);

    return $models;
  }
}
