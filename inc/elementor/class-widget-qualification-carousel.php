<?php
/**
 * Elementor: Qualification / certificate horizontal carousel.
 *
 * @package HelloElementorChild
 */

namespace HelloElementorChild\Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Title + line, grey cards, white image frame, caption, prev/next.
 */
class Widget_Qualification_Carousel extends Widget_Base {

	public function get_name() {
		return 'heb_qualification_carousel';
	}

	public function get_title() {
		return esc_html__( 'Qualification Carousel · 资质证书轮播', 'hello-elementor-child' );
	}

	public function get_icon() {
		return 'eicon-slides';
	}

	public function get_categories() {
		return [ 'heb-child' ];
	}

	public function get_keywords() {
		return [ 'cert', 'carousel', 'slider', 'qualification', '证书', '资质' ];
	}

	public function get_style_depends() {
		return [ 'heb-swiper', 'heb-qualification-carousel' ];
	}

	public function get_script_depends() {
		return [ 'heb-swiper', 'heb-qualification-carousel' ];
	}

	/**
	 * @return array<int, array<string, mixed>>
	 */
	private function default_slides() {
		return [
			[
				'caption' => esc_html__( '24 Classes of Registered Trademarks Electronic', 'hello-elementor-child' ),
			],
			[
				'caption' => esc_html__( 'Trademark Registration Certificate for Class 25', 'hello-elementor-child' ),
			],
			[
				'caption' => esc_html__( 'Suzhou YNS GRS Certificate', 'hello-elementor-child' ),
			],
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
				'default'     => esc_html__( 'Honorary Qualifications', 'hello-elementor-child' ),
				'placeholder' => esc_html__( 'Section title', 'hello-elementor-child' ),
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
				'label' => esc_html__( 'Slides', 'hello-elementor-child' ),
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
		$rep->add_control(
			'caption',
			[
				'label'       => esc_html__( 'Caption', 'hello-elementor-child' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			]
		);

		$this->add_control(
			'slides',
			[
				'label'       => esc_html__( 'Certificates', 'hello-elementor-child' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $rep->get_controls(),
				'default'     => $this->default_slides(),
				'title_field' => '{{{ caption }}}',
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
					'size' => 16,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 14,
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
				'label'       => esc_html__( 'Slide image height mode', 'hello-elementor-child' ),
				'description' => esc_html__( 'Min height: grows with content; Fixed height: uniform rows (certificates scale inside).', 'hello-elementor-child' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'min',
				'options'     => [
					'min'   => esc_html__( 'Min height', 'hello-elementor-child' ),
					'fixed' => esc_html__( 'Fixed height', 'hello-elementor-child' ),
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
						'min' => 80,
						'max' => 900,
					],
					'vh' => [
						'min' => 15,
						'max' => 90,
					],
				],
				'default'        => [
					'size' => 230,
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
					'{{WRAPPER}}' => '--heb-qual-slide-h: {{SIZE}}{{UNIT}};',
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
				'label'       => esc_html__( 'Autoplay delay (ms)', 'hello-elementor-child' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1000,
				'max'         => 20000,
				'step'        => 500,
				'default'     => 5000,
				'condition'   => [
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
			'section_nav_icons',
			[
				'label' => esc_html__( 'Navigation arrows', 'hello-elementor-child' ),
			]
		);

		$this->add_control(
			'nav_icon_prev',
			[
				'label'   => esc_html__( 'Previous icon', 'hello-elementor-child' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value'   => 'eicon-chevron-left',
					'library' => 'eicons',
				],
			]
		);

		$this->add_control(
			'nav_icon_next',
			[
				'label'   => esc_html__( 'Next icon', 'hello-elementor-child' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value'   => 'eicon-chevron-right',
					'library' => 'eicons',
				],
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
					'{{WRAPPER}} .heb-qual-carousel__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Title typography', 'hello-elementor-child' ),
				'selector' => '{{WRAPPER}} .heb-qual-carousel__title',
			]
		);

		$this->add_control(
			'title_line_color',
			[
				'label'     => esc_html__( 'Underline color', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#c8c8c8',
				'selectors' => [
					'{{WRAPPER}} .heb-qual-carousel__title-line' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'show_title_line' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'title_margin_bottom',
			[
				'label'      => esc_html__( 'Title spacing bottom', 'hello-elementor-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 48,
					],
				],
				'default'    => [
					'size' => 10,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .heb-qual-carousel__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_card',
			[
				'label' => esc_html__( 'Card', 'hello-elementor-child' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'card_background',
			[
				'label'     => esc_html__( 'Card background', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f2f2f2',
				'selectors' => [
					'{{WRAPPER}} .heb-qual-carousel__slide-inner' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'image_well_background',
			[
				'label'     => esc_html__( 'Image frame background', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .heb-qual-carousel__img-wrap' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'card_padding',
			[
				'label'      => esc_html__( 'Card padding', 'hello-elementor-child' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default'    => [
					'top'      => '14',
					'right'    => '14',
					'bottom'   => '16',
					'left'     => '14',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .heb-qual-carousel__slide-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'caption_color',
			[
				'label'     => esc_html__( 'Caption color', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#444444',
				'selectors' => [
					'{{WRAPPER}} .heb-qual-carousel__caption' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'caption_typography',
				'label'    => esc_html__( 'Caption typography', 'hello-elementor-child' ),
				'selector' => '{{WRAPPER}} .heb-qual-carousel__caption',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_nav',
			[
				'label' => esc_html__( 'Arrows', 'hello-elementor-child' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'nav_background',
			[
				'label'     => esc_html__( 'Background', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eeeeee',
				'selectors' => [
					'{{WRAPPER}} .heb-qual-carousel__nav' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_color',
			[
				'label'     => esc_html__( 'Icon color', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#444444',
				'selectors' => [
					'{{WRAPPER}} .heb-qual-carousel__nav'              => 'color: {{VALUE}};',
					'{{WRAPPER}} .heb-qual-carousel__nav svg'          => 'fill: {{VALUE}};',
					'{{WRAPPER}} .heb-qual-carousel__nav svg path'      => 'fill: {{VALUE}};',
					'{{WRAPPER}} .heb-qual-carousel__nav .e-font-icon-svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'nav_icon_size',
			[
				'label'      => esc_html__( 'Icon size', 'hello-elementor-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 12,
						'max' => 48,
					],
				],
				'default'    => [
					'size' => 22,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .heb-qual-carousel__nav-icon-wrap svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .heb-qual-carousel__nav-icon-wrap i'    => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'nav_border_color',
			[
				'label'     => esc_html__( 'Border color', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#dddddd',
				'selectors' => [
					'{{WRAPPER}} .heb-qual-carousel__nav' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'nav_width',
			[
				'label'      => esc_html__( 'Arrow button width', 'hello-elementor-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 28,
						'max' => 64,
					],
				],
				'default'    => [
					'size' => 40,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .heb-qual-carousel__nav' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Parse responsive slider control as px (layout tab uses px only).
	 *
	 * @param array<string,mixed> $settings Saved widget settings.
	 * @param string              $key      Control key (include _tablet / _mobile suffix when needed).
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
	 * Swiper options (mobile-first breakpoints at 768 / 1025 to align with Elementor defaults).
	 *
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

		$gap_d = $this->slider_field_px( $settings, 'slides_gap', 16 );
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

	/**
	 * @param array<string,mixed>  $settings Widget settings.
	 * @param string                 $key      Control key.
	 * @param array<string,string> $fallback Default Elementor icon.
	 * @return array<string,string>
	 */
	private function get_nav_icon_for_render( array $settings, $key, array $fallback ) {
		$icon = isset( $settings[ $key ] ) && is_array( $settings[ $key ] ) ? $settings[ $key ] : [];
		if ( empty( $icon['value'] ) ) {
			return $fallback;
		}
		return $icon;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$slides   = isset( $settings['slides'] ) && is_array( $settings['slides'] ) ? $settings['slides'] : [];
		$edit     = \Elementor\Plugin::$instance->editor->is_edit_mode();

		if ( empty( $slides ) ) {
			if ( $edit ) {
				echo '<p class="heb-qual-carousel__empty">' . esc_html__( 'Add slides (image + caption) in the widget panel.', 'hello-elementor-child' ) . '</p>';
			}
			return;
		}

		$title     = isset( $settings['section_title'] ) ? $settings['section_title'] : '';
		$show_line = ! empty( $settings['show_title_line'] ) && 'yes' === $settings['show_title_line'];
		$line_cls  = $show_line ? 'heb-qual-carousel__title-line' : 'heb-qual-carousel__title-line is-hidden';

		$saved      = $this->get_settings();
		$sw         = $this->build_swiper_options( is_array( $saved ) ? $saved : [] );
		$h_mode     = isset( $settings['slide_height_mode'] ) && 'fixed' === $settings['slide_height_mode'] ? 'fixed' : 'min';

		echo '<div class="heb-qual-carousel" data-heb-height-mode="' . esc_attr( $h_mode ) . '" data-heb-swiper="' . esc_attr( wp_json_encode( $sw ) ) . '">';
		if ( '' !== $title ) {
			echo '<div class="heb-qual-carousel__head">';
			echo '<h3 class="heb-qual-carousel__title">' . esc_html( $title ) . '</h3>';
			echo '<span class="' . esc_attr( $line_cls ) . '" aria-hidden="true"></span>';
			echo '</div>';
		}

		$icon_prev = $this->get_nav_icon_for_render(
			$settings,
			'nav_icon_prev',
			[
				'value'   => 'eicon-chevron-left',
				'library' => 'eicons',
			]
		);
		$icon_next = $this->get_nav_icon_for_render(
			$settings,
			'nav_icon_next',
			[
				'value'   => 'eicon-chevron-right',
				'library' => 'eicons',
			]
		);

		echo '<div class="heb-qual-carousel__row">';
		echo '<button type="button" class="heb-qual-carousel__nav heb-qual-carousel__nav--prev swiper-button-prev" aria-label="' . esc_attr__( 'Previous', 'hello-elementor-child' ) . '">';
		echo '<span class="heb-qual-carousel__nav-icon-wrap">';
		Icons_Manager::render_icon( $icon_prev, [ 'aria-hidden' => 'true' ] );
		echo '</span>';
		echo '</button>';

		echo '<div class="heb-qual-carousel__viewport">';
		echo '<div class="swiper heb-qual-carousel__swiper">';
		echo '<div class="swiper-wrapper">';

		foreach ( $slides as $row ) {
			$media   = isset( $row['slide_image'] ) && is_array( $row['slide_image'] ) ? $row['slide_image'] : [];
			$img_id  = ! empty( $media['id'] ) ? (int) $media['id'] : 0;
			$caption = isset( $row['caption'] ) ? $row['caption'] : '';

			echo '<div class="swiper-slide">';
			echo '<article class="heb-qual-carousel__slide-inner">';
			echo '<figure class="heb-qual-carousel__figure">';
			echo '<div class="heb-qual-carousel__img-wrap">';
			if ( $img_id ) {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image is safe.
				echo wp_get_attachment_image(
					$img_id,
					'large',
					false,
					[
						'class'   => 'heb-qual-carousel__img',
						'loading' => 'lazy',
					]
				);
			} elseif ( $edit ) {
				echo '<span class="heb-qual-carousel__placeholder">';
				echo esc_html__( 'Select image', 'hello-elementor-child' );
				echo '</span>';
			}
			echo '</div>';
			if ( '' !== $caption ) {
				echo '<figcaption class="heb-qual-carousel__caption">' . esc_html( $caption ) . '</figcaption>';
			}
			echo '</figure>';
			echo '</article>';
			echo '</div>';
		}

		echo '</div></div></div>';

		echo '<button type="button" class="heb-qual-carousel__nav heb-qual-carousel__nav--next swiper-button-next" aria-label="' . esc_attr__( 'Next', 'hello-elementor-child' ) . '">';
		echo '<span class="heb-qual-carousel__nav-icon-wrap">';
		Icons_Manager::render_icon( $icon_next, [ 'aria-hidden' => 'true' ] );
		echo '</span>';
		echo '</button>';

		echo '</div></div>';
	}
}
