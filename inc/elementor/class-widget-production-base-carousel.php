<?php
/**
 * Elementor: Production base image carousel — title, Swiper, line pagination.
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
 * Gallery row + horizontal bar bullets (pagination).
 */
class Widget_Production_Base_Carousel extends Widget_Base {

	public function get_name() {
		return 'heb_production_base_carousel';
	}

	public function get_title() {
		return esc_html__( 'Production Base Gallery · 生产基地轮播', 'hello-elementor-child' );
	}

	public function get_icon() {
		return 'eicon-gallery-slider';
	}

	public function get_categories() {
		return [ 'heb-child' ];
	}

	public function get_keywords() {
		return [ 'factory', 'gallery', 'swiper', 'slider', 'production', '基地', '相册' ];
	}

	public function get_style_depends() {
		return [ 'heb-swiper', 'heb-production-base-carousel' ];
	}

	public function get_script_depends() {
		return [ 'heb-swiper', 'heb-production-base-carousel' ];
	}

	/**
	 * @return array<int, array<string, mixed>>
	 */
	private function default_slides() {
		return [
			[],
			[],
			[],
		];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_heading',
			[
				'label' => esc_html__( 'Heading', 'hello-elementor-child' ),
			]
		);

		$this->add_control(
			'section_title',
			[
				'label'       => esc_html__( 'Title', 'hello-elementor-child' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Production Base', 'hello-elementor-child' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'show_title_line',
			[
				'label'        => esc_html__( 'Title underline', 'hello-elementor-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slides',
			[
				'label' => esc_html__( 'Images', 'hello-elementor-child' ),
			]
		);

		$rep = new Repeater();
		$rep->add_control(
			'slide_image',
			[
				'label' => esc_html__( 'Image', 'hello-elementor-child' ),
				'type'  => Controls_Manager::MEDIA,
			]
		);

		$this->add_control(
			'slides',
			[
				'label'       => esc_html__( 'Slides', 'hello-elementor-child' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $rep->get_controls(),
				'default'     => $this->default_slides(),
				'title_field' => 'Slide',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Carousel', 'hello-elementor-child' ),
			]
		);

		$this->add_responsive_control(
			'slides_per_view',
			[
				'label'          => esc_html__( 'Slides per view', 'hello-elementor-child' ),
				'type'           => Controls_Manager::NUMBER,
				'min'            => 1,
				'max'            => 6,
				'step'           => 1,
				'default'        => 3,
				'tablet_default' => 2,
				'mobile_default' => 1,
			]
		);

		$this->add_responsive_control(
			'slides_gap',
			[
				'label'          => esc_html__( 'Gap', 'hello-elementor-child' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => [ 'px' ],
				'range'          => [
					'px' => [
						'min' => 0,
						'max' => 48,
					],
				],
				'default'        => [
					'size' => 20,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 16,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 12,
					'unit' => 'px',
				],
			]
		);

		$this->add_control(
			'slide_height_mode',
			[
				'label'       => esc_html__( 'Slide height mode', 'hello-elementor-child' ),
				'description' => esc_html__( 'Fixed height keeps one row uniform; Min height follows image.', 'hello-elementor-child' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'fixed',
				'options'     => [
					'fixed' => esc_html__( 'Fixed height', 'hello-elementor-child' ),
					'min'   => esc_html__( 'Min height', 'hello-elementor-child' ),
				],
			]
		);

		$this->add_responsive_control(
			'slide_area_height',
			[
				'label'          => esc_html__( 'Slide image height', 'hello-elementor-child' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => [ 'px', 'vh' ],
				'range'          => [
					'px' => [
						'min' => 120,
						'max' => 900,
					],
					'vh' => [
						'min' => 15,
						'max' => 90,
					],
				],
				'default'        => [
					'size' => 240,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 200,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 180,
					'unit' => 'px',
				],
				'selectors'      => [
					'{{WRAPPER}}' => '--heb-prod-slide-h: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'        => esc_html__( 'Autoplay', 'hello-elementor-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'autoplay_delay',
			[
				'label'     => esc_html__( 'Autoplay delay (ms)', 'hello-elementor-child' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1000,
				'max'       => 20000,
				'step'      => 500,
				'default'   => 5000,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label'       => esc_html__( 'Transition speed (ms)', 'hello-elementor-child' ),
				'description' => esc_html__( 'Slide animation duration.', 'hello-elementor-child' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 100,
				'max'         => 3000,
				'step'        => 50,
				'default'     => 600,
			]
		);

		$this->add_control(
			'autoplay_pause_on_hover',
			[
				'label'        => esc_html__( 'Pause on hover', 'hello-elementor-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'loop',
			[
				'label'        => esc_html__( 'Loop', 'hello-elementor-child' ),
				'description'  => esc_html__( 'Continuously loop slides. Disabled automatically when total slides ≤ slides per view.', 'hello-elementor-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_heading',
			[
				'label' => esc_html__( 'Heading', 'hello-elementor-child' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title color', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1a1a1a',
				'selectors' => [
					'{{WRAPPER}} .heb-prod-base__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Title typography', 'hello-elementor-child' ),
				'selector' => '{{WRAPPER}} .heb-prod-base__title',
			]
		);

		$this->add_control(
			'title_line_color',
			[
				'label'     => esc_html__( 'Underline color', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#c8c8c8',
				'selectors' => [
					'{{WRAPPER}} .heb-prod-base__title-line' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'show_title_line' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_pagination',
			[
				'label' => esc_html__( 'Line pagination', 'hello-elementor-child' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'pagination_inactive',
			[
				'label'     => esc_html__( 'Inactive bar color', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#dedede',
				'selectors' => [
					'{{WRAPPER}} .heb-prod-base__pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pagination_active',
			[
				'label'     => esc_html__( 'Active bar color', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#722f37',
				'selectors' => [
					'{{WRAPPER}} .heb-prod-base__pagination .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_bar_width',
			[
				'label'      => esc_html__( 'Bar width', 'hello-elementor-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 8,
						'max' => 80,
					],
				],
				'default'    => [
					'size' => 36,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .heb-prod-base__pagination .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_bar_height',
			[
				'label'      => esc_html__( 'Bar height', 'hello-elementor-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 2,
						'max' => 12,
					],
				],
				'default'    => [
					'size' => 3,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .heb-prod-base__pagination .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_gap',
			[
				'label'      => esc_html__( 'Spacing above lines', 'hello-elementor-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 48,
					],
				],
				'default'    => [
					'size' => 20,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .heb-prod-base__pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_line_gap',
			[
				'label'      => esc_html__( 'Gap between bars', 'hello-elementor-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 24,
					],
				],
				'default'    => [
					'size' => 8,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .heb-prod-base__pagination' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @param array<string,mixed> $settings Saved widget settings.
	 * @param string              $key      Control key.
	 * @param int                 $fallback Fallback px.
	 * @return int
	 */
	private function slider_field_px( array $settings, $key, $fallback ) {
		if ( ! isset( $settings[ $key ] ) || ! is_array( $settings[ $key ] ) ) {
			return (int) $fallback;
		}
		$row = $settings[ $key ];
		$u   = isset( $row['unit'] ) ? (string) $row['unit'] : 'px';
		$s   = isset( $row['size'] ) ? (float) $row['size'] : (float) $fallback;
		if ( 'px' !== $u ) {
			return (int) round( $fallback );
		}
		return max( 0, (int) round( $s ) );
	}

	/**
	 * @param array<string,mixed> $settings Saved widget settings from get_settings().
	 * @return array<string,mixed>
	 */
	private function build_swiper_options( array $settings ) {
		$spv_d = isset( $settings['slides_per_view'] ) && '' !== $settings['slides_per_view']
			? max( 1, min( 6, (int) $settings['slides_per_view'] ) )
			: 3;
		$spv_t = isset( $settings['slides_per_view_tablet'] ) && '' !== $settings['slides_per_view_tablet']
			? max( 1, min( 6, (int) $settings['slides_per_view_tablet'] ) )
			: 2;
		$spv_m = isset( $settings['slides_per_view_mobile'] ) && '' !== $settings['slides_per_view_mobile']
			? max( 1, min( 6, (int) $settings['slides_per_view_mobile'] ) )
			: 1;

		$gap_d = $this->slider_field_px( $settings, 'slides_gap', 20 );
		$gap_t = $this->slider_field_px( $settings, 'slides_gap_tablet', $gap_d );
		$gap_m = $this->slider_field_px( $settings, 'slides_gap_mobile', $gap_t );

		$opts = [
			'slidesPerView'  => $spv_m,
			'spaceBetween'   => $gap_m,
			'speed'          => isset( $settings['autoplay_speed'] ) && '' !== $settings['autoplay_speed']
				? max( 100, min( 3000, (int) $settings['autoplay_speed'] ) )
				: 600,
			'watchOverflow'  => true,
			'observer'       => true,
			'observeParents' => true,
			'breakpoints'    => [
				768  => [
					'slidesPerView' => $spv_t,
					'spaceBetween'  => $gap_t,
				],
				1025 => [
					'slidesPerView' => $spv_d,
					'spaceBetween'  => $gap_d,
				],
			],
		];

		if ( ! empty( $settings['autoplay'] ) && 'yes' === $settings['autoplay'] ) {
			$delay                  = isset( $settings['autoplay_delay'] ) && '' !== $settings['autoplay_delay']
				? max( 500, min( 30000, (int) $settings['autoplay_delay'] ) )
				: 5000;
			$opts['autoplay']       = [
				'delay'                => $delay,
				'disableOnInteraction' => false,
				'pauseOnMouseEnter'    => ! empty( $settings['autoplay_pause_on_hover'] ) && 'yes' === $settings['autoplay_pause_on_hover'],
			];
		}

		if ( ! empty( $settings['loop'] ) && 'yes' === $settings['loop'] ) {
			$opts['loop'] = true;
		}

		return $opts;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$slides   = isset( $settings['slides'] ) && is_array( $settings['slides'] ) ? $settings['slides'] : [];
		$edit     = \Elementor\Plugin::$instance->editor->is_edit_mode();

		if ( empty( $slides ) ) {
			if ( $edit ) {
				echo '<p class="heb-prod-base__empty">' . esc_html__( 'Add images in the widget panel.', 'hello-elementor-child' ) . '</p>';
			}
			return;
		}

		$title     = isset( $settings['section_title'] ) ? $settings['section_title'] : '';
		$show_line = ! empty( $settings['show_title_line'] ) && 'yes' === $settings['show_title_line'];
		$line_cls  = $show_line ? 'heb-prod-base__title-line' : 'heb-prod-base__title-line is-hidden';

		$saved  = $this->get_settings();
		$sw     = $this->build_swiper_options( is_array( $saved ) ? $saved : [] );
		$h_mode = isset( $settings['slide_height_mode'] ) && 'min' === $settings['slide_height_mode'] ? 'min' : 'fixed';

		echo '<div class="heb-prod-base" data-heb-height-mode="' . esc_attr( $h_mode ) . '" data-heb-swiper="' . esc_attr( wp_json_encode( $sw ) ) . '">';

		if ( '' !== $title ) {
			echo '<div class="heb-prod-base__head">';
			echo '<h3 class="heb-prod-base__title">' . esc_html( $title ) . '</h3>';
			echo '<span class="' . esc_attr( $line_cls ) . '" aria-hidden="true"></span>';
			echo '</div>';
		}

		echo '<div class="swiper heb-prod-base__swiper">';
		echo '<div class="swiper-wrapper">';

		foreach ( $slides as $row ) {
			$media  = isset( $row['slide_image'] ) && is_array( $row['slide_image'] ) ? $row['slide_image'] : [];
			$img_id = ! empty( $media['id'] ) ? (int) $media['id'] : 0;

			echo '<div class="swiper-slide">';
			echo '<figure class="heb-prod-base__slide-inner">';
			echo '<div class="heb-prod-base__img-wrap">';
			if ( $img_id ) {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image is safe.
				echo wp_get_attachment_image(
					$img_id,
					'large',
					false,
					[
						'class'   => 'heb-prod-base__img',
						'loading' => 'lazy',
					]
				);
			} elseif ( $edit ) {
				echo '<span class="heb-prod-base__placeholder">';
				echo esc_html__( 'Select image', 'hello-elementor-child' );
				echo '</span>';
			}
			echo '</div>';
			echo '</figure>';
			echo '</div>';
		}

		echo '</div>';
		echo '<div class="swiper-pagination heb-prod-base__pagination"></div>';
		echo '</div>';

		echo '</div>';
	}
}
