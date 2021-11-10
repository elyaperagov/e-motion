<?php

namespace AZ;

class Seo
{
  public static function getMeta(&$context)
  {
    if (!empty($context['site_settings']['meta_title'])) {
      $title = $context['post']->post_title . ' | ' . $context['site_settings']['meta_title'];
    } else {
      $title = $context['post']->post_title;
    }

    $description = $context['site_settings']['meta_description'];
    $keywords = $context['site_settings']['meta_keywords'];

    if (isset($context['post']->fields['meta_title']) && !empty($context['post']->fields['meta_title'])) {
      $title = $context['post']->fields['meta_title'];
    }
    if (isset($context['post']->fields['meta_description']) && !empty($context['post']->fields['meta_description'])) {
      $description = $context['post']->fields['meta_description'];
    }
    if (isset($context['post']->fields['meta_keywords']) && !empty($context['post']->fields['meta_keywords'])) {
      $keywords = $context['post']->fields['meta_keywords'];
    }

    if (empty($description)) {
      $description = $context['post']->post_title;
    }
    if (empty($keywords)) {
      $keywords = $context['post']->post_title;
    }

    $context['site_settings']['meta_title'] = $title;
    $context['site_settings']['meta_description'] = $description;
    $context['site_settings']['meta_keywords'] = $keywords;
  }
}
