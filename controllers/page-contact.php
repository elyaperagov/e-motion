<?php

/**
 * Template Name: Contact
 */

$context = Timber::context();

$timber_post = new Timber\Post();
$context['post'] = $timber_post;

$context['post']->fields = get_fields($context['post']->ID);

$context['model_types'] = [];

$context['models'] = get_posts(array(
  'numberposts' => 10,
  'orderby'     => 'menu_order',
  'order'       => 'ASC',
  'post_type'   => 'models'
));
foreach ($context['models'] as $key => $model) {
  $context['models'][$key]->fields = get_fields($model->ID);

  foreach ($context['models'][$key]->fields['model_types'] as $key_t => $model_type) {
    $context['model_types'][] = [
      'name' => $model->post_title . ' ' . $model_type['model_title'],
      'slug' => $model_type['model_slug'],
      'short_slug' => $model_type['model_short_slug'],
      'code' => $model_type['product_code'],
      'price' => $model_type['product_price'],
      'price_preorder' => $model_type['product_price_preorder'],
    ];
  }
}

$context['model_types_json'] = json_encode($context['model_types']);

Timber::render(array('page-' . $timber_post->post_name . '.twig', 'page-contact.twig'), $context);
