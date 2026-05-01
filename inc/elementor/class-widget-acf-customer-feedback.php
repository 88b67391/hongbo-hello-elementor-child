<?php
/**
 * Elementor: ACF customer feedback repeater (products CPT).
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
 * Renders repeater `customer_feedback` with name, date, rating, content, gallery.
 */
class Widget_ACF_Customer_Feedback extends Widget_Base {

	public function get_name() {
		return 'heb_acf_customer_feedback';
	}

	public function get_title() {
		return esc_html__( 'ACF Customer feedback · 客户反馈', 'hello-elementor-child' );
	}

	public function get_icon() {
		return 'eicon-testimonial-carousel';
	}

	public function get_categories() {
		return [ 'heb-child' ];
	}

	public function get_keywords() {
		return [ 'acf', 'review', 'testimonial', '客户', '反馈' ];
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
			'repeater_field',
			[
				'label'       => esc_html__( 'Repeater field name', 'hello-elementor-child' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'customer_feedback',
				'placeholder' => 'customer_feedback',
			]
		);

		$this->add_control(
			'sub_customer_name',
			[
				'label'   => esc_html__( 'Sub: customer name', 'hello-elementor-child' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'customer_name',
			]
		);

		$this->add_control(
			'sub_review_date',
			[
				'label'   => esc_html__( 'Sub: review date', 'hello-elementor-child' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'review_date',
			]
		);

		$this->add_control(
			'sub_review_rating',
			[
				'label'   => esc_html__( 'Sub: rating', 'hello-elementor-child' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'review_rating',
			]
		);

		$this->add_control(
			'sub_review_content',
			[
				'label'   => esc_html__( 'Sub: content', 'hello-elementor-child' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'review_content',
			]
		);

		$this->add_control(
			'sub_review_images',
			[
				'label'   => esc_html__( 'Sub: images (gallery)', 'hello-elementor-child' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'review_images',
			]
		);

		$this->add_control(
			'post_id',
			[
				'label'       => esc_html__( 'Post ID (optional)', 'hello-elementor-child' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'step'        => 1,
				'description' => esc_html__( 'Leave empty to use the current post.', 'hello-elementor-child' ),
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
				'default'     => esc_html__( 'Customer feedback', 'hello-elementor-child' ),
				'label_block' => true,
				'condition'   => [
					'show_heading' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_tokens',
			[
				'label' => esc_html__( 'Colors', 'hello-elementor-child' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'navy_star',
			[
				'label'     => esc_html__( 'Stars (filled)', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1a2f6e',
				'selectors' => [
					'{{WRAPPER}} .heb-acf-feedback' => '--heb-cf-navy: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'muted_color',
			[
				'label'     => esc_html__( 'Muted (date)', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6b7280',
				'selectors' => [
					'{{WRAPPER}} .heb-acf-feedback' => '--heb-cf-muted: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'card_border',
			[
				'label'     => esc_html__( 'Card border', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e4e8ef',
				'selectors' => [
					'{{WRAPPER}} .heb-acf-feedback' => '--heb-cf-border: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'card_gap',
			[
				'label'      => esc_html__( 'Gap between cards', 'hello-elementor-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 40 ],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 16,
				],
				'selectors'  => [
					'{{WRAPPER}} .heb-acf-feedback' => '--heb-cf-card-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'card_radius',
			[
				'label'      => esc_html__( 'Card radius', 'hello-elementor-child' ),
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
					'{{WRAPPER}} .heb-acf-feedback' => '--heb-cf-radius: {{SIZE}}{{UNIT}};',
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
				'label'     => esc_html__( 'Body text', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1a1a1a',
				'selectors' => [
					'{{WRAPPER}} .heb-acf-feedback' => '--heb-cf-text: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'name_typography',
				'label'    => esc_html__( 'Customer name', 'hello-elementor-child' ),
				'selector' => '{{WRAPPER}} .heb-acf-feedback__name',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'label'    => esc_html__( 'Review text', 'hello-elementor-child' ),
				'selector' => '{{WRAPPER}} .heb-acf-feedback__content',
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

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} .heb-acf-feedback__heading',
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label'     => esc_html__( 'Color', 'hello-elementor-child' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .heb-acf-feedback__heading' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
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

	/**
	 * Format ACF date (return Y-m-d) for display.
	 *
	 * @param mixed $raw Date string or empty.
	 * @return string HTML-safe display string or empty.
	 */
	private function format_review_date( $raw ) {
		if ( ! is_string( $raw ) || '' === trim( $raw ) ) {
			return '';
		}
		$ts = strtotime( $raw );
		if ( false === $ts ) {
			return '';
		}
		return date_i18n( get_option( 'date_format' ), $ts );
	}

	/**
	 * @param mixed $raw Rating from ACF number field.
	 * @return int 0–5
	 */
	private function normalize_rating( $raw ) {
		if ( '' === $raw || null === $raw ) {
			return 0;
		}
		$n = (int) $raw;
		if ( $n < 1 ) {
			return 0;
		}
		if ( $n > 5 ) {
			return 5;
		}
		return $n;
	}

	/**
	 * @param int $n 1–5
	 */
	private function render_stars( $n ) {
		$n = min( 5, max( 0, (int) $n ) );
		if ( $n < 1 ) {
			return;
		}
		$label = sprintf(
			/* translators: %d: star count 1-5 */
			__( 'Rating: %d of 5 stars', 'hello-elementor-child' ),
			$n
		);
		echo '<span class="heb-acf-feedback__stars" role="img" aria-label="' . esc_attr( $label ) . '">';
		for ( $i = 1; $i <= 5; $i++ ) {
			$on  = $i <= $n;
			$cls = $on ? 'heb-acf-feedback__star heb-acf-feedback__star--on' : 'heb-acf-feedback__star heb-acf-feedback__star--off';
			echo '<span class="' . esc_attr( $cls ) . '" aria-hidden="true">&#9733;</span>';
		}
		echo '</span>';
	}

	/**
	 * @param mixed $images ACF gallery array.
	 */
	private function render_gallery( $images ) {
		if ( ! is_array( $images ) || empty( $images ) ) {
			return;
		}
		echo '<div class="heb-acf-feedback__gallery">';
		foreach ( $images as $img ) {
			$id = 0;
			if ( is_array( $img ) && isset( $img['ID'] ) ) {
				$id = (int) $img['ID'];
			} elseif ( is_numeric( $img ) ) {
				$id = (int) $img;
			}
			if ( $id < 1 ) {
				continue;
			}
			$full = wp_get_attachment_image_url( $id, 'full' );
			if ( ! $full ) {
				continue;
			}
			$thumb = wp_get_attachment_image(
				$id,
				'thumbnail',
				false,
				[
					'class'   => 'heb-acf-feedback__thumb-img',
					'loading' => 'lazy',
					'decoding'=> 'async',
				]
			);
			if ( ! $thumb ) {
				continue;
			}
			echo '<a class="heb-acf-feedback__thumb" href="' . esc_url( $full ) . '" target="_blank" rel="noopener noreferrer">';
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image is safe.
			echo $thumb;
			echo '</a>';
		}
		echo '</div>';
	}

	protected function render() {
		if ( ! function_exists( 'get_field' ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="heb-acf-feedback__empty">' . esc_html__( 'ACF is not active.', 'hello-elementor-child' ) . '</div>';
			}
			return;
		}

		$settings = $this->get_settings_for_display();
		$post_id  = $this->resolve_post_id( $settings );

		if ( ! empty( $settings['require_products'] ) && 'yes' === $settings['require_products'] ) {
			if ( ! $post_id || 'products' !== get_post_type( $post_id ) ) {
				if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
					echo '<div class="heb-acf-feedback__empty">' . esc_html__( 'Use this widget on a single product, or turn off “Only on products post type”.', 'hello-elementor-child' ) . '</div>';
				}
				return;
			}
		}

		$rep_key = $this->sanitize_field_name( isset( $settings['repeater_field'] ) ? $settings['repeater_field'] : '', 'customer_feedback' );
		$k_name  = $this->sanitize_field_name( isset( $settings['sub_customer_name'] ) ? $settings['sub_customer_name'] : '', 'customer_name' );
		$k_date  = $this->sanitize_field_name( isset( $settings['sub_review_date'] ) ? $settings['sub_review_date'] : '', 'review_date' );
		$k_rate  = $this->sanitize_field_name( isset( $settings['sub_review_rating'] ) ? $settings['sub_review_rating'] : '', 'review_rating' );
		$k_body  = $this->sanitize_field_name( isset( $settings['sub_review_content'] ) ? $settings['sub_review_content'] : '', 'review_content' );
		$k_gal   = $this->sanitize_field_name( isset( $settings['sub_review_images'] ) ? $settings['sub_review_images'] : '', 'review_images' );

		// Repeater: never use get_field( ..., false ) — ACF returns a row count / raw meta, not subfield arrays.
		if ( ! $post_id || ! function_exists( 'have_rows' ) || ! have_rows( $rep_key, $post_id ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="heb-acf-feedback__empty">' . esc_html__( 'No customer feedback rows. Add entries in the ACF repeater for this product.', 'hello-elementor-child' ) . '</div>';
			}
			return;
		}

		$show_h  = ! empty( $settings['show_heading'] ) && 'yes' === $settings['show_heading'];
		$heading = isset( $settings['heading_text'] ) ? $settings['heading_text'] : '';

		$cards_html = '';
		while ( have_rows( $rep_key, $post_id ) ) {
			the_row();

			$cname     = get_sub_field( $k_name );
			$cname     = is_string( $cname ) ? trim( $cname ) : '';
			$date_raw  = get_sub_field( $k_date );
			$date_raw  = is_string( $date_raw ) ? $date_raw : '';
			$date_disp = $this->format_review_date( $date_raw );
			$rating    = $this->normalize_rating( get_sub_field( $k_rate ) );
			$body      = get_sub_field( $k_body );
			$body      = is_string( $body ) ? trim( $body ) : '';
			$imgs      = get_sub_field( $k_gal );
			$imgs      = is_array( $imgs ) ? $imgs : [];

			if ( '' === $cname && '' === $body && '' === $date_disp && $rating < 1 && empty( $imgs ) ) {
				continue;
			}

			ob_start();
			?>
					<li class="heb-acf-feedback__card">
						<div class="heb-acf-feedback__head">
							<?php if ( '' !== $cname ) : ?>
								<strong class="heb-acf-feedback__name"><?php echo esc_html( $cname ); ?></strong>
							<?php endif; ?>
							<span class="heb-acf-feedback__meta">
								<?php
								if ( $rating > 0 ) {
									$this->render_stars( $rating );
								}
								if ( '' !== $date_disp ) {
									$ts      = is_string( $date_raw ) ? strtotime( $date_raw ) : false;
									$dt_attr = ( false !== $ts ) ? wp_date( 'Y-m-d', $ts ) : '';
									printf(
										'<time class="heb-acf-feedback__time"%s>%s</time>',
										'' !== $dt_attr ? ' datetime="' . esc_attr( $dt_attr ) . '"' : '',
										esc_html( $date_disp )
									);
								}
								?>
							</span>
						</div>
						<?php if ( '' !== $body ) : ?>
							<div class="heb-acf-feedback__content"><?php echo apply_filters( 'the_content', $body ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
						<?php endif; ?>
						<?php $this->render_gallery( $imgs ); ?>
					</li>
			<?php
			$cards_html .= ob_get_clean();
		}

		if ( '' === $cards_html ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="heb-acf-feedback__empty">' . esc_html__( 'No customer feedback rows. Add entries in the ACF repeater for this product.', 'hello-elementor-child' ) . '</div>';
			}
			return;
		}

		?>
		<div class="heb-acf-feedback">
			<?php if ( $show_h && '' !== $heading ) : ?>
				<h2 class="heb-acf-feedback__heading"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>
			<ul class="heb-acf-feedback__list">
				<?php
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- card markup built in-loop with escaped fields.
				echo $cards_html;
				?>
			</ul>
		</div>
		<?php
	}
}
