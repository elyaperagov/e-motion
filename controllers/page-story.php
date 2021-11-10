<?php
/**
 * Template Name: Story
 */

$context = Timber::context();

$timber_post = new Timber\Post();
$context['post'] = $timber_post;

$context['post']->fields = get_fields($context['post']->ID);

Timber::render( array( 'page-' . $timber_post->post_name . '.twig', 'page-story.twig' ), $context );
