<?php
/**
 * Hello Elementor Child — functions and definitions
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_stylesheet_directory() . '/inc/import-cs-products.php';
require_once get_stylesheet_directory() . '/inc/disable-elementor-updates.php';
require_once get_stylesheet_directory() . '/inc/class-theme-updater.php';
if ( is_admin() ) {
	require_once get_stylesheet_directory() . '/inc/admin-seed-products.php';
}
Hello_Elementor_Child_Theme_Updater::instance();

/**
 * 加载子主题样式表（在父主题 theme.css 之后，保证可覆盖）。
 */
function hello_elementor_child_enqueue_styles() {
	wp_enqueue_style(
		'hello-elementor-child',
		get_stylesheet_directory_uri() . '/style.css',
		[ 'hello-elementor-theme-style' ],
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_styles', 20 );

/**
 * Register assets for custom Elementor widgets (gallery + sticky contact bar).
 */
function hello_elementor_child_register_elementor_widget_assets() {
	$base_dir = get_stylesheet_directory();
	$base_uri = get_stylesheet_directory_uri();

	$css_path = $base_dir . '/assets/css/acf-product-gallery.css';
	$js_path  = $base_dir . '/assets/js/acf-product-gallery.js';
	$css_ver  = file_exists( $css_path ) ? (string) filemtime( $css_path ) : null;
	$js_ver   = file_exists( $js_path ) ? (string) filemtime( $js_path ) : null;

	wp_register_style( 'heb-acf-product-gallery', $base_uri . '/assets/css/acf-product-gallery.css', [], $css_ver );
	wp_register_script( 'heb-acf-product-gallery', $base_uri . '/assets/js/acf-product-gallery.js', [], $js_ver, true );

	$sc_css = $base_dir . '/assets/css/sticky-contact-bar.css';
	$sc_js  = $base_dir . '/assets/js/sticky-contact-bar.js';
	wp_register_style( 'heb-sticky-contact-bar', $base_uri . '/assets/css/sticky-contact-bar.css', [], file_exists( $sc_css ) ? (string) filemtime( $sc_css ) : null );
	wp_register_script( 'heb-sticky-contact-bar', $base_uri . '/assets/js/sticky-contact-bar.js', [], file_exists( $sc_js ) ? (string) filemtime( $sc_js ) : null, true );

	$faq_css = $base_dir . '/assets/css/acf-faq.css';
	wp_register_style( 'heb-acf-faq', $base_uri . '/assets/css/acf-faq.css', [], file_exists( $faq_css ) ? (string) filemtime( $faq_css ) : null );

	$bc_css = $base_dir . '/assets/css/breadcrumbs.css';
	wp_register_style( 'heb-breadcrumbs', $base_uri . '/assets/css/breadcrumbs.css', [], file_exists( $bc_css ) ? (string) filemtime( $bc_css ) : null );

	$cw_css = $base_dir . '/assets/css/content-widgets.css';
	wp_register_style( 'heb-content-widgets', $base_uri . '/assets/css/content-widgets.css', [], file_exists( $cw_css ) ? (string) filemtime( $cw_css ) : null );

	$cf_css = $base_dir . '/assets/css/acf-company-feedback.css';
	wp_register_style( 'heb-acf-company-feedback', $base_uri . '/assets/css/acf-company-feedback.css', [], file_exists( $cf_css ) ? (string) filemtime( $cf_css ) : null );
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_register_elementor_widget_assets', 15 );

/**
 * Register a dedicated Elementor category so widgets are easy to find (not only under “General / 常规”).
 */
function hello_elementor_child_register_elementor_category() {
	if ( ! class_exists( '\Elementor\Plugin' ) ) {
		return;
	}
	$em = \Elementor\Plugin::instance()->elements_manager;
	if ( ! $em || ! method_exists( $em, 'add_category' ) ) {
		return;
	}
	$em->add_category(
		'heb-child',
		[
			'title' => esc_html__( 'Hello Child · 子主题', 'hello-elementor-child' ),
			'icon'  => 'eicon-plug',
		],
		1
	);
}
add_action( 'elementor/init', 'hello_elementor_child_register_elementor_category', 5 );

/**
 * Load widget class files once.
 */
function hello_elementor_child_load_elementor_widget_classes() {
	static $loaded = false;
	if ( $loaded ) {
		return;
	}
	$loaded = true;
	require_once get_stylesheet_directory() . '/inc/elementor/class-widget-acf-product-gallery.php';
	require_once get_stylesheet_directory() . '/inc/elementor/class-widget-sticky-contact-bar.php';
	require_once get_stylesheet_directory() . '/inc/elementor/class-widget-acf-faq.php';
	require_once get_stylesheet_directory() . '/inc/elementor/class-widget-breadcrumbs.php';
	require_once get_stylesheet_directory() . '/inc/elementor/class-widget-feature-value-grid.php';
	require_once get_stylesheet_directory() . '/inc/elementor/class-widget-certification-grid.php';
	require_once get_stylesheet_directory() . '/inc/elementor/class-widget-vertical-timeline.php';
	require_once get_stylesheet_directory() . '/inc/elementor/class-widget-acf-company-intro.php';
	require_once get_stylesheet_directory() . '/inc/elementor/class-widget-acf-customer-feedback.php';
}

/**
 * Register custom Elementor widgets (Elementor 3.5+).
 *
 * @param \Elementor\Widgets_Manager $widgets_manager Widgets manager instance.
 */
function hello_elementor_child_register_elementor_widgets( $widgets_manager ) {
	hello_elementor_child_load_elementor_widget_classes();
	if ( method_exists( $widgets_manager, 'register' ) ) {
		$widgets_manager->register( new \HelloElementorChild\Elementor\Widget_ACF_Product_Gallery() );
		$widgets_manager->register( new \HelloElementorChild\Elementor\Widget_Sticky_Contact_Bar() );
		$widgets_manager->register( new \HelloElementorChild\Elementor\Widget_ACF_FAQ() );
		$widgets_manager->register( new \HelloElementorChild\Elementor\Widget_Breadcrumbs() );
		$widgets_manager->register( new \HelloElementorChild\Elementor\Widget_Feature_Value_Grid() );
		$widgets_manager->register( new \HelloElementorChild\Elementor\Widget_Certification_Grid() );
		$widgets_manager->register( new \HelloElementorChild\Elementor\Widget_Vertical_Timeline() );
		$widgets_manager->register( new \HelloElementorChild\Elementor\Widget_ACF_Company_Intro() );
		$widgets_manager->register( new \HelloElementorChild\Elementor\Widget_ACF_Customer_Feedback() );
	}
}
add_action( 'elementor/widgets/register', 'hello_elementor_child_register_elementor_widgets' );

/**
 * Older Elementor: register_widget_type when ->register() does not exist.
 */
function hello_elementor_child_register_elementor_widgets_legacy() {
	if ( ! class_exists( '\Elementor\Plugin' ) ) {
		return;
	}
	$wm = \Elementor\Plugin::instance()->widgets_manager;
	if ( method_exists( $wm, 'register' ) ) {
		return;
	}
	if ( ! method_exists( $wm, 'register_widget_type' ) ) {
		return;
	}
	if ( $wm->get_widget_types( 'heb_acf_product_gallery' ) ) {
		return;
	}
	hello_elementor_child_load_elementor_widget_classes();
	$wm->register_widget_type( new \HelloElementorChild\Elementor\Widget_ACF_Product_Gallery() );
	$wm->register_widget_type( new \HelloElementorChild\Elementor\Widget_Sticky_Contact_Bar() );
	$wm->register_widget_type( new \HelloElementorChild\Elementor\Widget_ACF_FAQ() );
	$wm->register_widget_type( new \HelloElementorChild\Elementor\Widget_Breadcrumbs() );
	$wm->register_widget_type( new \HelloElementorChild\Elementor\Widget_Feature_Value_Grid() );
	$wm->register_widget_type( new \HelloElementorChild\Elementor\Widget_Certification_Grid() );
	$wm->register_widget_type( new \HelloElementorChild\Elementor\Widget_Vertical_Timeline() );
	$wm->register_widget_type( new \HelloElementorChild\Elementor\Widget_ACF_Company_Intro() );
	$wm->register_widget_type( new \HelloElementorChild\Elementor\Widget_ACF_Customer_Feedback() );
}
add_action( 'elementor/widgets/widgets_registered', 'hello_elementor_child_register_elementor_widgets_legacy', 20 );

/**
 * Parse ACF FAQ textarea (field `faq_list`): each line is "Question|Answer".
 *
 * @param string $raw Raw field value.
 * @return array<int, array{question: string, answer: string}>
 */
function hello_elementor_child_parse_faq_list( $raw ) {
	$out = [];
	if ( ! is_string( $raw ) || '' === trim( $raw ) ) {
		return $out;
	}
	$lines = preg_split( '/\r\n|\r|\n/', $raw );
	foreach ( $lines as $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}
		$parts = explode( '|', $line, 2 );
		$q     = isset( $parts[0] ) ? trim( $parts[0] ) : '';
		$a     = isset( $parts[1] ) ? trim( $parts[1] ) : '';
		if ( '' === $q && '' === $a ) {
			continue;
		}
		$out[] = [
			'question' => $q,
			'answer'   => $a,
		];
	}
	return $out;
}

/**
 * 在模板中输出语言切换器（基于 heb-product-publisher 写入的 _heb_pp_lang_map）。
 *
 * 用法（模板文件）：
 * if ( function_exists( 'hello_elementor_child_render_language_switcher' ) ) {
 *     hello_elementor_child_render_language_switcher();
 * }
 *
 * @param int $post_id Post ID, default current post.
 */
function hello_elementor_child_render_language_switcher( $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : (int) get_the_ID();
	if ( $post_id <= 0 ) {
		return;
	}
	$map = get_post_meta( $post_id, '_heb_pp_lang_map', true );
	if ( ! is_array( $map ) || empty( $map ) ) {
		return;
	}

	$labels = [
		'en' => 'English',
		'ja' => 'Japanese',
		'fr' => 'French',
		'vi' => 'Vietnamese',
		'ko' => 'Korean',
		'ru' => 'Russian',
		'ar' => 'Arabic',
	];
	$current = strtolower( (string) get_locale() );
	$current = strpos( $current, '_' ) !== false ? explode( '_', $current )[0] : $current;
	$current = sanitize_key( $current );

	echo '<nav class="heb-lang-switcher" aria-label="Language switcher"><ul class="heb-lang-switcher__list">';
	foreach ( $map as $lang => $url ) {
		$lang = sanitize_key( (string) $lang );
		$url  = esc_url( (string) $url );
		if ( '' === $lang || '' === $url ) {
			continue;
		}
		$label = isset( $labels[ $lang ] ) ? $labels[ $lang ] : strtoupper( $lang );
		if ( $lang === $current ) {
			echo '<li class="heb-lang-switcher__item is-current"><span>' . esc_html( $label ) . '</span></li>';
		} else {
			echo '<li class="heb-lang-switcher__item"><a href="' . $url . '">' . esc_html( $label ) . '</a></li>';
		}
	}
	echo '</ul></nav>';
}

/**
 * 输出 hreflang / x-default（基于 _heb_pp_lang_map）。
 */
function hello_elementor_child_output_hreflang_links() {
	if ( ! is_singular() ) {
		return;
	}
	$post_id = (int) get_queried_object_id();
	if ( $post_id <= 0 ) {
		return;
	}
	$map = get_post_meta( $post_id, '_heb_pp_lang_map', true );
	if ( ! is_array( $map ) || empty( $map ) ) {
		return;
	}
	$clean = [];
	foreach ( $map as $lang => $url ) {
		$lang = strtolower( sanitize_key( (string) $lang ) );
		$url  = esc_url( (string) $url );
		if ( '' === $lang || '' === $url ) {
			continue;
		}
		$clean[ $lang ] = $url;
	}
	if ( empty( $clean ) ) {
		return;
	}
	foreach ( $clean as $lang => $url ) {
		echo '<link rel="alternate" hreflang="' . esc_attr( $lang ) . '" href="' . esc_url( $url ) . "\" />\n";
	}
	$x_default = '';
	if ( ! empty( $clean['en'] ) ) {
		$x_default = $clean['en'];
	} else {
		$x_default = reset( $clean );
	}
	if ( is_string( $x_default ) && '' !== $x_default ) {
		echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $x_default ) . "\" />\n";
	}
}
add_action( 'wp_head', 'hello_elementor_child_output_hreflang_links', 5 );

/**
 * 语言站点域名映射（可用 filter 覆盖）。
 *
 * @return array<string,string>
 */
function hello_elementor_child_language_sites() {
	$sites = [
		'en' => 'https://www.hongbotex.com',
		'ja' => 'https://ja.hongbotex.com',
		'fr' => 'https://fr.hongbotex.com',
		'vi' => 'https://vi.hongbotex.com',
		'ko' => 'https://ko.hongbotex.com',
		'ru' => 'https://ru.hongbotex.com',
		'ar' => 'https://ar.hongbotex.com',
	];
	return (array) apply_filters( 'hello_elementor_child_language_sites', $sites );
}

/**
 * 构建当前请求对应的全站语言跳转链接：
 * 1) 若文章有 _heb_pp_lang_map（精确映射）则优先使用；
 * 2) 否则回退为“同路径跨域名”。
 *
 * @return array<string,string>
 */
function hello_elementor_child_build_switcher_links() {
	$sites = hello_elementor_child_language_sites();
	$links = [];
	if ( empty( $sites ) ) {
		return $links;
	}

	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_unslash( $_SERVER['REQUEST_URI'] ) : '/';
	if ( '' === $request_uri ) {
		$request_uri = '/';
	}
	foreach ( $sites as $lang => $base_url ) {
		$lang               = sanitize_key( (string) $lang );
		$base               = rtrim( esc_url_raw( (string) $base_url ), '/' );
		$links[ $lang ]     = $base . $request_uri;
	}

	if ( is_singular() ) {
		$post_id = (int) get_queried_object_id();
		if ( $post_id > 0 ) {
			$map = get_post_meta( $post_id, '_heb_pp_lang_map', true );
			if ( is_array( $map ) ) {
				foreach ( $map as $lang => $url ) {
					$lang = sanitize_key( (string) $lang );
					$url  = esc_url_raw( (string) $url );
					if ( '' !== $lang && '' !== $url ) {
						$links[ $lang ] = $url;
					}
				}
			}
		}
	}

	return $links;
}

/**
 * 顶部导航追加语言切换器（全站可用）。
 *
 * @param string $items Menu html.
 * @param object $args  Menu args.
 * @return string
 */
function hello_elementor_child_append_language_switcher_to_menu( $items, $args ) {
	if ( is_admin() || empty( $args->theme_location ) || 'menu-1' !== $args->theme_location ) {
		return $items;
	}
	$links = hello_elementor_child_build_switcher_links();
	if ( empty( $links ) ) {
		return $items;
	}

	$host         = wp_parse_url( home_url(), PHP_URL_HOST );
	$current_lang = 'en';
	if ( is_string( $host ) && preg_match( '/^([a-z]{2})\./i', $host, $m ) ) {
		$current_lang = sanitize_key( strtolower( (string) $m[1] ) );
	}
	$label = strtoupper( $current_lang );
	$out   = '<li class="menu-item menu-item-type-custom menu-item-has-children heb-lang-menu">';
	$out  .= '<a href="#" aria-label="Language">' . esc_html( $label ) . '</a>';
	$out  .= '<ul class="sub-menu">';
	foreach ( $links as $lang => $url ) {
		$code  = strtoupper( sanitize_key( (string) $lang ) );
		$class = $lang === $current_lang ? ' class="current-lang"' : '';
		$out  .= '<li' . $class . '><a href="' . esc_url( $url ) . '">' . esc_html( $code ) . '</a></li>';
	}
	$out .= '</ul></li>';
	return $items . $out;
}
add_filter( 'wp_nav_menu_items', 'hello_elementor_child_append_language_switcher_to_menu', 20, 2 );

/**
 * Shortcode: [heb_lang_switcher]
 * 可在 Elementor 的 Shortcode 组件中直接使用。
 *
 * @param array<string,string> $atts Shortcode attributes.
 * @return string
 */
function hello_elementor_child_lang_switcher_shortcode( $atts = [] ) {
	$atts = shortcode_atts(
		[
			'class' => '',
		],
		(array) $atts,
		'heb_lang_switcher'
	);

	$links = hello_elementor_child_build_switcher_links();
	if ( empty( $links ) ) {
		return '';
	}

	$host         = wp_parse_url( home_url(), PHP_URL_HOST );
	$current_lang = 'en';
	if ( is_string( $host ) && preg_match( '/^([a-z]{2})\./i', $host, $m ) ) {
		$current_lang = sanitize_key( strtolower( (string) $m[1] ) );
	}

	$labels = [
		'en' => 'English',
		'ja' => 'Japanese',
		'fr' => 'French',
		'vi' => 'Vietnamese',
		'ko' => 'Korean',
		'ru' => 'Russian',
		'ar' => 'Arabic',
	];

	$cls = 'heb-lang-switcher heb-lang-switcher--custom';
	if ( is_string( $atts['class'] ) && '' !== trim( $atts['class'] ) ) {
		$cls .= ' ' . sanitize_html_class( trim( $atts['class'] ) );
	}

	$current_label = isset( $labels[ $current_lang ] ) ? $labels[ $current_lang ] : strtoupper( $current_lang );
	$html          = '<nav class="' . esc_attr( $cls ) . '" aria-label="Language switcher">';
	$html         .= '<details class="heb-lang-switcher__details">';
	$html         .= '<summary class="heb-lang-switcher__trigger"><span>' . esc_html( $current_label ) . '</span></summary>';
	$html         .= '<ul class="heb-lang-switcher__menu">';
	foreach ( $links as $lang => $url ) {
		$lang = sanitize_key( (string) $lang );
		$url  = esc_url( (string) $url );
		if ( '' === $lang || '' === $url ) {
			continue;
		}
		$label = isset( $labels[ $lang ] ) ? $labels[ $lang ] : strtoupper( $lang );
		if ( $lang === $current_lang ) {
			$html .= '<li class="is-current"><span>' . esc_html( $label ) . '</span></li>';
		} else {
			$html .= '<li><a href="' . $url . '">' . esc_html( $label ) . '</a></li>';
		}
	}
	$html .= '</ul></details></nav>';
	return $html;
}
add_shortcode( 'heb_lang_switcher', 'hello_elementor_child_lang_switcher_shortcode' );
