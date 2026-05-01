<?php
/**
 * Elementor: text breadcrumbs (CPT products, pages, archives).
 *
 * @package HelloElementorChild
 */

namespace HelloElementorChild\Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Breadcrumb trail: Home > … > current (last item not linked).
 */
class Widget_Breadcrumbs extends Widget_Base {

	public function get_name() {
		return 'heb_breadcrumbs';
	}

	public function get_title() {
		return esc_html__( 'Breadcrumbs · 面包屑', 'hello-elementor-child' );
	}

	public function get_icon() {
		return 'eicon-bullet-list';
	}

	public function get_categories() {
		return [ 'heb-child' ];
	}

	public function get_keywords() {
		return [ 'breadcrumb', 'trail', 'navigation', '产品', '面包屑' ];
	}

	public function get_style_depends() {
		return [ 'heb-breadcrumbs' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'hello-elementor-child' ),
			]
		);

		$this->add_control(
			'show_home',
			[
				'label'        => esc_html__( 'Show home', 'hello-elementor-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'home_label',
			[
				'label'     => esc_html__( 'Home label', 'hello-elementor-child' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Home', 'hello-elementor-child' ),
				'condition' => [
					'show_home' => 'yes',
				],
			]
		);

		$this->add_control(
			'separator',
			[
				'label'   => esc_html__( 'Separator', 'hello-elementor-child' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '>',
			]
		);

		$this->add_control(
			'product_post_type',
			[
				'label'       => esc_html__( 'Product post type slug', 'hello-elementor-child' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'products',
				'description' => esc_html__( 'Used to insert archive crumb before single posts of this type.', 'hello-elementor-child' ),
			]
		);

		$this->add_control(
			'show_product_archive',
			[
				'label'        => esc_html__( 'Show archive before single product', 'hello-elementor-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'archive_label_override',
			[
				'label'       => esc_html__( 'Archive label (optional)', 'hello-elementor-child' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Leave empty to use the post type’s plural name (e.g. Products).', 'hello-elementor-child' ),
				'condition'   => [
					'show_product_archive' => 'yes',
				],
			]
		);

		$this->add_control(
			'solutions_post_type',
			[
				'label'       => esc_html__( 'Solutions post type slug', 'hello-elementor-child' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'solutions',
				'description' => esc_html__( 'Matches ACF CPT “Solutions” (hierarchical).', 'hello-elementor-child' ),
			]
		);

		$this->add_control(
			'show_solutions_archive',
			[
				'label'        => esc_html__( 'Show Solutions archive before single solution', 'hello-elementor-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'solutions_archive_label_override',
			[
				'label'       => esc_html__( 'Solutions archive label (optional)', 'hello-elementor-child' ),
				'type'        => Controls_Manager::TEXT,
				'condition'   => [
					'show_solutions_archive' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_product_category_trail',
			[
				'label'        => esc_html__( 'Show product category trail', 'hello-elementor-child' ),
				'description'  => esc_html__( 'On single Products: Home › … › Product categories › Product title.', 'hello-elementor-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'product_category_taxonomy',
			[
				'label'       => esc_html__( 'Product category taxonomy slug', 'hello-elementor-child' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'product-categories',
				'condition'   => [
					'show_product_category_trail' => 'yes',
				],
			]
		);

		$rep = new Repeater();
		$rep->add_control(
			'mid_title',
			[
				'label'       => esc_html__( 'Title', 'hello-elementor-child' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);
		$rep->add_control(
			'mid_link',
			[
				'label'       => esc_html__( 'Link', 'hello-elementor-child' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => home_url( '/' ),
			]
		);

		$this->add_control(
			'prepend_items',
			[
				'label'       => esc_html__( 'Extra crumbs after Home', 'hello-elementor-child' ),
				'type'        => Controls_Manager::REPEATER,
				'description' => esc_html__( 'e.g. “Solutions” linking to a hub page (Home > Solutions > …).', 'hello-elementor-child' ),
				'fields'      => $rep->get_controls(),
				'title_field' => '{{{ mid_title }}}',
			]
		);

		$this->add_control(
			'schema_jsonld',
			[
				'label'        => esc_html__( 'BreadcrumbList JSON-LD', 'hello-elementor-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Output invisible structured data for search engines.', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Style', 'hello-elementor-child' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => esc_html__( 'Alignment', 'hello-elementor-child' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'flex-start',
				'options'   => [
					'flex-start' => esc_html__( 'Left', 'hello-elementor-child' ),
					'center'     => esc_html__( 'Center', 'hello-elementor-child' ),
					'flex-end'   => esc_html__( 'Right', 'hello-elementor-child' ),
				],
				'selectors' => [
					'{{WRAPPER}} .heb-breadcrumb__list' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'link_color',
			[
				'label'     => esc_html__( 'Link color', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1e3a7a',
				'selectors' => [
					'{{WRAPPER}} .heb-breadcrumb' => '--heb-bc-link: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sep_color',
			[
				'label'     => esc_html__( 'Separator color', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#666666',
				'selectors' => [
					'{{WRAPPER}} .heb-breadcrumb' => '--heb-bc-sep: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'current_color',
			[
				'label'     => esc_html__( 'Current page color', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#444444',
				'selectors' => [
					'{{WRAPPER}} .heb-breadcrumb' => '--heb-bc-current: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .heb-breadcrumb',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @param array<int, array{title: string, url: string}> $crumbs
	 */
	private function add_post_type_archive_crumb( array &$crumbs, string $post_type, ?string $label_override = null ): void {
		if ( '' === $post_type ) {
			return;
		}
		$archive = get_post_type_archive_link( $post_type );
		if ( ! $archive ) {
			return;
		}
		$label = ( null !== $label_override && '' !== trim( $label_override ) ) ? trim( $label_override ) : '';
		if ( '' === $label ) {
			$pto   = get_post_type_object( $post_type );
			$label = $pto && isset( $pto->labels->name ) ? $pto->labels->name : ucfirst( $post_type );
		}
		$crumbs[] = [
			'title' => $label,
			'url'   => $archive,
		];
	}

	/**
	 * @param array<int, array{title: string, url: string}> $crumbs
	 */
	private function add_term_ancestor_crumbs( array &$crumbs, \WP_Term $term, bool $include_self_link ): void {
		$ids = get_ancestors( $term->term_id, $term->taxonomy, 'taxonomy' );
		$ids = array_reverse( $ids );

		foreach ( $ids as $tid ) {
			$t = get_term( $tid, $term->taxonomy );
			if ( ! $t || is_wp_error( $t ) ) {
				continue;
			}
			$link = get_term_link( $t );
			$crumbs[] = [
				'title' => $t->name,
				'url'   => is_wp_error( $link ) ? '' : $link,
			];
		}

		if ( $include_self_link ) {
			$link = get_term_link( $term );
			$crumbs[] = [
				'title' => $term->name,
				'url'   => is_wp_error( $link ) ? '' : $link,
			];
		}
	}

	/**
	 * @param array<int, array{title: string, url: string}> $crumbs
	 */
	private function add_product_category_trail_for_post( array &$crumbs, \WP_Post $post, string $taxonomy ): void {
		if ( ! taxonomy_exists( $taxonomy ) ) {
			return;
		}
		$terms = get_the_terms( $post->ID, $taxonomy );
		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return;
		}
		$best       = null;
		$best_depth = -1;
		foreach ( $terms as $t ) {
			if ( ! $t instanceof \WP_Term ) {
				continue;
			}
			$depth = count( get_ancestors( $t->term_id, $taxonomy, 'taxonomy' ) );
			if ( $depth > $best_depth ) {
				$best_depth = $depth;
				$best       = $t;
			}
		}
		if ( ! $best instanceof \WP_Term ) {
			return;
		}
		$this->add_term_ancestor_crumbs( $crumbs, $best, true );
	}

	/**
	 * Insert CPT archive link(s) on taxonomy archives when the taxonomy belongs to products and/or solutions.
	 *
	 * @param array<int, array{title: string, url: string}> $crumbs
	 * @param array<string,mixed>                          $settings
	 */
	private function add_cpt_archives_for_taxonomy( array &$crumbs, \WP_Term $term, array $settings ): void {
		$tax = get_taxonomy( $term->taxonomy );
		if ( ! $tax ) {
			return;
		}
		$pts = isset( $tax->object_type ) ? (array) $tax->object_type : [];

		$pt_slug = isset( $settings['product_post_type'] ) ? sanitize_key( (string) $settings['product_post_type'] ) : 'products';
		if ( '' === $pt_slug ) {
			$pt_slug = 'products';
		}
		$sol_slug = isset( $settings['solutions_post_type'] ) ? sanitize_key( (string) $settings['solutions_post_type'] ) : 'solutions';
		if ( '' === $sol_slug ) {
			$sol_slug = 'solutions';
		}

		if ( in_array( $pt_slug, $pts, true ) && ! empty( $settings['show_product_archive'] ) && 'yes' === $settings['show_product_archive'] ) {
			$ov = isset( $settings['archive_label_override'] ) ? (string) $settings['archive_label_override'] : '';
			$this->add_post_type_archive_crumb( $crumbs, $pt_slug, '' !== trim( $ov ) ? $ov : null );
		}
		if ( in_array( $sol_slug, $pts, true ) && ! empty( $settings['show_solutions_archive'] ) && 'yes' === $settings['show_solutions_archive'] ) {
			$ov = isset( $settings['solutions_archive_label_override'] ) ? (string) $settings['solutions_archive_label_override'] : '';
			$this->add_post_type_archive_crumb( $crumbs, $sol_slug, '' !== trim( $ov ) ? $ov : null );
		}
	}

	/**
	 * @param array<string,mixed> $settings Widget settings.
	 * @return array<int, array{title: string, url: string}>
	 */
	private function build_crumbs( array $settings ) {
		if ( is_front_page() ) {
			return [];
		}

		$crumbs = [];

		if ( ! empty( $settings['show_home'] ) && 'yes' === $settings['show_home'] ) {
			$home_label = isset( $settings['home_label'] ) && '' !== $settings['home_label']
				? $settings['home_label']
				: esc_html__( 'Home', 'hello-elementor-child' );
			$crumbs[] = [
				'title' => $home_label,
				'url'   => home_url( '/' ),
			];
		}

		if ( ! empty( $settings['prepend_items'] ) && is_array( $settings['prepend_items'] ) ) {
			foreach ( $settings['prepend_items'] as $row ) {
				$t = isset( $row['mid_title'] ) ? $row['mid_title'] : '';
				$u = isset( $row['mid_link']['url'] ) ? $row['mid_link']['url'] : '';
				if ( '' === $t ) {
					continue;
				}
				$crumbs[] = [
					'title' => $t,
					'url'   => $u ? $u : '',
				];
			}
		}

		$pt_slug = isset( $settings['product_post_type'] ) ? sanitize_key( $settings['product_post_type'] ) : 'products';
		if ( '' === $pt_slug ) {
			$pt_slug = 'products';
		}

		$sol_slug = isset( $settings['solutions_post_type'] ) ? sanitize_key( $settings['solutions_post_type'] ) : 'solutions';
		if ( '' === $sol_slug ) {
			$sol_slug = 'solutions';
		}

		if ( is_singular() ) {
			$post = get_queried_object();
			if ( $post instanceof \WP_Post ) {
				if ( $post->post_type === $sol_slug && ! empty( $settings['show_solutions_archive'] ) && 'yes' === $settings['show_solutions_archive'] ) {
					$ov = isset( $settings['solutions_archive_label_override'] ) ? (string) $settings['solutions_archive_label_override'] : '';
					$this->add_post_type_archive_crumb( $crumbs, $sol_slug, '' !== trim( $ov ) ? $ov : null );
				}

				if ( is_post_type_hierarchical( $post->post_type ) ) {
					$ancestors = get_post_ancestors( $post->ID, $post->post_type );
					if ( ! empty( $ancestors ) ) {
						$ancestors = array_reverse( $ancestors );
						foreach ( $ancestors as $aid ) {
							$crumbs[] = [
								'title' => get_the_title( $aid ),
								'url'   => get_permalink( $aid ),
							];
						}
					}
				}

				if ( $post->post_type === $pt_slug && ! empty( $settings['show_product_archive'] ) && 'yes' === $settings['show_product_archive'] ) {
					$ov = isset( $settings['archive_label_override'] ) ? (string) $settings['archive_label_override'] : '';
					$this->add_post_type_archive_crumb( $crumbs, $pt_slug, '' !== trim( $ov ) ? $ov : null );
				}
				if ( $post->post_type === $pt_slug && ! empty( $settings['show_product_category_trail'] ) && 'yes' === $settings['show_product_category_trail'] ) {
					$tax = isset( $settings['product_category_taxonomy'] ) ? sanitize_key( (string) $settings['product_category_taxonomy'] ) : 'product-categories';
					if ( '' === $tax ) {
						$tax = 'product-categories';
					}
					$this->add_product_category_trail_for_post( $crumbs, $post, $tax );
				}

				$crumbs[] = [
					'title' => get_the_title( $post ),
					'url'   => '',
				];
			}
			return apply_filters( 'hello_elementor_child_breadcrumb_crumbs', $crumbs, $settings );
		}

		if ( is_post_type_archive( $pt_slug ) ) {
			$label = isset( $settings['archive_label_override'] ) && '' !== trim( (string) $settings['archive_label_override'] )
				? $settings['archive_label_override']
				: '';
			if ( '' === $label ) {
				$pto   = get_post_type_object( $pt_slug );
				$label = $pto && isset( $pto->labels->name ) ? $pto->labels->name : ucfirst( $pt_slug );
			}
			$crumbs[] = [
				'title' => $label,
				'url'   => '',
			];
			return apply_filters( 'hello_elementor_child_breadcrumb_crumbs', $crumbs, $settings );
		}

		if ( is_home() && ! is_front_page() ) {
			$crumbs[] = [
				'title' => single_post_title( '', false ),
				'url'   => '',
			];
			return apply_filters( 'hello_elementor_child_breadcrumb_crumbs', $crumbs, $settings );
		}

		if ( is_category() || is_tag() || is_tax() ) {
			$term = get_queried_object();
			if ( $term instanceof \WP_Term ) {
				$this->add_cpt_archives_for_taxonomy( $crumbs, $term, $settings );
				$this->add_term_ancestor_crumbs( $crumbs, $term, false );
				$crumbs[] = [
					'title' => $term->name,
					'url'   => '',
				];
			}
			return apply_filters( 'hello_elementor_child_breadcrumb_crumbs', $crumbs, $settings );
		}

		if ( is_post_type_archive() ) {
			$pto = get_queried_object();
			if ( $pto instanceof \WP_Post_Type ) {
				$crumbs[] = [
					'title' => $pto->labels->name ?? $pto->name,
					'url'   => '',
				];
			}
			return apply_filters( 'hello_elementor_child_breadcrumb_crumbs', $crumbs, $settings );
		}

		if ( is_404() ) {
			$crumbs[] = [
				'title' => esc_html__( 'Page not found', 'hello-elementor-child' ),
				'url'   => '',
			];
			return apply_filters( 'hello_elementor_child_breadcrumb_crumbs', $crumbs, $settings );
		}

		if ( is_search() ) {
			$crumbs[] = [
				'title' => sprintf(
					/* translators: %s search query */
					__( 'Search results for "%s"', 'hello-elementor-child' ),
					get_search_query()
				),
				'url'   => '',
			];
			return apply_filters( 'hello_elementor_child_breadcrumb_crumbs', $crumbs, $settings );
		}

		return apply_filters( 'hello_elementor_child_breadcrumb_crumbs', $crumbs, $settings );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$sep        = isset( $settings['separator'] ) ? $settings['separator'] : '>';
		$crumbs     = $this->build_crumbs( $settings );

		if ( empty( $crumbs ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<nav class="heb-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'hello-elementor-child' ) . '">';
				echo '<span class="heb-breadcrumb__placeholder">' . esc_html__( 'Breadcrumb appears on the front end (not on the static front page).', 'hello-elementor-child' ) . '</span>';
				echo '</nav>';
			}
			return;
		}

		$items_schema = [];
		$position     = 1;
		$canonical    = function_exists( 'wp_get_canonical_url' ) ? wp_get_canonical_url() : '';
		if ( ! is_string( $canonical ) || '' === $canonical ) {
			$canonical = is_singular() ? get_permalink() : home_url( add_query_arg( [] ) );
		}

		echo '<nav class="heb-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'hello-elementor-child' ) . '">';
		echo '<ol class="heb-breadcrumb__list">';

		$last = count( $crumbs ) - 1;
		foreach ( $crumbs as $i => $c ) {
			$title   = isset( $c['title'] ) ? $c['title'] : '';
			$url     = isset( $c['url'] ) ? $c['url'] : '';
			$is_last = ( $i === $last );

			if ( $i > 0 ) {
				echo '<li class="heb-breadcrumb__sep" role="presentation" aria-hidden="true">' . esc_html( $sep ) . '</li>';
			}

			echo '<li class="heb-breadcrumb__item">';

			if ( $is_last || '' === $url ) {
				echo '<span class="heb-breadcrumb__current"' . ( $is_last ? ' aria-current="page"' : '' ) . '><span class="heb-breadcrumb__current-inner">' . esc_html( $title ) . '</span></span>';
			} else {
				echo '<a class="heb-breadcrumb__link" href="' . esc_url( $url ) . '">' . esc_html( $title ) . '</a>';
			}

			echo '</li>';

			$item_url = ( $is_last || '' === $url ) ? $canonical : $url;
			$items_schema[] = [
				'@type'    => 'ListItem',
				'position' => $position,
				'name'     => wp_strip_all_tags( $title ),
				'item'     => $item_url,
			];
			++$position;
		}

		echo '</ol></nav>';

		if ( ! empty( $settings['schema_jsonld'] ) && 'yes' === $settings['schema_jsonld'] && ! empty( $items_schema ) ) {
			$schema = [
				'@context'        => 'https://schema.org',
				'@type'           => 'BreadcrumbList',
				'itemListElement' => $items_schema,
			];
			echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>';
		}
	}
}
