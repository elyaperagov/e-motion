<?php
/**
* Add locations and register_nav_menus function here.
* They are hooked into WordPress in functions.php.
*/

$locations = array(
	'left-menu'		=> 'Left Menu',
	'right-menu'	=> 'Right Menu',
	'mobile-menu'	=> 'Mobile Menu',
	'main-menu' 	=> 'Main Menu',
	'footer-menu' 	=> 'Footer Menu',
);
register_nav_menus( $locations );