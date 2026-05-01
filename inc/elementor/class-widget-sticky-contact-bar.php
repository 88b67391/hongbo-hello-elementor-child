<?php
/**
 * Elementor: Sticky contact rail (WhatsApp-style edge tabs).
 *
 * @package HelloElementorChild
 */

namespace HelloElementorChild\Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fixed vertical contact buttons with hover-expand labels.
 */
class Widget_Sticky_Contact_Bar extends Widget_Base {

	public function get_name() {
		return 'heb_sticky_contact_bar';
	}

	public function get_title() {
		return esc_html__( 'Sticky Contact Bar · 固定联系条', 'hello-elementor-child' );
	}

	public function get_icon() {
		return 'eicon-call-to-action';
	}

	public function get_categories() {
		return [ 'heb-child' ];
	}

	public function get_keywords() {
		return [ 'contact', 'whatsapp', 'sticky', 'social', 'float', 'bar', 'hongbo', '联系', '客服' ];
	}

	public function get_style_depends() {
		return [ 'heb-sticky-contact-bar' ];
	}

	public function get_script_depends() {
		return [ 'heb-sticky-contact-bar' ];
	}

	/**
	 * Default rows: 首页 / 产品 / 询盘 / WhatsApp（新插入的小工具；已保存的模板不受影响）。
	 *
	 * @return array<int, array<string, mixed>>
	 */
	private function get_default_contact_items() {
		$products_url = function_exists( 'get_post_type_archive_link' ) ? get_post_type_archive_link( 'products' ) : '';
		if ( ! is_string( $products_url ) || '' === $products_url ) {
			$products_url = home_url( '/products/' );
		}

		return [
			[
				'item_preset'  => 'home',
				'item_label'   => '首页',
				'item_link'    => [ 'url' => home_url( '/' ) ],
				'expand_label' => 'yes',
			],
			[
				'item_preset'  => 'globe',
				'item_label'   => '产品',
				'item_link'    => [ 'url' => $products_url ],
				'expand_label' => 'yes',
			],
			[
				'item_preset'  => 'phone',
				'item_label'   => '询盘',
				'item_link'    => [ 'url' => home_url( '/contact/' ) ],
				'expand_label' => 'yes',
			],
			[
				'item_preset'  => 'whatsapp',
				'item_label'   => 'WhatsApp',
				'item_link'    => [ 'url' => 'https://wa.me/' ],
				'expand_label' => 'yes',
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

		$repeater = new Repeater();

		$repeater->add_control(
			'item_preset',
			[
				'label'   => esc_html__( 'Icon preset', 'hello-elementor-child' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'whatsapp',
				'options' => [
					'home'      => esc_html__( 'Home / 首页', 'hello-elementor-child' ),
					'whatsapp'  => 'WhatsApp',
					'messenger' => 'Messenger',
					'phone'     => esc_html__( 'Phone / 电话', 'hello-elementor-child' ),
					'globe'     => esc_html__( 'Globe / site', 'hello-elementor-child' ),
					'custom'    => esc_html__( 'Custom (Elementor icon)', 'hello-elementor-child' ),
				],
			]
		);

		$repeater->add_control(
			'item_icon',
			[
				'label'     => esc_html__( 'Icon', 'hello-elementor-child' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-comment',
					'library' => 'fa-solid',
				],
				'condition' => [
					'item_preset' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'item_label',
			[
				'label'       => esc_html__( 'Label', 'hello-elementor-child' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'WhatsApp',
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'item_link',
			[
				'label'       => esc_html__( 'Link', 'hello-elementor-child' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://',
				'default'     => [
					'url' => '',
				],
			]
		);

		$repeater->add_control(
			'expand_label',
			[
				'label'        => esc_html__( 'Always show label', 'hello-elementor-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'No', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => esc_html__( 'If off, label shows on hover (desktop). Mobile bottom bar always shows labels.', 'hello-elementor-child' ),
			]
		);

		$this->add_control(
			'items',
			[
				'label'       => esc_html__( 'Contact items', 'hello-elementor-child' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => $this->get_default_contact_items(),
				'title_field' => '{{{ item_label }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'hello-elementor-child' ),
			]
		);

		$this->add_control(
			'side',
			[
				'label'   => esc_html__( 'Side', 'hello-elementor-child' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'right' => [
						'title' => esc_html__( 'Right', 'hello-elementor-child' ),
						'icon'  => 'eicon-h-align-right',
					],
					'left'  => [
						'title' => esc_html__( 'Left', 'hello-elementor-child' ),
						'icon'  => 'eicon-h-align-left',
					],
				],
				'default' => 'right',
				'toggle'  => false,
			]
		);

		$this->add_control(
			'vertical_align',
			[
				'label'   => esc_html__( 'Vertical position', 'hello-elementor-child' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => [
					'center' => esc_html__( 'Middle', 'hello-elementor-child' ),
					'top'    => esc_html__( 'Top', 'hello-elementor-child' ),
					'bottom' => esc_html__( 'Bottom', 'hello-elementor-child' ),
				],
			]
		);

		$this->add_responsive_control(
			'offset_y',
			[
				'label'      => esc_html__( 'Offset from top / bottom', 'hello-elementor-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 300 ],
					'vh' => [ 'min' => 0, 'max' => 50 ],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 120,
				],
				'selectors'  => [
					'{{WRAPPER}} .heb-sticky-contact.is-valign-top'    => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
					'{{WRAPPER}} .heb-sticky-contact.is-valign-bottom' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
				],
				'condition'  => [
					'vertical_align!' => 'center',
				],
			]
		);

		$this->add_responsive_control(
			'stack_zindex',
			[
				'label'     => esc_html__( 'Z-index', 'hello-elementor-child' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 999999,
				'step'      => 1,
				'default'   => 99998,
				'selectors' => [
					'{{WRAPPER}} .heb-sticky-contact' => 'z-index: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'mobile_bottom_bar',
			[
				'label'        => esc_html__( 'Mobile: bottom tab bar', 'hello-elementor-child' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Screens ≤767px: full-width strip at bottom, icon + label (app style). Desktop keeps side rail.', 'hello-elementor-child' ),
				'label_on'     => esc_html__( 'On', 'hello-elementor-child' ),
				'label_off'    => esc_html__( 'Off', 'hello-elementor-child' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'mobile_bar_look',
			[
				'label'     => esc_html__( 'Mobile bar look', 'hello-elementor-child' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'dark',
				'options'   => [
					'dark'     => esc_html__( 'Dark strip', 'hello-elementor-child' ),
					'segments' => esc_html__( 'Colored items (like PC)', 'hello-elementor-child' ),
				],
				'condition' => [
					'mobile_bottom_bar' => 'yes',
				],
			]
		);

		$this->add_control(
			'mobile_bar_bg',
			[
				'label'       => esc_html__( 'Mobile bar background', 'hello-elementor-child' ),
				'description' => esc_html__( 'Only when Color scheme is Custom. Green/blue presets use a matching dark strip.', 'hello-elementor-child' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#16161a',
				'condition'   => [
					'mobile_bottom_bar' => 'yes',
					'mobile_bar_look'   => 'dark',
					'color_scheme'      => 'custom',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_items',
			[
				'label' => esc_html__( 'Items', 'hello-elementor-child' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'color_scheme',
			[
				'label'       => esc_html__( 'Color scheme', 'hello-elementor-child' ),
				'description' => esc_html__( 'For the classic green docked pills (like WhatsApp green + white icons), choose “Green & white”. “Blue & white” is a separate look. “Custom” uses the color pickers below.', 'hello-elementor-child' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'green',
				'options'     => [
					'green'  => esc_html__( 'Green & white · 绿底白字（贴边胶囊）', 'hello-elementor-child' ),
					'blue'   => esc_html__( 'Blue & white · 蓝底白字', 'hello-elementor-child' ),
					'custom' => esc_html__( 'Custom · 自定义', 'hello-elementor-child' ),
				],
			]
		);

		$this->start_controls_tabs( 'tabs_item_states' );

		$this->start_controls_tab(
			'tab_item_normal',
			[
				'label' => esc_html__( 'Normal', 'hello-elementor-child' ),
			]
		);

		$this->add_control(
			'bar_color',
			[
				'label'     => esc_html__( 'Background', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#25d366',
				'selectors' => [
					'{{WRAPPER}} .heb-sticky-contact' => '--heb-sc-bg: {{VALUE}};',
				],
				'condition' => [
					'color_scheme' => 'custom',
				],
			]
		);

		$this->add_control(
			'fg_color',
			[
				'label'     => esc_html__( 'Text & icon', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .heb-sticky-contact' => '--heb-sc-fg: {{VALUE}};',
				],
				'condition' => [
					'color_scheme' => 'custom',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'item_shadow',
				'selector' => '{{WRAPPER}} .heb-sticky-contact__item',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_item_hover',
			[
				'label' => esc_html__( 'Hover', 'hello-elementor-child' ),
			]
		);

		$this->add_control(
			'bar_color_hover',
			[
				'label'       => esc_html__( 'Background', 'hello-elementor-child' ),
				'description' => esc_html__( 'Leave empty to keep the same as normal.', 'hello-elementor-child' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'{{WRAPPER}} .heb-sticky-contact__item:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .heb-sticky-contact__item:focus-visible' => 'background-color: {{VALUE}};',
				],
				'condition'   => [
					'color_scheme' => 'custom',
				],
			]
		);

		$this->add_control(
			'fg_color_hover',
			[
				'label'       => esc_html__( 'Text & icon', 'hello-elementor-child' ),
				'description' => esc_html__( 'Leave empty to keep the same as normal.', 'hello-elementor-child' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => [
					'{{WRAPPER}} .heb-sticky-contact__item:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .heb-sticky-contact__item:focus-visible' => 'color: {{VALUE}};',
				],
				'condition'   => [
					'color_scheme' => 'custom',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'item_shadow_hover',
				'selector' => '{{WRAPPER}} .heb-sticky-contact__item:hover, {{WRAPPER}} .heb-sticky-contact__item:focus-visible',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'corner_radius',
			[
				'label'      => esc_html__( 'Corner radius', 'hello-elementor-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 100 ],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 48,
				],
				'selectors'  => [
					'{{WRAPPER}} .heb-sticky-contact' => '--heb-sc-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'      => esc_html__( 'Icon size', 'hello-elementor-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => 12, 'max' => 40 ],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 22,
				],
				'selectors'  => [
					'{{WRAPPER}} .heb-sticky-contact' => '--heb-sc-icon: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_gap',
			[
				'label'      => esc_html__( 'Gap between items', 'hello-elementor-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 32 ],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors'  => [
					'{{WRAPPER}} .heb-sticky-contact' => '--heb-sc-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'label_typography',
				'label'    => esc_html__( 'Label typography', 'hello-elementor-child' ),
				'selector' => '{{WRAPPER}} .heb-sticky-contact__label',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Built-in SVG icons (currentColor).
	 *
	 * @param string $preset Preset slug.
	 * @return string Safe HTML.
	 */
	private function get_preset_icon_html( $preset ) {
		$svgs = [
			'home'      => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" focusable="false" aria-hidden="true"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>',
			'whatsapp'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" focusable="false" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>',
			'messenger' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" focusable="false" aria-hidden="true"><path d="M12 2C6.48 2 2 6.02 2 10.95c0 2.55 1.19 4.84 3.07 6.36V22l4.09-2.24h2.84C17.52 19.76 22 15.74 22 10.95 22 6.02 17.52 2 12 2zm.93 12.49l-2.43-2.58-4.76 2.58 5.23-5.55 2.46 2.57 4.7-2.57-5.2 5.55z"/></svg>',
			'phone'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" focusable="false" aria-hidden="true"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>',
			'globe'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" focusable="false" aria-hidden="true"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>',
		];

		return isset( $svgs[ $preset ] ) ? $svgs[ $preset ] : $svgs['home'];
	}

	/**
	 * @param array<string,mixed> $item Repeater row.
	 * @return string
	 */
	private function render_item_icon( array $item ) {
		$preset = isset( $item['item_preset'] ) ? $item['item_preset'] : 'whatsapp';

		$icon = isset( $item['item_icon'] ) && is_array( $item['item_icon'] ) ? $item['item_icon'] : [];
		if ( 'custom' === $preset && class_exists( Icons_Manager::class ) && ! empty( $icon['value'] ) ) {
			ob_start();
			Icons_Manager::render_icon(
				$icon,
				[
					'aria-hidden' => 'true',
					'class'       => 'heb-sticky-contact__icon-el',
				]
			);
			$out = ob_get_clean();
			if ( '' !== $out ) {
				return '<span class="heb-sticky-contact__icon heb-sticky-contact__icon--el">' . $out . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor icon HTML.
			}
		}

		return '<span class="heb-sticky-contact__icon">' . $this->get_preset_icon_html( $preset ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG.
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$items    = isset( $settings['items'] ) && is_array( $settings['items'] ) ? $settings['items'] : [];

		if ( empty( $items ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<p class="heb-sticky-contact__empty">' . esc_html__( 'Add at least one contact item.', 'hello-elementor-child' ) . '</p>';
			}
			return;
		}

		$side   = isset( $settings['side'] ) && 'left' === $settings['side'] ? 'left' : 'right';
		$valign = isset( $settings['vertical_align'] ) ? $settings['vertical_align'] : 'center';
		if ( ! in_array( $valign, [ 'top', 'center', 'bottom' ], true ) ) {
			$valign = 'center';
		}

		$wrap_classes = [ 'heb-sticky-contact', 'is-side-' . $side, 'is-valign-' . $valign ];

		$mobile_bottom = ! isset( $settings['mobile_bottom_bar'] ) || 'yes' === $settings['mobile_bottom_bar'];
		if ( $mobile_bottom ) {
			$wrap_classes[] = 'heb-sticky-contact--mobile-bottom';
			if ( empty( $settings['mobile_bar_look'] ) || 'dark' === $settings['mobile_bar_look'] ) {
				$wrap_classes[] = 'heb-sticky-contact--mobile-dark';
			} else {
				$wrap_classes[] = 'heb-sticky-contact--mobile-segments';
			}
		}

		// Empty = widgets saved before this control existed; keep Custom + Elementor colors.
		$scheme_raw = isset( $settings['color_scheme'] ) ? (string) $settings['color_scheme'] : '';
		$scheme     = sanitize_key( $scheme_raw );
		if ( '' === $scheme || ! in_array( $scheme, [ 'green', 'blue', 'custom' ], true ) ) {
			$scheme = 'custom';
		}
		if ( 'custom' !== $scheme ) {
			$wrap_classes[] = 'heb-sticky-contact--scheme-' . $scheme;
		}

		$scheme_style = '';
		if ( 'green' === $scheme ) {
			$scheme_style = '--heb-sc-bg:#25d366;--heb-sc-fg:#ffffff;--heb-sc-mobile-strip-bg:#0d2818;--heb-sc-shadow:-4px 5px 22px rgba(37,211,102,0.3);';
		} elseif ( 'blue' === $scheme ) {
			$scheme_style = '--heb-sc-bg:#2563eb;--heb-sc-fg:#ffffff;--heb-sc-mobile-strip-bg:#0c1929;--heb-sc-shadow:-4px 5px 22px rgba(37,99,235,0.28);';
		}

		$this->add_render_attribute(
			'wrap',
			[
				'class'                   => implode( ' ', $wrap_classes ),
				'data-heb-sticky-contact' => '1',
			]
		);

		if ( '' !== $scheme_style ) {
			$this->add_render_attribute( 'wrap', 'style', $scheme_style );
		}

		if ( $mobile_bottom
			&& ( empty( $settings['mobile_bar_look'] ) || 'dark' === $settings['mobile_bar_look'] )
			&& 'custom' === $scheme
			&& ! empty( $settings['mobile_bar_bg'] )
		) {
			$eid = 'elementor-element-' . $this->get_id();
			printf(
				'<style>@media (max-width: 767px) { #%1$s .heb-sticky-contact.heb-sticky-contact--mobile-dark { background-color: %2$s !important; } }</style>',
				esc_attr( $eid ),
				esc_attr( $settings['mobile_bar_bg'] )
			);
		}

		echo '<div ' . $this->get_render_attribute_string( 'wrap' ) . '>';

		foreach ( $items as $row ) {
			$label = isset( $row['item_label'] ) ? $row['item_label'] : '';
			$link  = isset( $row['item_link'] ) && is_array( $row['item_link'] ) ? $row['item_link'] : [];

			$url = isset( $link['url'] ) ? $link['url'] : '';
			if ( '' === $url ) {
				$url = '#';
			}

			$target = ! empty( $link['is_external'] ) ? ' target="_blank"' : '';

			$rel = [];
			if ( ! empty( $link['is_external'] ) ) {
				$rel[] = 'noopener';
			}
			if ( ! empty( $link['nofollow'] ) ) {
				$rel[] = 'nofollow';
			}
			$rel = $rel ? ' rel="' . esc_attr( implode( ' ', array_unique( $rel ) ) ) . '"' : '';

			$always       = ! empty( $row['expand_label'] ) && 'yes' === $row['expand_label'];
			$item_classes = 'heb-sticky-contact__item';
			if ( $always ) {
				$item_classes .= ' is-always-expanded';
			}

			$aria = $label ? $label : esc_html__( 'Contact', 'hello-elementor-child' );

			echo '<a class="' . esc_attr( $item_classes ) . '" href="' . esc_url( $url ) . '"' . $target . $rel; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $target is literal.
			echo ' data-heb-contact-item="1"';
			echo ' aria-label="' . esc_attr( $aria ) . '"';
			echo ' title="' . esc_attr( $aria ) . '">';
			echo '<span class="heb-sticky-contact__label">' . esc_html( $label ) . '</span>';
			echo $this->render_item_icon( $row ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</a>';
		}

		echo '</div>';
	}
}
