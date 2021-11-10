<?php

/**
 * Template Name: Homepage
 */

$context = Timber::context();

$timber_post = new Timber\Post();
$context['post'] = $timber_post;

$context['models'] = get_posts(array(
    'numberposts' => 10,
    'orderby'     => 'menu_order',
    'order'       => 'ASC',
    'post_type'   => 'models'
));
foreach ($context['models'] as $key => $value) {
    $context['models'][$key]->fields = get_fields($value->ID);

    foreach ($context['models'][$key]->fields['model_types'] as $key_1 => &$value_1) {
        if ($value_1['product_price_preorder'] && $value_1['product_price']) {
            $value_1['product_price_percent'] = ' ' . round(100 - ($value_1['product_price_preorder'] / $value_1['product_price']) * 100);
        } else {
            $value_1['product_price_percent'] = '';
        }

        if (is_array($value_1['typed_text'])) {
            foreach ($value_1['typed_text'] as $key_2 => $value_2) {
                $typed_text_array[$key][$key_1][] = $value_2['typed_text'];
            }
            $context['models'][$key]->fields['model_types'][$key_1]['typed_text_json'] = json_encode($typed_text_array[$key][$key_1]);
        }
    }
}

Timber::render(array('page-' . $timber_post->post_name . '.twig', 'page-home.twig'), $context);
