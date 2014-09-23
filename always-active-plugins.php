<?php
/*
Plugin Name: Force activation of provided plugins, also preventing their deactivation
Version: 0.1
Author: Weston Ruter, Jonathan Bardo <info@jonathanbardo.com>
*/

class Always_Active_Plugins {

	/**
	 * @action muplugins_loaded
	 */
	public static function setup() {
		self::activate_plugins();
		add_filter( 'plugin_action_links', array( __CLASS__, 'filter_plugin_action_links_to_disable_blog_deactivation' ), 10, 4 );
		add_filter( 'network_admin_plugin_action_links', array( __CLASS__, 'filter_plugin_action_links_to_disable_network_deactivation' ), 10, 4 );
	}

	public static function activate_plugins() {
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

		$required_plugins = apply_filters( 'auto_activated_required_plugins', array() );
		$network_required_plugins = apply_filters( 'auto_activated_network_required_plugins', array() );
		$required_plugins = array_merge( $required_plugins, $network_required_plugins );

		foreach ( $required_plugins as $plugin ) {
			$is_network = is_network_only_plugin( $plugin ) || in_array( $plugin, $network_required_plugins );
			$result     = null;
			if ( $is_network && ! is_plugin_active_for_network( $plugin ) ) {

			} else if ( ! is_plugin_active( $plugin ) ) {
				$result = activate_plugin( $plugin, '', $is_network );
			}
			if ( is_wp_error( $result ) ) {
				wp_die( sprintf( __( '%1$s: %2$s' ), $plugin, $result->get_error_message() ) );
			}
		}
	}

	/**
	 * @action plugin_action_links
	 */
	public static function filter_plugin_action_links_to_disable_blog_deactivation( $actions, $plugin_file, $plugin_data, $context ) {
		$plugins = apply_filters( 'auto_activated_required_plugins', array() );

		if ( in_array( $plugin_file, $plugins ) ) {
			unset( $actions['edit'] );
			unset( $actions['deactivate'] );
		}

		return $actions;
	}

	public static function filter_plugin_action_links_to_disable_network_deactivation( $actions, $plugin_file, $plugin_data, $context ) {
		$plugins = apply_filters( 'auto_activated_network_required_plugins', array() );
		
		if ( in_array( $plugin_file, $plugins ) ) {
			unset( $actions['edit'] );
			unset( $actions['deactivate'] );
		}
		
		return $actions;
	}

}

add_action( 'muplugins_loaded', array( 'Always_Active_Plugins', 'setup' ) );
