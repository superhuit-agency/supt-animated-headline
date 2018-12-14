<?php
/**
 * Plugin Name:		SUPT Animated headline
 * Description: 	A simple animated headline shortcode. Simplyfied and based on https://wordpress.org/plugins/animated-headline/
 * Version:			1.0.0
 * Author URI:		https://profiles.wordpress.org/kuuak
 * License:			GPL-2.0+
 * License URI:		http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:		suah
 * Domain Path:		/languages
 *
 * SUPT Animated headline is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * SUPT Animated headline is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package		SUPT_Animated_Headline
 * @author		Kuuak
 * @license		GPL-2.0+
 * @link		https://github.com/Kuuak/supt-animated-headline
 */

/* Prevent loading this file directly */
defined( 'ABSPATH' ) || exit;

/**
 * Add default options on plugin activation.
 * @since 1.1
 */
function supt_animated_headline_activate() {}
register_activation_hook( __FILE__, 'supt_animated_headline_activate' );


if ( !class_exists( 'SUPT_Animated_Headline' ) ) {

	/**
	 * Class SUPT_Animated_Headline
	 * @since 1.0.0
	 */
	class SUPT_Animated_Headline {

		/**
		 * The current version of the plugin.
		 *
		 * @since		1.0.0
		 * @access		protected
		 * @var			string	$version	The current version of the plugin.
		 */
		protected $version;

		/**
		 * The directory path of the plugin.
		 *
		 * @since		1.0.0
		 * @access		protected
		 * @var			string	$dir_path	The directory path of the plugin.
		 */
		protected $dir_path;

		/**
		 * The directory URI of the plugin.
		 *
		 * @since		1.0.0
		 * @access		protected
		 * @var			string	$dir_uri	The directory URI of the plugin.
		 */
		protected $dir_uri;

		/**
		 * Class Constructor.
		 *
		 * @since		1.0.0
		 */
		public function __construct() {

			$this->version			= '1.0.0';
			$this->plugin_name		= 'supt-animated-headline';
			$this->dir_path			= trailingslashit( plugin_dir_path( __FILE__ ) );
			$this->dir_uri			= trailingslashit( plugin_dir_url(  __FILE__ ) );

			$this->load_dependencies();
			$this->register_public_hooks();
		}

		/**
		 * Init the plugin functions
		 *
		 * @since		1.0.0
		 */
		private function load_dependencies() {}

		/**
		 * Register all of the hooks related to the public-facing functionality
		 * of the plugin.
		 *
		 * @since		1.0.0
		 */
		private function register_public_hooks() {
			add_action( 'init', array($this, 'add_shortcodes') );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets') );
		}

		/**
		 * Register the shortcode
		 *
		 * @since		1.0.0
		 */
		public function add_shortcodes() {
			add_shortcode( 'supt-animated-headline', array($this, 'render_shortcode') );
		}

		/**
		 * Transform the shortcode into javascript function call.
		 *
		 * @since		1.0.0
		 *
		 * @param		array		$atts		Shortcode attributes.
		 * @return		string					Rendered HTML.
		 */
		public function render_shortcode( $atts ) {
			extract( shortcode_atts(
				array(
					'title'			=> null,
					'animated_text'	=> null,
					'heading'		=> 1,
					'classes'		=> '',
				), $atts )
			);

			// Do not render anything if animated text is empty
			if ( count($texts = explode(',', $animated_text)) == 0 ) {
				return '';
			}
			
			$lines = '';
			forEach( $texts as $index => $line ) {
				$cls = ( ($index == 0) ? ' is-active' : '' );
				$lines .= sprintf( '<span class="suah__line%s">%s</span>', $cls, $line );
			}

			$title = ( empty($title) ? '' : "<span class=\"suah__title\">$title</span>" );

			$tple  = '<h%s class="suah%s">';
			$tple .= 	'%s'; // Title
			$tple .= 	'%s'; // Lines
			$tple .= '</h%s>';
			return sprintf( $tple, $heading, " $classes", $title, $lines, $heading );
		}

		/**
		 * Enqueuing js/css files
		 *
		 * @since		1.0.0
		 */
		public function enqueue_assets( ) {
			wp_enqueue_script( 'suah_js', $this->dir_uri . 'js/suah'. (WP_DEBUG ? '.min' : '') .'.js', NULL, $this->version );
			wp_enqueue_style( 'suah_style', $this->dir_uri . 'css/suah'. (WP_DEBUG ? '.min' : '') .'.css', NULL, $this->version );
		}

		/**
		 * The name of the plugin used to uniquely identify it within the context of
		 * WordPress and to define internationalization functionality.
		 *
		 * @since		1.0.0
		 * @return		string	The name of the plugin.
		 */
		public function get_plugin_name() {
			return $this->plugin_name;
		}

		/**
		 * Retrieve the version number of the plugin.
		 *
		 * @since		1.0.0
		 * @return		string	The version number of the plugin.
		 */
		public function get_version() {
			return $this->version;
		}
	}
}

new SUPT_Animated_Headline();
