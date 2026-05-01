<?php
/**
 * Elementor: Feature / value cards (icon, title, description) with hover.
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
 * Blue/white value cards; hover inverts icon capsule (navy + white icon) and lifts card.
 */
class Widget_Feature_Value_Grid extends Widget_Base {

	public function get_name() {
		return 'heb_feature_value_grid';
	}

	public function get_title() {
		return esc_html__( 'Feature / Values Grid · 价值观网格', 'hello-elementor-child' );
	}

	public function get_icon() {
		return 'eicon-icon-box';
	}

	public function get_categories() {
		return [ 'heb-child' ];
	}

	public function get_keywords() {
		return [ 'feature', 'values', 'icon', 'grid', 'about', '价值观' ];
	}

	public function get_style_depends() {
		return [ 'heb-content-widgets' ];
	}

	/**
	 * @return array<int, array<string, mixed>>
	 */
	private function default_items() {
		return [
			[
				'item_title'       => 'Innovation',
				'item_description' => 'Continuous R&D investment…',
				'item_icon'        => [
					'value'   => 'fas fa-layer-group',
					'library' => 'fa-solid',
				],
			],
			[
				'item_title'       => 'Quality',
				'item_description' => 'Rigorous quality control…',
				'item_icon'        => [
					'value'   => 'fas fa-circle-check',
					'library' => 'fa-solid',
				],
			],
			[
				'item_title'       => 'Integrity',
				'item_description' => 'Transparent business practices…',
				'item_icon'        => [
					'value'   => 'fas fa-comment-dots',
					'library' => 'fa-solid',
				],
			],
			[
				'item_title'       => 'Sustainability',
				'item_description' => 'Commitment to eco-friendly…',
				'item_icon'        => [
					'value'   => 'fas fa-shield-halved',
					'library' => 'fa-solid',
				],
			],
		];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_items',
			[
				'label' => esc_html__( 'Items', 'hello-elementor-child' ),
			]
		);

		$rep = new Repeater();
		$rep->add_control(
			'item_icon',
			[
				'label'   => esc_html__( 'Icon', 'hello-elementor-child' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				],
			]
		);
		$rep->add_control(
			'item_title',
			[
				'label'       => esc_html__( 'Title', 'hello-elementor-child' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Title', 'hello-elementor-child' ),
				'label_block' => true,
			]
		);
		$rep->add_control(
			'item_description',
			[
				'label'       => esc_html__( 'Description', 'hello-elementor-child' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'rows'        => 3,
				'label_block' => true,
			]
		);

		$this->add_control(
			'items',
			[
				'label'       => esc_html__( 'Cards', 'hello-elementor-child' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $rep->get_controls(),
				'default'     => $this->default_items(),
				'title_field' => '{{{ item_title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'hello-elementor-child' ),
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'          => esc_html__( 'Columns', 'hello-elementor-child' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '4',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'selectors'      => [
					'{{WRAPPER}} .heb-fvg__grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label'      => esc_html__( 'Row gap', 'hello-elementor-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
				'default'    => [ 'size' => 20, 'unit' => 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .heb-fvg__grid' => 'row-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label'      => esc_html__( 'Column gap', 'hello-elementor-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
				'default'    => [ 'size' => 20, 'unit' => 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .heb-fvg__grid' => 'column-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Card', 'hello-elementor-child' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'card_bg',
			[
				'label'     => esc_html__( 'Background', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .heb-fvg__card' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'card_border_color',
			[
				'label'     => esc_html__( 'Border', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e8ecf1',
				'selectors' => [
					'{{WRAPPER}} .heb-fvg__card' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'card_padding',
			[
				'label'      => esc_html__( 'Card padding', 'hello-elementor-child' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [
					'top'      => '20',
					'right'    => '18',
					'bottom'   => '18',
					'left'     => '18',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .heb-fvg__card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_box_size',
			[
				'label'      => esc_html__( 'Icon box size', 'hello-elementor-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [ 'px' => [ 'min' => 36, 'max' => 96 ] ],
				'default'    => [ 'size' => 56, 'unit' => 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .heb-fvg__icon-wrap' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_box_bg',
			[
				'label'     => esc_html__( 'Icon box background', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f0f7ff',
				'selectors' => [
					'{{WRAPPER}} .heb-fvg__icon-wrap' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'       => esc_html__( 'Icon color', 'hello-elementor-child' ),
				'description' => esc_html__( 'Applies to SVG and font icons (overrides hard-coded SVG fills).', 'hello-elementor-child' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#003366',
				'selectors'   => [
					'{{WRAPPER}} .heb-fvg__icon-wrap' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title color', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1a1a1a',
				'selectors' => [
					'{{WRAPPER}} .heb-fvg__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label'     => esc_html__( 'Description color', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#666666',
				'selectors' => [
					'{{WRAPPER}} .heb-fvg__desc' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_accent',
			[
				'label'       => esc_html__( 'Hover: icon box & border accent', 'hello-elementor-child' ),
				'description' => esc_html__( 'Icon area fills this color; icon turns white.', 'hello-elementor-child' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#003366',
				'selectors'   => [
					'{{WRAPPER}} .heb-fvg__card:hover' => '--heb-fvg-hover-accent: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Title typography', 'hello-elementor-child' ),
				'selector' => '{{WRAPPER}} .heb-fvg__title',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'desc_typography',
				'label'    => esc_html__( 'Description typography', 'hello-elementor-child' ),
				'selector' => '{{WRAPPER}} .heb-fvg__desc',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @param array<string,mixed> $item Repeater row.
	 */
	private function render_icon( array $item ) {
		$icon = isset( $item['item_icon'] ) && is_array( $item['item_icon'] ) ? $item['item_icon'] : [];
		if ( class_exists( Icons_Manager::class ) && ! empty( $icon['value'] ) ) {
			ob_start();
			Icons_Manager::render_icon(
				$icon,
				[
					'aria-hidden' => 'true',
					'class'       => 'heb-fvg__icon-el',
				]
			);
			$html = ob_get_clean();
			if ( '' !== $html ) {
				return $html;
			}
		}
		return '';
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$items    = isset( $settings['items'] ) && is_array( $settings['items'] ) ? $settings['items'] : [];

		if ( empty( $items ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<p class="heb-fvg__empty">' . esc_html__( 'Add at least one card.', 'hello-elementor-child' ) . '</p>';
			}
			return;
		}

		echo '<div class="heb-fvg">';
		echo '<div class="heb-fvg__grid">';

		foreach ( $items as $row ) {
			$title = isset( $row['item_title'] ) ? $row['item_title'] : '';
			$desc  = isset( $row['item_description'] ) ? $row['item_description'] : '';
			$icon  = $this->render_icon( $row );

			echo '<article class="heb-fvg__card">';
			echo '<div class="heb-fvg__icon-wrap">';
			if ( '' !== $icon ) {
				echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor icon HTML.
			}
			echo '</div>';
			if ( '' !== $title ) {
				echo '<h3 class="heb-fvg__title">' . esc_html( $title ) . '</h3>';
			}
			if ( '' !== $desc ) {
				echo '<p class="heb-fvg__desc">' . esc_html( $desc ) . '</p>';
			}
			echo '</article>';
		}

		echo '</div></div>';
	}
}
