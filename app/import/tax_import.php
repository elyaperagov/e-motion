<?php

ini_set('max_execution_time', 900);
set_time_limit(900);

require_once($_SERVER['DOCUMENT_ROOT'].'/wp/wp-load.php');

function insert_cities_terms($file){
	$delimiter = ';';

	$csv = file_get_contents(dirname(__FILE__) . $file);
	$rows = explode(PHP_EOL, $csv);
	$data = [];

	$args = array(
		'taxonomy' => 'cities',
		'hide_empty' => false,
	);
	$terms = get_terms( $args );

	$terms_exist = array();

	foreach ($terms as $key => $value) {
		//wp_delete_term( $value->term_id, 'city' );
		$terms_exist[$value->term_id] = $value->name;
	}

	// echo '<pre>';
	//  print_r($terms_exist);
	// echo '</pre>';

	foreach ($rows as $row) {
	  $data[] = explode($delimiter, $row);
	}

	// echo '<pre>';
	// print_r($data);
	// echo '</pre>';

	foreach ($data as $key => $value) {
		$term_name = str_replace(array('"', '«', '»', "'"), array('', '', ''), $value[0]);

		$country  = (int)$value[1];

		if (!in_array($term_name, $terms_exist)) {
			$res = wp_insert_term(
			$term_name,
				'cities',
				array(
					'description' => '',
					'slug'        => str_replace(array(' ', '-'), array('_', '_'), $value[0]),
					'parent'      => 0
				)
			);
			if( !is_wp_error($res) ) {
				update_field( 'country', $country, 'cities_' . $res['term_id'] );
			}
		}
	}
}

// insert_cities_terms('/cities_kaz.csv');
// insert_cities_terms('/cities_rus.csv');
// insert_cities_terms('/cities_bel.csv');
// insert_cities_terms('/cities_kir.csv');
?>