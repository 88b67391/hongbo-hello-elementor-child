<?php
/**
 * 从 WordPress 更新列表中排除 Elementor / Elementor Pro（避免误升 4.x 等）。
 * 路径与站点插件目录下文件夹一致；若 Pro 主文件不同，可用过滤器追加。
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return list<string>
 */
function hello_elementor_child_blocked_plugin_update_paths() {
	$paths = [
		'elementor/elementor.php',
		'elementor-pro/elementor-pro.php',
	];
	// 少数环境 Pro 主文件为 plugin.php。
	if ( file_exists( WP_PLUGIN_DIR . '/elementor-pro/plugin.php' ) ) {
		$paths[] = 'elementor-pro/plugin.php';
	}
	$paths = array_unique( $paths );

	return apply_filters( 'hello_elementor_child_blocked_plugin_updates', $paths );
}

/**
 * @param object|false $transient Value from get_site_transient( 'update_plugins' ).
 * @return object|false
 */
function hello_elementor_child_strip_blocked_plugin_updates( $transient ) {
	if ( ! is_object( $transient ) || empty( $transient->response ) || ! is_array( $transient->response ) ) {
		return $transient;
	}
	foreach ( hello_elementor_child_blocked_plugin_update_paths() as $plugin_file ) {
		unset( $transient->response[ $plugin_file ] );
	}
	return $transient;
}

add_filter( 'site_transient_update_plugins', 'hello_elementor_child_strip_blocked_plugin_updates', 20 );

/**
 * 禁止这两项被自动更新（即使别处开启了插件自动更新）。
 *
 * @param bool|null $update Whether to update.
 * @param object    $item   Plugin update offer.
 * @return bool|null
 */
function hello_elementor_child_disable_auto_update_for_elementor( $update, $item ) {
	if ( ! is_object( $item ) || empty( $item->plugin ) ) {
		return $update;
	}
	if ( in_array( $item->plugin, hello_elementor_child_blocked_plugin_update_paths(), true ) ) {
		return false;
	}
	return $update;
}

add_filter( 'auto_update_plugin', 'hello_elementor_child_disable_auto_update_for_elementor', 20, 2 );
