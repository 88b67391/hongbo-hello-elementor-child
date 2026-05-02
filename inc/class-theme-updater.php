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

	/** @since 1.1.6 升级 key，丢弃旧缓存并改用 site transient。 */
	const TRANSIENT_KEY = 'hello_child_gh_release_v2';
	const LEGACY_TRANSIENT_KEY = 'hello_child_gh_release_v1';

	const DEFAULT_REPO = '88b67391/hongbo-hello-elementor-child';

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
		add_action( 'load-themes.php', [ $this, 'force_check_on_themes_screen' ] );
	}

	/**
	 * 在主题页强制刷新：清除 GitHub 缓存 + update_themes，否则仅删后者仍会命中 12h transient。
	 */
	public function force_check_on_themes_screen() {
		if ( ! current_user_can( 'update_themes' ) ) {
			return;
		}
		$flag = 'hello_child_forced_theme_check';
		if ( isset( $_GET[ $flag ] ) ) {
			return;
		}
		self::purge_cache();
		if ( ! function_exists( 'wp_update_themes' ) ) {
			require_once ABSPATH . 'wp-includes/update.php';
		}
		wp_update_themes();
	}

	/**
	 * @return string
	 */
	public static function repo() {
		if ( defined( 'HELLO_CHILD_GITHUB_REPO' ) && is_string( HELLO_CHILD_GITHUB_REPO ) && '' !== HELLO_CHILD_GITHUB_REPO ) {
			return HELLO_CHILD_GITHUB_REPO;
		}
		$v = get_option( 'hello_child_github_repo', '' );
		$v = is_string( $v ) ? trim( $v ) : '';
		return '' !== $v ? $v : self::DEFAULT_REPO;
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
			$cached = get_site_transient( self::TRANSIENT_KEY );
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

		$candidates = [];
		$release_json = null;

		$response = wp_remote_get(
			'https://api.github.com/repos/' . $repo . '/releases/latest',
			[
				'timeout' => 15,
				'headers' => $headers,
			]
		);
		if ( ! is_wp_error( $response ) && 200 === (int) wp_remote_retrieve_response_code( $response ) ) {
			$release_json = json_decode( (string) wp_remote_retrieve_body( $response ), true );
			if ( is_array( $release_json ) && ! empty( $release_json['tag_name'] ) && is_string( $release_json['tag_name'] ) ) {
				$tag = trim( (string) $release_json['tag_name'] );
				$ver = $this->tag_to_version( $tag );
				if ( '' !== $ver ) {
					$candidates[ $tag ] = [
						'tag'          => $tag,
						'version'      => $ver,
						'release_json' => $release_json,
					];
				}
			}
		}

		$response = wp_remote_get(
			'https://api.github.com/repos/' . $repo . '/tags?per_page=100',
			[
				'timeout' => 15,
				'headers' => $headers,
			]
		);
		if ( ! is_wp_error( $response ) && 200 === (int) wp_remote_retrieve_response_code( $response ) ) {
			$tags = json_decode( (string) wp_remote_retrieve_body( $response ), true );
			if ( is_array( $tags ) ) {
				foreach ( $tags as $row ) {
					if ( empty( $row['name'] ) || ! is_string( $row['name'] ) ) {
						continue;
					}
					$tag = trim( $row['name'] );
					$ver = $this->tag_to_version( $tag );
					if ( '' === $ver ) {
						continue;
					}
					if ( ! isset( $candidates[ $tag ] ) ) {
						$candidates[ $tag ] = [
							'tag'          => $tag,
							'version'      => $ver,
							'release_json' => null,
						];
					}
				}
			}
		}

		if ( empty( $candidates ) ) {
			$tag = $this->get_latest_tag_from_redirect( $repo );
			if ( '' !== $tag ) {
				$ver = $this->tag_to_version( $tag );
				if ( '' !== $ver ) {
					$candidates[ $tag ] = [
						'tag'          => $tag,
						'version'      => $ver,
						'release_json' => null,
					];
				}
			}
		}

		if ( empty( $candidates ) ) {
			return null;
		}

		$best = null;
		foreach ( $candidates as $row ) {
			if ( null === $best || version_compare( $row['version'], $best['version'], '>' ) ) {
				$best = $row;
			}
		}

		if ( null === $best ) {
			return null;
		}

		$tag   = $best['tag'];
		$rjson = $best['release_json'];
		$zip   = $this->resolve_zip_url( $repo, $tag, $token, is_array( $rjson ) ? $rjson : null );

		if ( '' === $zip ) {
			return null;
		}

		$info = [
			'version'  => $best['version'],
			'zip_url'  => $zip,
			'homepage' => 'https://github.com/' . $repo . '/releases/tag/' . rawurlencode( $tag ),
		];
		set_site_transient( self::TRANSIENT_KEY, $info, 12 * HOUR_IN_SECONDS );
		return $info;
	}

	/**
	 * @param string $tag Tag name e.g. v1.1.5.
	 * @return string Normalized version for version_compare, empty if unusable.
	 */
	private function tag_to_version( $tag ) {
		$tag = trim( (string) $tag );
		if ( '' === $tag ) {
			return '';
		}
		$v = ltrim( $tag, 'vV' );
		return $v;
	}

	/**
	 * @param string               $repo         owner/repo.
	 * @param string               $tag          Exact tag on GitHub.
	 * @param string               $token        GitHub token or ''.
	 * @param array<string,mixed>|null $release_json Parsed releases/latest JSON when tag matches.
	 * @return string
	 */
	private function resolve_zip_url( $repo, $tag, $token, $release_json ) {
		if ( '' !== $token ) {
			return 'https://api.github.com/repos/' . $repo . '/zipball/' . rawurlencode( $tag );
		}

		if ( is_array( $release_json ) && isset( $release_json['tag_name'] ) && (string) $release_json['tag_name'] === $tag ) {
			if ( ! empty( $release_json['assets'] ) && is_array( $release_json['assets'] ) ) {
				foreach ( $release_json['assets'] as $asset ) {
					$name = isset( $asset['name'] ) ? (string) $asset['name'] : '';
					$url  = isset( $asset['browser_download_url'] ) ? (string) $asset['browser_download_url'] : '';
					if ( '' !== $url && preg_match( '/\.zip$/i', $name ) ) {
						return $url;
					}
				}
			}
			if ( ! empty( $release_json['zipball_url'] ) ) {
				return (string) $release_json['zipball_url'];
			}
		}

		return 'https://api.github.com/repos/' . $repo . '/zipball/' . rawurlencode( $tag );
	}

	/**
	 * 通过 releases/latest 的 302 跳转提取 tag（API 不可用时兜底）。
	 *
	 * @param string $repo owner/repo.
	 * @return string
	 */
	private function get_latest_tag_from_redirect( $repo ) {
		$url      = 'https://github.com/' . $repo . '/releases/latest';
		$response = wp_remote_head(
			$url,
			[
				'timeout'     => 15,
				'redirection' => 0,
				'headers'     => [
					'User-Agent' => 'hello-elementor-child-updater',
				],
			]
		);
		if ( is_wp_error( $response ) ) {
			return '';
		}
		$code = (int) wp_remote_retrieve_response_code( $response );
		if ( 301 !== $code && 302 !== $code && 303 !== $code && 307 !== $code && 308 !== $code ) {
			return '';
		}
		$location = wp_remote_retrieve_header( $response, 'location' );
		if ( ! is_string( $location ) || '' === $location ) {
			return '';
		}
		if ( preg_match( '#/releases/tag/([^/?#]+)#', $location, $m ) ) {
			return trim( (string) $m[1] );
		}
		return '';
	}

	/**
	 * Clear update cache.
	 */
	public static function purge_cache() {
		delete_site_transient( self::LEGACY_TRANSIENT_KEY );
		delete_site_transient( self::TRANSIENT_KEY );
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
		$tmp = download_url(
			$package,
			300,
			false,
			[
				'Authorization' => 'token ' . $token,
				'User-Agent'    => 'hello-elementor-child-updater',
				'Accept'        => 'application/octet-stream',
			]
		);
		if ( is_wp_error( $tmp ) ) {
			$tmp = download_url(
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
		return $tmp;
	}
}
