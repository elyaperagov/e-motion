<?php
/**
* Add arguments and register_taxonomy function here.
* They are hooked into WordPress in functions.php.
*/

$labels = array(
	'name'                       => 'Countries',
	'singular_name'              => 'Country',
	'menu_name'                  => 'Country',
	'all_items'                  => 'All Countries',
	'parent_item'                => 'Parent Country',
	'parent_item_colon'          => 'Parent Country:',
	'new_item_name'              => 'New Country Name',
	'add_new_item'               => 'Add New Country',
	'edit_item'                  => 'Edit Country',
	'update_item'                => 'Update Country',
	'view_item'                  => 'View Country',
	'separate_items_with_commas' => 'Separate items with commas',
	'add_or_remove_items'        => 'Add or remove items',
	'choose_from_most_used'      => 'Choose from the most used',
	'popular_items'              => 'Popular Countries',
	'search_items'               => 'Search Countries',
	'not_found'                  => 'Not Found',
	'no_terms'                   => 'No items',
	'items_list'                 => 'Countries list',
	'items_list_navigation'      => 'Countries list navigation',
);
$args = array(
	'labels'                     => $labels,
	'hierarchical'               => false,
	'public'                     => false,
	'show_ui'                    => true,
	'show_admin_column'          => true,
	'show_in_nav_menus'          => true,
	'show_tagcloud'              => false,
);
register_taxonomy( 'countries', array( 'form_requests' ), $args );

$labels = array(
	'name'                       => 'Cities',
	'singular_name'              => 'City',
	'menu_name'                  => 'City',
	'all_items'                  => 'All Cities',
	'parent_item'                => 'Parent City',
	'parent_item_colon'          => 'Parent City:',
	'new_item_name'              => 'New City Name',
	'add_new_item'               => 'Add New City',
	'edit_item'                  => 'Edit City',
	'update_item'                => 'Update City',
	'view_item'                  => 'View City',
	'separate_items_with_commas' => 'Separate items with commas',
	'add_or_remove_items'        => 'Add or remove items',
	'choose_from_most_used'      => 'Choose from the most used',
	'popular_items'              => 'Popular Cities',
	'search_items'               => 'Search Cities',
	'not_found'                  => 'Not Found',
	'no_terms'                   => 'No items',
	'items_list'                 => 'Cities list',
	'items_list_navigation'      => 'Cities list navigation',
);
$args = array(
	'labels'                     => $labels,
	'hierarchical'               => false,
	'public'                     => false,
	'show_ui'                    => true,
	'show_admin_column'          => true,
	'show_in_nav_menus'          => true,
	'show_tagcloud'              => false,
);
register_taxonomy( 'cities', array( 'form_requests' ), $args );