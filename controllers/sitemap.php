<?php

$context = Timber::context();

$pages = [];

$posts = get_posts(array(
  'posts_per_page' => -1,
  'post_type' => ['page', 'post'],
  'post_status' => 'publish'
));

if (!empty($posts)) {
  foreach ($posts as $key => $post) {
    $page['url'] = get_permalink($post->ID);
    $page['changefreq'] = 'weekly';
    $page['priority'] = 0.80;
    $page['post_modified'] = date('c', strtotime($post->post_modified));

    $pages[] = $page;
  }
}

foreach ($pages as $key => &$page) {
  $page['url'] = htmlentities($page['url']);
}

uasort($pages, function ($a, $b) {
  return ($a['url'] > $b['url']) ? 1 : 0;
});

$context['pages'] = $pages;

header('Content-Type: application/xml');

Timber::render(array('sitemap.twig'), $context);
