<?php

namespace AZ\Api;

class Routes
{
  private static $instance;

  function __construct()
  {
    register_rest_route('api/order', '/post/', [
      'methods' => 'POST',
      'callback' => ['AZ\\Api\\Order', 'post']
    ]);

    register_rest_route('api/user', '/post/', [
      'methods' => 'POST',
      'callback' => ['AZ\\Api\\User', 'post']
    ]);

    register_rest_route('api/models', '/get/', [
      'methods' => 'GET',
      'callback' => ['AZ\\Api\\Models', 'get']
    ]);

    register_rest_route('api/cities', '/get/', [
      'methods' => 'GET',
      'callback' => ['AZ\\Api\\Cities', 'get']
    ]);

    register_rest_route('api/countries', '/get/', [
      'methods' => 'GET',
      'callback' => ['AZ\\Api\\Countries', 'get']
    ]);
  }

  public static function getInstance()
  {
    if (is_null(self::$instance)) {
      self::$instance = new Routes();
    }

    return self::$instance;
  }
}
