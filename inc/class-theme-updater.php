<?php
/**
 * Child theme updater via GitHub Releases.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Hello_Elementor_Child_Theme_Updater {

	const TRANSIENT_KEY = 'hello_child_gh_release_v1';

	/** @var self|null */
	private static $instance = null;

	/**
	 * @return self
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_filter( 'pre_set_site_transient_update_themes', [ $this, 'inject_update' ] );
		add_filter( 'site_transient_update_themes', [ $this, 'inject_update' ] );
		add_filter( 'upgrader_pre_download', [ $this, 'authenticate_download' ], 10, 3 );
	}

	/**
	 * @return string
	 */
	public static function repo() {
		if ( defined( 'HELLO_CHILD_GITHUB_REPO' ) && is_string( HELLO_CHILD_GITHUB_REPO ) && '' !== HELLO_CHILD_GITHUB_REPO ) {
			return HELLO_CHILD_GITHUB_REPO;
		}
		$v = get_option( 'hello_child_github_repo', '' );
		return is_string( $v ) ? trim( $v ) : '';
	}

	/**
	 * @return string
	 */
	public static function token() {
		if ( defined( 'HELLO_CHILD_GITHUB_TOKEN' ) && is_string( HELLO_CHILD_GITHUB_TOKEN ) && '' !== HELLO_CHILD_GITHUB_TOKEN ) {
			return HELLO_CHILD_GITHUB_TOKEN;
		}
		return '';
	}

	/**
	 * @return string
	 */
	public static function stylesheet() {
		return get_stylesheet();
	}

	/**
	 * @param bool $force Skip cache.
	 * @return array<string,string>|null
	 */
	public function get_release( $force = false ) {
		if ( ! $force ) {
			$cached = get_transient( self::TRANSIENT_KEY );
			if ( is_array( $cached ) ) {
				return $cached;
			}
		}

		$repo = self::repo();
		if ( '' === $repo || false === strpos( $repo, '/' ) ) {
			return null;
		}

		$headers = [
			'Accept'     => 'application/vnd.github+json',
			'User-Agent' => 'hello-elementor-child-updater',
		];
		$token = self::token();
		if ( '' !== $token ) {
			$headers['Authorization'] = 'Bearer ' . $token;
		}

		$response = wp_remote_get(
			'https://api.github.com/repos/' . $repo . '/releases/latest',
			[ 'timeout' => 15, 'headers' => $headers ]
		);
		if ( is_wp_error( $response ) ) {
			return null;
		}
		if ( 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			return null;
		}
		$json = json_decode( (string) wp_remote_retrieve_body( $response ), true );
		if ( ! is_array( $json ) || empty( $json['tag_name'] ) ) {
			return null;
		}

		$zip = '';
		if ( ! empty( $json['assets'] ) && is_array( $json['assets'] ) ) {
			foreach ( $json['assets'] as $asset ) {
				$name = isset( $asset['name'] ) ? (string) $asset['name'] : '';
				$url  = isset( $asset['browser_download_url'] ) ? (string) $asset['browser_download_url'] : '';
				if ( '' !== $url && preg_match( '/\.zip$/i', $name ) ) {
					$zip = $url;
					break;
				}
			}
		}
		if ( '' === $zip && ! empty( $json['zipball_url'] ) ) {
			$zip = (string) $json['zipball_url'];
		}

		$info = [
			'version'  => ltrim( (string) $json['tag_name'], 'vV' ),
			'zip_url'  => $zip,
			'homepage' => isset( $json['html_url'] ) ? (string) $json['html_url'] : ( 'https://github.com/' . $repo ),
		];
		set_transient( self::TRANSIENT_KEY, $info, 12 * HOUR_IN_SECONDS );
		return $info;
	}

	/**
	 * Clear update cache.
	 */
	public static function purge_cache() {
		delete_transient( self::TRANSIENT_KEY );
		delete_site_transient( 'update_themes' );
	}

	/**
	 * @param object|false $transient Theme update transient.
	 * @return object|false
	 */
	public function inject_update( $transient ) {
		if ( ! is_object( $transient ) ) {
			return $transient;
		}

		$info = $this->get_release();
		if ( ! $info || empty( $info['version'] ) || empty( $info['zip_url'] ) ) {
			return $transient;
		}

		$stylesheet = self::stylesheet();
		$theme      = wp_get_theme( $stylesheet );
		$current    = (string) $theme->get( 'Version' );

		if ( version_compare( $info['version'], $current, '>' ) ) {
			if ( ! isset( $transient->response ) || ! is_array( $transient->response ) ) {
				$transient->response = [];
			}
			$transient->response[ $stylesheet ] = [
				'theme'       => $stylesheet,
				'new_version' => $info['version'],
				'url'         => $info['homepage'],
				'package'     => $info['zip_url'],
			];
		}

		return $transient;
	}

	/**
	 * @param bool|string|\WP_Error $reply    Default false.
	 * @param string                $package  Package URL.
	 * @param \WP_Upgrader          $upgrader Upgrader.
	 * @return bool|string|\WP_Error
	 */
	public function authenticate_download( $reply, $package, $upgrader ) {
		$token = self::token();
		if ( '' === $token ) {
			return $reply;
		}
		if ( ! is_string( $package ) || ( false === strpos( $package, 'api.github.com' ) && false === strpos( $package, 'github.com' ) ) ) {
			return $reply;
		}
		$repo = self::repo();
		if ( '' === $repo || false === strpos( $package, $repo ) ) {
			return $reply;
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';
		return download_url(
			$package,
			300,
			false,
			[
				'Authorization' => 'Bearer ' . $token,
				'User-Agent'    => 'hello-elementor-child-updater',
				'Accept'        => 'application/octet-stream',
			]
		);
	}
}
