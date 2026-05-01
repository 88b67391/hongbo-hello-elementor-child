<?php
/**
 * Elementor: ACF FAQ list (textarea Q|A per line).
 *
 * @package HelloElementorChild
 */

namespace HelloElementorChild\Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Renders FAQ from ACF `faq_list` using native details/summary.
 */
class Widget_ACF_FAQ extends Widget_Base {

	public function get_name() {
		return 'heb_acf_faq';
	}

	public function get_title() {
		return esc_html__( 'ACF FAQ · 常见问题', 'hello-elementor-child' );
	}

	public function get_icon() {
		return 'eicon-toggle';
	}

	public function get_categories() {
		return [ 'heb-child' ];
	}

	public function get_keywords() {
		return [ 'acf', 'faq', 'accordion', 'question', '常见问题' ];
	}

	public function get_style_depends() {
		return [ 'heb-acf-faq' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'ACF', 'hello-elementor-child' ),
			]
		);

		$this->add_control(
			'acf_field_name',
			[
				'label'       => esc_html__( 'FAQ field name', 'hello-elementor-child' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'faq_list',
				'placeholder' => 'faq_list',
				'description' => esc_html__( 'Textarea field: one line per Q&A, separated by |', 'hello-elementor-child' ),
			]
		);

		$this->add_control(
			'show_heading',
			[
				'label'        => esc_html__( 'Show heading', 'hello-elementor-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'No', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'heading_text',
			[
				'label'       => esc_html__( 'Heading', 'hello-elementor-child' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Frequently asked questions', 'hello-elementor-child' ),
				'label_block' => true,
				'condition'   => [
					'show_heading' => 'yes',
				],
			]
		);

		$this->add_control(
			'first_item_open',
			[
				'label'        => esc_html__( 'First item open by default', 'hello-elementor-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'No', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_box',
			[
				'label' => esc_html__( 'Items', 'hello-elementor-child' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'item_gap',
			[
				'label'      => esc_html__( 'Gap between items', 'hello-elementor-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 40 ],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors'  => [
					'{{WRAPPER}} .heb-acf-faq' => '--heb-faq-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'item_bg',
			[
				'label'     => esc_html__( 'Background', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .heb-acf-faq' => '--heb-faq-item-bg: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_radius',
			[
				'label'      => esc_html__( 'Border radius', 'hello-elementor-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 24 ],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors'  => [
					'{{WRAPPER}} .heb-acf-faq' => '--heb-faq-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'item_border',
				'selector' => '{{WRAPPER}} .heb-acf-faq__item',
			]
		);

		$this->add_control(
			'open_shadow',
			[
				'label'        => esc_html__( 'Shadow when open', 'hello-elementor-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'Off', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'open_shadow_value',
			[
				'label'     => esc_html__( 'Open shadow', 'hello-elementor-child' ),
				'type'      => Controls_Manager::BOX_SHADOW,
				'default'   => [
					'horizontal' => 0,
					'vertical'   => 4,
					'blur'       => 14,
					'spread'     => 0,
					'color'      => 'rgba(0,0,0,0.08)',
				],
				'selectors' => [
					'{{WRAPPER}} .heb-acf-faq__item[open]' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
				],
				'condition' => [
					'open_shadow' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_heading',
			[
				'label'     => esc_html__( 'Heading', 'hello-elementor-child' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_heading' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'heading_spacing',
			[
				'label'      => esc_html__( 'Spacing below heading', 'hello-elementor-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 48 ],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 16,
				],
				'selectors'  => [
					'{{WRAPPER}} .heb-acf-faq' => '--heb-faq-heading-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} .heb-acf-faq__heading',
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label'     => esc_html__( 'Color', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .heb-acf-faq__heading' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_question',
			[
				'label' => esc_html__( 'Question', 'hello-elementor-child' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'question_padding',
			[
				'label'      => esc_html__( 'Padding', 'hello-elementor-child' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [
					'top' => 14,
					'right' => 16,
					'bottom' => 14,
					'left' => 16,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .heb-acf-faq' => '--heb-faq-q-pad: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'question_typography',
				'selector' => '{{WRAPPER}} .heb-acf-faq__item summary',
			]
		);

		$this->add_control(
			'question_color',
			[
				'label'     => esc_html__( 'Color', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .heb-acf-faq' => '--heb-faq-q-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_answer',
			[
				'label' => esc_html__( 'Answer', 'hello-elementor-child' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'answer_padding_x',
			[
				'label'      => esc_html__( 'Horizontal padding', 'hello-elementor-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 48 ],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 16,
				],
				'selectors'  => [
					'{{WRAPPER}} .heb-acf-faq' => '--heb-faq-a-pad-x: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'answer_padding_bottom',
			[
				'label'      => esc_html__( 'Bottom padding', 'hello-elementor-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 48 ],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 16,
				],
				'selectors'  => [
					'{{WRAPPER}} .heb-acf-faq' => '--heb-faq-a-pad-b: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'answer_typography',
				'selector' => '{{WRAPPER}} .heb-acf-faq__a',
			]
		);

		$this->add_control(
			'answer_color',
			[
				'label'     => esc_html__( 'Color', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#444444',
				'selectors' => [
					'{{WRAPPER}} .heb-acf-faq' => '--heb-faq-a-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Post ID for ACF get_field.
	 */
	private function resolve_post_id() {
		$post_id = get_the_ID();
		if ( $post_id ) {
			return (int) $post_id;
		}
		$qid = get_queried_object_id();
		return $qid ? (int) $qid : 0;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$field    = isset( $settings['acf_field_name'] ) ? sanitize_text_field( $settings['acf_field_name'] ) : 'faq_list';
		$field    = preg_replace( '/[^a-z0-9_\-]/i', '', $field );
		if ( '' === $field ) {
			$field = 'faq_list';
		}

		$post_id = $this->resolve_post_id();
		$raw     = '';

		if ( function_exists( 'get_field' ) && $post_id ) {
			$v = get_field( $field, $post_id, false );
			$raw = is_string( $v ) ? $v : '';
		}

		$rows = function_exists( 'hello_elementor_child_parse_faq_list' )
			? hello_elementor_child_parse_faq_list( $raw )
			: [];

		if ( empty( $rows ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="heb-acf-faq__empty">';
				echo esc_html__( 'No FAQ entries for this post. Fill the ACF textarea (one Question|Answer per line).', 'hello-elementor-child' );
				echo '</div>';
			}
			return;
		}

		$first_open = ! empty( $settings['first_item_open'] ) && 'yes' === $settings['first_item_open'];
		$show_h     = ! empty( $settings['show_heading'] ) && 'yes' === $settings['show_heading'];
		$heading    = isset( $settings['heading_text'] ) ? $settings['heading_text'] : '';

		?>
		<div class="heb-acf-faq">
			<?php if ( $show_h && '' !== $heading ) : ?>
				<h2 class="heb-acf-faq__heading"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>
			<?php foreach ( $rows as $i => $row ) : ?>
				<?php
				$q = isset( $row['question'] ) ? $row['question'] : '';
				$a = isset( $row['answer'] ) ? $row['answer'] : '';
				$open = ( 0 === $i && $first_open );
				?>
				<details class="heb-acf-faq__item"<?php echo $open ? ' open' : ''; ?>>
					<summary class="heb-acf-faq__q"><?php echo esc_html( $q ); ?></summary>
					<div class="heb-acf-faq__a"><?php echo nl2br( esc_html( $a ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped before nl2br ?></div>
				</details>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
