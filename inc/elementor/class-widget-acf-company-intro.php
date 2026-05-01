<?php
/**
 * Elementor: ACF company name + introduction (products CPT).
 *
 * @package HelloElementorChild
 */

namespace HelloElementorChild\Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Renders `company_name` and `company_introduction` from ACF.
 */
class Widget_ACF_Company_Intro extends Widget_Base {

	public function get_name() {
		return 'heb_acf_company_intro';
	}

	public function get_title() {
		return esc_html__( 'ACF Company intro · 企业介绍', 'hello-elementor-child' );
	}

	public function get_icon() {
		return 'eicon-person';
	}

	public function get_categories() {
		return [ 'heb-child' ];
	}

	public function get_keywords() {
		return [ 'acf', 'company', 'about', '企业', '介绍' ];
	}

	public function get_style_depends() {
		return [ 'heb-acf-company-feedback' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'ACF', 'hello-elementor-child' ),
			]
		);

		$this->add_control(
			'name_field',
			[
				'label'       => esc_html__( 'Company name field', 'hello-elementor-child' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'company_name',
				'placeholder' => 'company_name',
			]
		);

		$this->add_control(
			'intro_field',
			[
				'label'       => esc_html__( 'Introduction field', 'hello-elementor-child' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'company_introduction',
				'placeholder' => 'company_introduction',
			]
		);

		$this->add_control(
			'post_id',
			[
				'label'       => esc_html__( 'Post ID (optional)', 'hello-elementor-child' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'step'        => 1,
				'description' => esc_html__( 'Leave empty to use the current post (single product template).', 'hello-elementor-child' ),
			]
		);

		$this->add_control(
			'require_products',
			[
				'label'        => esc_html__( 'Only on products post type', 'hello-elementor-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'No', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_section_heading',
			[
				'label'        => esc_html__( 'Show section heading', 'hello-elementor-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'No', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'section_heading_text',
			[
				'label'       => esc_html__( 'Section heading', 'hello-elementor-child' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Company', 'hello-elementor-child' ),
				'label_block' => true,
				'condition'   => [
					'show_section_heading' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_box',
			[
				'label' => esc_html__( 'Box', 'hello-elementor-child' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'box_bg',
			[
				'label'     => esc_html__( 'Background', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f7f8fa',
				'selectors' => [
					'{{WRAPPER}} .heb-acf-company' => '--heb-cf-bg: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'box_border',
			[
				'label'     => esc_html__( 'Border', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e4e8ef',
				'selectors' => [
					'{{WRAPPER}} .heb-acf-company' => '--heb-cf-border: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'accent_color',
			[
				'label'     => esc_html__( 'Accent bar', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1a2f6e',
				'selectors' => [
					'{{WRAPPER}} .heb-acf-company' => '--heb-cf-navy: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_radius',
			[
				'label'      => esc_html__( 'Radius', 'hello-elementor-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 24 ],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 6,
				],
				'selectors'  => [
					'{{WRAPPER}} .heb-acf-company' => '--heb-cf-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label'      => esc_html__( 'Padding', 'hello-elementor-child' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem' ],
				'default'    => [
					'top' => 24,
					'right' => 28,
					'bottom' => 24,
					'left' => 28,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .heb-acf-company' => '--heb-cf-pad: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_typo',
			[
				'label' => esc_html__( 'Typography', 'hello-elementor-child' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Text color', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1a1a1a',
				'selectors' => [
					'{{WRAPPER}} .heb-acf-company' => '--heb-cf-text: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'name_typography',
				'label'    => esc_html__( 'Company name', 'hello-elementor-child' ),
				'selector' => '{{WRAPPER}} .heb-acf-company__name',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'intro_typography',
				'label'    => esc_html__( 'Introduction', 'hello-elementor-child' ),
				'selector' => '{{WRAPPER}} .heb-acf-company__intro',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_section_heading',
			[
				'label'     => esc_html__( 'Section heading', 'hello-elementor-child' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_section_heading' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'section_heading_typography',
				'selector' => '{{WRAPPER}} .heb-acf-company__heading',
			]
		);

		$this->add_control(
			'section_heading_color',
			[
				'label'     => esc_html__( 'Color', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .heb-acf-company__heading' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Sanitize ACF field name (alphanumeric + underscore).
	 *
	 * @param string $name Raw name.
	 * @param string $fallback Default.
	 * @return string
	 */
	private function sanitize_field_name( $name, $fallback ) {
		$name = is_string( $name ) ? $name : '';
		$name = preg_replace( '/[^a-z0-9_]/i', '', $name );
		return '' !== $name ? $name : $fallback;
	}

	/**
	 * Resolve post ID for ACF.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @return int
	 */
	private function resolve_post_id( array $settings ) {
		if ( ! empty( $settings['post_id'] ) ) {
			return absint( $settings['post_id'] );
		}
		$pid = get_queried_object_id();
		if ( $pid ) {
			return (int) $pid;
		}
		$tid = get_the_ID();
		return $tid ? (int) $tid : 0;
	}

	protected function render() {
		if ( ! function_exists( 'get_field' ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="heb-acf-company__empty">' . esc_html__( 'ACF is not active.', 'hello-elementor-child' ) . '</div>';
			}
			return;
		}

		$settings = $this->get_settings_for_display();
		$post_id  = $this->resolve_post_id( $settings );

		if ( ! empty( $settings['require_products'] ) && 'yes' === $settings['require_products'] ) {
			if ( ! $post_id || 'products' !== get_post_type( $post_id ) ) {
				if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
					echo '<div class="heb-acf-company__empty">' . esc_html__( 'Use this widget on a single product, or turn off “Only on products post type”.', 'hello-elementor-child' ) . '</div>';
				}
				return;
			}
		}

		$name_key  = $this->sanitize_field_name( isset( $settings['name_field'] ) ? $settings['name_field'] : '', 'company_name' );
		$intro_key = $this->sanitize_field_name( isset( $settings['intro_field'] ) ? $settings['intro_field'] : '', 'company_introduction' );

		$name  = $post_id ? get_field( $name_key, $post_id, false ) : '';
		$intro = $post_id ? get_field( $intro_key, $post_id, false ) : '';

		$name  = is_string( $name ) ? trim( $name ) : '';
		$intro = is_string( $intro ) ? trim( $intro ) : '';

		if ( '' === $name && '' === $intro ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="heb-acf-company__empty">' . esc_html__( 'No company name or introduction for this product. Fill ACF fields in the editor.', 'hello-elementor-child' ) . '</div>';
			}
			return;
		}

		$show_h = ! empty( $settings['show_section_heading'] ) && 'yes' === $settings['show_section_heading'];
		$h      = isset( $settings['section_heading_text'] ) ? $settings['section_heading_text'] : '';

		?>
		<div class="heb-acf-company">
			<?php if ( $show_h && '' !== $h ) : ?>
				<h2 class="heb-acf-company__heading"><?php echo esc_html( $h ); ?></h2>
			<?php endif; ?>
			<div class="heb-acf-company__inner">
				<div class="heb-acf-company__accent" aria-hidden="true"></div>
				<div class="heb-acf-company__body">
					<?php if ( '' !== $name ) : ?>
						<h3 class="heb-acf-company__name"><?php echo esc_html( $name ); ?></h3>
					<?php endif; ?>
					<?php if ( '' !== $intro ) : ?>
						<div class="heb-acf-company__intro"><?php echo apply_filters( 'the_content', $intro ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
	}
}
