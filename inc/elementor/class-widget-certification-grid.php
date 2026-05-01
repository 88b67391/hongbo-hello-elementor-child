<?php
/**
 * Elementor: Certification / badge grid (heading + sub) with hover.
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
 * Square cells: bold acronym + gray subtitle; hover border + shadow.
 */
class Widget_Certification_Grid extends Widget_Base {

	public function get_name() {
		return 'heb_certification_grid';
	}

	public function get_title() {
		return esc_html__( 'Certification Grid · 认证网格', 'hello-elementor-child' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'heb-child' ];
	}

	public function get_keywords() {
		return [ 'cert', 'iso', 'badge', 'compliance', '认证' ];
	}

	public function get_style_depends() {
		return [ 'heb-content-widgets' ];
	}

	/**
	 * @return array<int, array<string, mixed>>
	 */
	private function default_items() {
		return [
			[ 'main_text' => 'ISO', 'sub_text' => 'ISO 9001' ],
			[ 'main_text' => '140', 'sub_text' => 'ISO 14001' ],
			[ 'main_text' => 'GRS', 'sub_text' => 'GRS Certified' ],
			[ 'main_text' => 'Oeko', 'sub_text' => 'OEKO-TEX' ],
			[ 'main_text' => 'REACH', 'sub_text' => 'REACH' ],
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
			'main_text',
			[
				'label'       => esc_html__( 'Heading', 'hello-elementor-child' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'ISO',
				'label_block' => true,
			]
		);
		$rep->add_control(
			'sub_text',
			[
				'label'       => esc_html__( 'Sub-heading', 'hello-elementor-child' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			]
		);

		$this->add_control(
			'items',
			[
				'label'       => esc_html__( 'Certifications', 'hello-elementor-child' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $rep->get_controls(),
				'default'     => $this->default_items(),
				'title_field' => '{{{ main_text }}}',
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
				'default'        => '5',
				'tablet_default' => '3',
				'mobile_default' => '2',
				'options'        => [
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'selectors'      => [
					'{{WRAPPER}} .heb-cert__grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
				],
			]
		);

		$this->add_responsive_control(
			'gap',
			[
				'label'      => esc_html__( 'Gap', 'hello-elementor-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [ 'px' => [ 'min' => 0, 'max' => 48 ] ],
				'default'    => [ 'size' => 16, 'unit' => 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .heb-cert__grid' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'square_cells',
			[
				'label'        => esc_html__( 'Square cells', 'hello-elementor-child' ),
				'description'  => esc_html__( 'Off: cell height follows text (compact row). On: 1:1 square like the original demo.', 'hello-elementor-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_responsive_control(
			'cell_min_height',
			[
				'label'      => esc_html__( 'Cell min height', 'hello-elementor-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [ 'px' => [ 'min' => 0, 'max' => 320 ] ],
				'default'    => [ 'size' => 0, 'unit' => 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .heb-cert__cell' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Cell', 'hello-elementor-child' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'cell_padding',
			[
				'label'      => esc_html__( 'Padding', 'hello-elementor-child' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [
					'top'      => '12',
					'right'    => '10',
					'bottom'   => '12',
					'left'     => '10',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .heb-cert__cell' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'cell_bg',
			[
				'label'     => esc_html__( 'Background', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .heb-cert__cell' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'border_color',
			[
				'label'     => esc_html__( 'Border', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eeeeee',
				'selectors' => [
					'{{WRAPPER}} .heb-cert__cell' => 'border: 2px solid {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label'     => esc_html__( 'Heading color', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#003366',
				'selectors' => [
					'{{WRAPPER}} .heb-cert__main' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sub_color',
			[
				'label'     => esc_html__( 'Sub-heading color', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#666666',
				'selectors' => [
					'{{WRAPPER}} .heb-cert__sub' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_border',
			[
				'label'     => esc_html__( 'Hover border', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#003366',
				'selectors' => [
					'{{WRAPPER}} .heb-cert__cell' => '--heb-cert-hover-border: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'main_typography',
				'label'    => esc_html__( 'Heading typography', 'hello-elementor-child' ),
				'selector' => '{{WRAPPER}} .heb-cert__main',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sub_typography',
				'label'    => esc_html__( 'Sub typography', 'hello-elementor-child' ),
				'selector' => '{{WRAPPER}} .heb-cert__sub',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$items    = isset( $settings['items'] ) && is_array( $settings['items'] ) ? $settings['items'] : [];

		if ( empty( $items ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<p class="heb-cert__empty">' . esc_html__( 'Add certifications in the widget settings.', 'hello-elementor-child' ) . '</p>';
			}
			return;
		}

		$wrap_classes = [ 'heb-cert' ];
		if ( ! empty( $settings['square_cells'] ) && 'yes' === $settings['square_cells'] ) {
			$wrap_classes[] = 'heb-cert--square-cells';
		}

		echo '<div class="' . esc_attr( implode( ' ', $wrap_classes ) ) . '">';
		echo '<div class="heb-cert__grid" role="list">';

		foreach ( $items as $row ) {
			$main = isset( $row['main_text'] ) ? $row['main_text'] : '';
			$sub  = isset( $row['sub_text'] ) ? $row['sub_text'] : '';

			echo '<div class="heb-cert__cell" role="listitem">';
			if ( '' !== $main ) {
				echo '<span class="heb-cert__main">' . esc_html( $main ) . '</span>';
			}
			if ( '' !== $sub ) {
				echo '<span class="heb-cert__sub">' . esc_html( $sub ) . '</span>';
			}
			echo '</div>';
		}

		echo '</div></div>';
	}
}
