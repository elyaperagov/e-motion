<?php
/**
* Add arguments and register_post_type function here.
* They are hooked into WordPress in functions.php.
*/

$labels = array(
	'name'                  => 'Form requests',
	'singular_name'         => 'Form request',
	'menu_name'             => 'Form requests',
	'name_admin_bar'        => 'Form requests',
	'archives'              => '',
	'attributes'            => 'Request Attributes',
	'parent_item_colon'     => '',
	'all_items'             => 'All Requests',
	'add_new_item'          => 'Add New Request',
	'add_new'               => 'Add New',
	'new_item'              => 'New Request',
	'edit_item'             => 'Edit Request',
	'update_item'           => 'Update Request',
	'view_item'             => 'View Request',
	'view_items'            => 'View Requests',
	'search_items'          => 'Search Request',
	'not_found'             => 'Not found',
	'not_found_in_trash'    => 'Not found in Trash',
	'featured_image'        => 'Featured Image',
	'set_featured_image'    => 'Set featured image',
	'remove_featured_image' => 'Remove featured image',
	'use_featured_image'    => 'Use as featured image',
	'insert_into_item'      => 'Insert into Request',
	'uploaded_to_this_item' => 'Uploaded to this Request',
	'items_list'            => 'Requests list',
	'items_list_navigation' => 'Requests list navigation',
	'filter_items_list'     => 'Filter Requests list',
);
$rewrite = array(
	'slug'                => 'requests',
	'with_front'          => false,
	'pages'               => true,
	'feeds'               => true,
);
$args = array(
	'label'                 => 'Request',
	'description'           => 'Requests',
	'labels'                => $labels,
	'supports'              => array('title', 'editor'),
	'hierarchical'          => false,
	'public'                => false,
	'show_ui'               => true,
	'show_in_menu'          => true,
	'menu_position'         => 5,
	'menu_icon'             => 'dashicons-welcome-widgets-menus',
	'show_in_admin_bar'     => true,
	'show_in_nav_menus'     => true,
	'can_export'            => true,
	'has_archive'           => false,
	'exclude_from_search'   => true,
	'publicly_queryable'    => false,
	'rewrite'								=> $rewrite,
	'capability_type'       => 'page',
);
register_post_type('form_requests', $args);

$labels = array(
	'name'                  => 'Projects',
	'singular_name'         => 'Project',
	'menu_name'             => 'Projects',
	'name_admin_bar'        => 'Project',
	'archives'              => '',
	'attributes'            => 'Project Attributes',
	'parent_item_colon'     => '',
	'all_items'             => 'All Projects',
	'add_new_item'          => 'Add New Project',
	'add_new'               => 'Add New',
	'new_item'              => 'New Project',
	'edit_item'             => 'Edit Project',
	'update_item'           => 'Update Project',
	'view_item'             => 'View Project',
	'view_items'            => 'View Projects',
	'search_items'          => 'Search Project',
	'not_found'             => 'Not found',
	'not_found_in_trash'    => 'Not found in Trash',
	'featured_image'        => 'Featured Image',
	'set_featured_image'    => 'Set featured image',
	'remove_featured_image' => 'Remove featured image',
	'use_featured_image'    => 'Use as featured image',
	'insert_into_item'      => 'Insert into Project',
	'uploaded_to_this_item' => 'Uploaded to this Project',
	'items_list'            => 'Projects list',
	'items_list_navigation' => 'Projects list navigation',
	'filter_items_list'     => 'Filter Projects list',
);
$rewrite = array(
	'slug'                => 'projects',
	'with_front'          => true,
	'pages'               => true,
	'feeds'               => true,
);
$args = array(
	'label'                 => 'Project',
	'description'           => 'Projects',
	'labels'                => $labels,
	'supports'              => array( 'title', 'editor' ),
	'hierarchical'          => false,
	'public'                => true,
	'show_ui'               => true,
	'show_in_menu'          => true,
	'menu_position'         => 5,
	'menu_icon'             => 'dashicons-building',
	'show_in_admin_bar'     => true,
	'show_in_nav_menus'     => true,
	'can_export'            => true,
	'has_archive'           => false,
	'exclude_from_search'   => true,
	'publicly_queryable'    => true,
	'rewrite'				=> $rewrite,
	'capability_type'       => 'page',
);
// register_post_type( 'projects', $args );

$labels = array(
	'name'                  => 'Models',
	'singular_name'         => 'Model',
	'menu_name'             => 'Models',
	'name_admin_bar'        => 'Model',
	'archives'              => '',
	'attributes'            => 'Model Attributes',
	'parent_item_colon'     => '',
	'all_items'             => 'All Models',
	'add_new_item'          => 'Add New Model',
	'add_new'               => 'Add New',
	'new_item'              => 'New Model',
	'edit_item'             => 'Edit Model',
	'update_item'           => 'Update Model',
	'view_item'             => 'View Model',
	'view_items'            => 'View Models',
	'search_items'          => 'Search Model',
	'not_found'             => 'Not found',
	'not_found_in_trash'    => 'Not found in Trash',
	'featured_image'        => 'Featured Image',
	'set_featured_image'    => 'Set featured image',
	'remove_featured_image' => 'Remove featured image',
	'use_featured_image'    => 'Use as featured image',
	'insert_into_item'      => 'Insert into Model',
	'uploaded_to_this_item' => 'Uploaded to this Model',
	'items_list'            => 'Models list',
	'items_list_navigation' => 'Models list navigation',
	'filter_items_list'     => 'Filter Models list',
);
$rewrite = array(
	'slug'                => 'models',
	'with_front'          => true,
	'pages'               => true,
	'feeds'               => true,
);
$args = array(
	'label'                 => 'Model',
	'description'           => 'Models',
	'labels'                => $labels,
	'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions', 'page-attributes'),
	'hierarchical'          => true,
	'public'                => true,
	'show_ui'               => true,
	'show_in_menu'          => true,
	'menu_position'         => 5,
	'menu_icon'             => 'dashicons-admin-customizer',
	'show_in_admin_bar'     => true,
	'show_in_nav_menus'     => true,
	'can_export'            => true,
	'has_archive'           => true,
	'exclude_from_search'   => true,
	'publicly_queryable'    => true,
	'rewrite'				=> $rewrite,
	'capability_type'       => 'post',
);
register_post_type( 'models', $args );