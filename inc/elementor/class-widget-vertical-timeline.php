<?php
/**
 * Elementor: Vertical timeline (year + card) alternating, with hover.
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
 * Central spine + green year pills; cards alternate L/R; mobile stacks beside line.
 */
class Widget_Vertical_Timeline extends Widget_Base {

	public function get_name() {
		return 'heb_vertical_timeline';
	}

	public function get_title() {
		return esc_html__( 'Vertical Timeline · 垂直时间轴', 'hello-elementor-child' );
	}

	public function get_icon() {
		return 'eicon-time-line';
	}

	public function get_categories() {
		return [ 'heb-child' ];
	}

	public function get_keywords() {
		return [ 'timeline', 'history', 'milestones', '时间轴', '历程' ];
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
				'year'             => '2010',
				'item_title'       => 'Company Founded',
				'item_description' => 'Established in Suzhou with a focus on sustainable materials.',
			],
			[
				'year'             => '2013',
				'item_title'       => 'Capacity Expansion',
				'item_description' => 'Installed advanced German production lines and doubled output.',
			],
			[
				'year'             => '2018',
				'item_title'       => 'Global Certification',
				'item_description' => 'Achieved ISO 9001 / 14001 and expanded export markets.',
			],
			[
				'year'             => '2024',
				'item_title'       => 'Innovation Lab',
				'item_description' => 'Opened R&D lab for next-generation recycled fibers.',
			],
		];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_items',
			[
				'label' => esc_html__( 'Milestones', 'hello-elementor-child' ),
			]
		);

		$rep = new Repeater();
		$rep->add_control(
			'year',
			[
				'label'       => esc_html__( 'Year / label', 'hello-elementor-child' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '2010',
				'label_block' => true,
			]
		);
		$rep->add_control(
			'item_title',
			[
				'label'       => esc_html__( 'Title', 'hello-elementor-child' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Milestone title', 'hello-elementor-child' ),
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
				'label'       => esc_html__( 'Items', 'hello-elementor-child' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $rep->get_controls(),
				'default'     => $this->default_items(),
				'title_field' => '{{{ year }}} — {{{ item_title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Timeline', 'hello-elementor-child' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'spine_color',
			[
				'label'     => esc_html__( 'Center line', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1a2b44',
				'selectors' => [
					'{{WRAPPER}} .heb-tl__line' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'year_bg',
			[
				'label'     => esc_html__( 'Year badge background', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#27ae60',
				'selectors' => [
					'{{WRAPPER}} .heb-tl__year' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'year_color',
			[
				'label'     => esc_html__( 'Year text', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .heb-tl__year' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'accent_color',
			[
				'label'       => esc_html__( 'Card accent bar', 'hello-elementor-child' ),
				'description' => esc_html__( 'Thick bar on the outer edge of each card.', 'hello-elementor-child' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#27ae60',
				'selectors'   => [
					'{{WRAPPER}} .heb-tl__card' => '--heb-tl-accent: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'card_bg',
			[
				'label'     => esc_html__( 'Card background', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .heb-tl__card' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title color', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#003366',
				'selectors' => [
					'{{WRAPPER}} .heb-tl__title' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .heb-tl__desc' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Title typography', 'hello-elementor-child' ),
				'selector' => '{{WRAPPER}} .heb-tl__title',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'desc_typography',
				'label'    => esc_html__( 'Description typography', 'hello-elementor-child' ),
				'selector' => '{{WRAPPER}} .heb-tl__desc',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$items    = isset( $settings['items'] ) && is_array( $settings['items'] ) ? $settings['items'] : [];

		if ( empty( $items ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<p class="heb-tl__empty">' . esc_html__( 'Add timeline items.', 'hello-elementor-child' ) . '</p>';
			}
			return;
		}

		echo '<div class="heb-tl">';
		echo '<span class="heb-tl__line" aria-hidden="true"></span>';
		echo '<div class="heb-tl__rows">';

		foreach ( $items as $i => $row ) {
			$year  = isset( $row['year'] ) ? $row['year'] : '';
			$title = isset( $row['item_title'] ) ? $row['item_title'] : '';
			$desc  = isset( $row['item_description'] ) ? $row['item_description'] : '';
			$side  = ( 0 === ( $i % 2 ) ) ? 'left' : 'right';

			printf(
				'<div class="heb-tl__row heb-tl__row--%1$s">',
				esc_attr( $side )
			);

			$marker = '';
			if ( '' !== $year ) {
				$marker = '<div class="heb-tl__marker"><span class="heb-tl__year">' . esc_html( $year ) . '</span></div>';
			} else {
				$marker = '<div class="heb-tl__marker"></div>';
			}

			$card  = '<article class="heb-tl__card">';
			$card .= '' !== $title ? '<h3 class="heb-tl__title">' . esc_html( $title ) . '</h3>' : '';
			$card .= '' !== $desc ? '<p class="heb-tl__desc">' . esc_html( $desc ) . '</p>' : '';
			$card .= '</article>';

			$spacer = '<div class="heb-tl__spacer" aria-hidden="true"></div>';

			if ( 'left' === $side ) {
				echo $card . $marker . $spacer; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts.
			} else {
				echo $spacer . $marker . $card; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			echo '</div>';
		}

		echo '</div></div>';
	}
}
