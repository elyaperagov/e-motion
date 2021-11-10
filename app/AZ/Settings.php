<?php

namespace AZ;

class Settings
{
	/**
	 * All settings in customizer go here
	 *
	 * @package Wordpress
	 * @param object $wp_customize
	 * @since   1.0
	 */
	public static function registerThemeCustomizer($wp_customize)
	{

		$wp_customize->add_section('new_section_general', array(
			'title'       => 'General Options',
			'priority'    => 1,
		));

		$wp_customize->add_setting('site_favicon', array(
			'sanitize_callback' => 'esc_url_raw',
		));

		$wp_customize->add_setting('site_logo', array(
			'sanitize_callback' => 'esc_url_raw'
		));

		$wp_customize->add_setting("phone", array(
			"default" => __("", T_DOMAIN),
			"transport" => "postMessage",
		));

		$wp_customize->add_setting("address", array(
			"default" => __("", T_DOMAIN),
			"transport" => "postMessage",
		));

		$wp_customize->add_setting("coordinates", array(
			"default" => __("", T_DOMAIN),
			"transport" => "postMessage",
		));

		$wp_customize->add_setting("fb_url", array(
			"default" => __("", T_DOMAIN),
			"transport" => "postMessage",
		));

		$wp_customize->add_setting("vk_url", array(
			"default" => __("", T_DOMAIN),
			"transport" => "postMessage",
		));

		$wp_customize->add_setting("instagram_url", array(
			"default" => __("", T_DOMAIN),
			"transport" => "postMessage",
		));

		/*= Add Control
	--------------------------------------------------------------------*/
		$wp_customize->add_control(new \WP_Customize_Image_Control(
			$wp_customize,
			'upload_favicon',
			array(
				'label'    => 'Upload Favicon',
				'section'  => 'new_section_general',
				'settings' => 'site_favicon',
				'priority' => 1
			)
		));

		$wp_customize->add_control(new \WP_Customize_Image_Control(
			$wp_customize,
			'upload_logo',
			array(
				'label'    => 'Upload Logo',
				'section'  => 'new_section_general',
				'settings' => 'site_logo',
				'priority' => 5
			)
		));

		$wp_customize->add_control(new \WP_Customize_Control(
			$wp_customize,
			"phone",
			array(
				"description" => __("Phone number", T_DOMAIN),
				"section" => "new_section_general",
				"settings" => "phone",
				'type' => 'text'
			)
		));

		$wp_customize->add_control(new \WP_Customize_Control(
			$wp_customize,
			"address",
			array(
				"label" => __("Address", T_DOMAIN),
				"section" => "new_section_general",
				"settings" => "address",
				"type" => "text",
			)
		));

		$wp_customize->add_control(new \WP_Customize_Control(
			$wp_customize,
			"coordinates",
			array(
				"label" => __("Coordinates", T_DOMAIN),
				"section" => "new_section_general",
				"settings" => "coordinates",
				"type" => "text",
			)
		));

		$wp_customize->add_control(new \WP_Customize_Control(
			$wp_customize,
			"fb_url",
			array(
				"label" => __("Facebook url", T_DOMAIN),
				"section" => "new_section_general",
				"settings" => "fb_url",
				"type" => "text",
			)
		));

		$wp_customize->add_control(new \WP_Customize_Control(
			$wp_customize,
			"vk_url",
			array(
				"label" => __("VK url", T_DOMAIN),
				"section" => "new_section_general",
				"settings" => "vk_url",
				"type" => "text",
			)
		));

		$wp_customize->add_control(new \WP_Customize_Control(
			$wp_customize,
			"instagram_url",
			array(
				"label" => __("Instagram url", T_DOMAIN),
				"section" => "new_section_general",
				"settings" => "instagram_url",
				"type" => "text",
			)
		));
	}

	public static function updateLoginLogo()
	{
		echo '<style type="text/css">
			#login h1 a,
			.login h1 a {
				background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/logo.png);
				background-size: 90%;
				width: XXXpx;
				height: XXXpx;
				margin: 0 auto;
			}
		</style>';
	}

	public static	function adminCss()
	{
		echo '<style>
			#edittag {
				max-width: 80%
			}
		</style>';
	}
}
