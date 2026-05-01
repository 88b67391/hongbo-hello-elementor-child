<?php
/**
 * Elementor: ACF Gallery — main image + thumbnail strip.
 *
 * @package HelloElementorChild
 */

namespace HelloElementorChild\Elementor;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Renders an ACF Gallery as ecommerce-style main + thumbs.
 */
class Widget_ACF_Product_Gallery extends Widget_Base {

	public function get_name() {
		return 'heb_acf_product_gallery';
	}

	public function get_title() {
		return esc_html__( 'ACF Product Gallery · 产品相册', 'hello-elementor-child' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'heb-child' ];
	}

	public function get_keywords() {
		return [ 'acf', 'gallery', 'product', 'image', 'thumbnail', 'slider', 'hongbo', '相册', '产品' ];
	}

	public function get_style_depends() {
		return [ 'heb-acf-product-gallery' ];
	}

	public function get_script_depends() {
		return [ 'heb-acf-product-gallery' ];
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
				'label'       => esc_html__( 'Gallery field name', 'hello-elementor-child' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'product_images',
				'placeholder' => 'product_images',
				'description' => esc_html__( 'ACF field name (Gallery return format: Array of Image).', 'hello-elementor-child' ),
			]
		);

		$size_choices = $this->get_image_size_choices();

		$this->add_control(
			'main_image_size',
			[
				'label'   => esc_html__( 'Main image size', 'hello-elementor-child' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'large',
				'options' => $size_choices,
			]
		);

		$this->add_control(
			'thumb_image_size',
			[
				'label'   => esc_html__( 'Thumbnail size', 'hello-elementor-child' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'thumbnail',
				'options' => $size_choices,
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @return array<string, string>
	 */
	private function get_image_size_choices() {
		global $_wp_additional_image_sizes;
		$choices = [
			'full'      => esc_html__( 'Full', 'hello-elementor-child' ),
			'large'     => esc_html__( 'Large', 'hello-elementor-child' ),
			'medium'    => esc_html__( 'Medium', 'hello-elementor-child' ),
			'thumbnail' => esc_html__( 'Thumbnail', 'hello-elementor-child' ),
		];
		if ( is_array( $_wp_additional_image_sizes ) ) {
			foreach ( array_keys( $_wp_additional_image_sizes ) as $slug ) {
				$choices[ $slug ] = $slug;
			}
		}
		return $choices;
	}

	/**
	 * Post ID used for ACF get_field in templates and singular views.
	 */
	private function resolve_post_id() {
		$post_id = get_the_ID();
		if ( $post_id ) {
			return (int) $post_id;
		}
		$qid = get_queried_object_id();
		return $qid ? (int) $qid : 0;
	}

	/**
	 * @param mixed  $item ACF image array or attachment ID.
	 * @param string $size Image size slug.
	 * @return array{url:string,srcset:string,sizes:string,alt:string}
	 */
	private function resolve_image( $item, $size ) {
		$empty = [ 'url' => '', 'srcset' => '', 'sizes' => '', 'alt' => '' ];

		if ( is_array( $item ) && ! empty( $item['ID'] ) ) {
			$id = (int) $item['ID'];
		} elseif ( is_numeric( $item ) ) {
			$id = (int) $item;
		} else {
			return $empty;
		}

		$url = wp_get_attachment_image_url( $id, $size );
		if ( ! $url && is_array( $item ) && ! empty( $item['url'] ) ) {
			$url = $item['url'];
		}
		if ( ! $url ) {
			return $empty;
		}

		$srcset = wp_get_attachment_image_srcset( $id, $size );
		$sizes  = wp_get_attachment_image_sizes( $id, $size );
		$alt    = '';
		if ( is_array( $item ) && isset( $item['alt'] ) ) {
			$alt = (string) $item['alt'];
		}
		if ( '' === $alt ) {
			$alt = (string) get_post_meta( $id, '_wp_attachment_image_alt', true );
		}

		return [
			'url'    => $url,
			'srcset' => is_string( $srcset ) ? $srcset : '',
			'sizes'  => is_string( $sizes ) ? $sizes : '',
			'alt'    => $alt,
		];
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$field = isset( $settings['acf_field_name'] ) ? sanitize_text_field( $settings['acf_field_name'] ) : 'product_images';
		$field = preg_replace( '/[^a-z0-9_\-]/i', '', $field );
		if ( '' === $field ) {
			$field = 'product_images';
		}

		$post_id = $this->resolve_post_id();
		$images  = [];

		if ( function_exists( 'get_field' ) && $post_id ) {
			$raw = get_field( $field, $post_id, false );
			$images = is_array( $raw ) ? $raw : [];
		}

		$main_size  = isset( $settings['main_image_size'] ) ? $settings['main_image_size'] : 'large';
		$thumb_size = isset( $settings['thumb_image_size'] ) ? $settings['thumb_image_size'] : 'thumbnail';

		if ( empty( $images ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="heb-acf-gallery__empty">';
				echo esc_html__( 'No images in this ACF gallery for the current post.', 'hello-elementor-child' );
				echo '</div>';
			}
			return;
		}

		$resolved = [];
		foreach ( $images as $item ) {
			$main  = $this->resolve_image( $item, $main_size );
			$thumb = $this->resolve_image( $item, $thumb_size );
			if ( '' === $main['url'] && '' !== $thumb['url'] ) {
				$main = $thumb;
			}
			if ( '' === $thumb['url'] && '' !== $main['url'] ) {
				$thumb = $main;
			}
			if ( '' === $main['url'] ) {
				continue;
			}
			$resolved[] = [
				'main'  => $main,
				'thumb' => $thumb,
			];
		}

		if ( empty( $resolved ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="heb-acf-gallery__empty">';
				echo esc_html__( 'Could not resolve image URLs.', 'hello-elementor-child' );
				echo '</div>';
			}
			return;
		}

		$first = $resolved[0]['main'];

		$uid = 'heb-gal-' . $this->get_id();
		?>
		<div id="<?php echo esc_attr( $uid ); ?>" class="heb-acf-gallery" data-heb-acf-gallery>
			<div class="heb-acf-gallery__stage">
				<img
					class="heb-acf-gallery__main"
					src="<?php echo esc_url( $first['url'] ); ?>"
					<?php if ( $first['srcset'] ) : ?>
						srcset="<?php echo esc_attr( $first['srcset'] ); ?>"
						sizes="<?php echo esc_attr( $first['sizes'] ); ?>"
					<?php endif; ?>
					alt="<?php echo esc_attr( $first['alt'] ); ?>"
					loading="eager"
					decoding="async"
				/>
			</div>
			<div class="heb-acf-gallery__thumbs" role="tablist" aria-label="<?php echo esc_attr__( 'Product images', 'hello-elementor-child' ); ?>">
				<?php foreach ( $resolved as $i => $pair ) : ?>
					<?php
					$m = $pair['main'];
					$t = $pair['thumb'];
					$is_active = ( 0 === $i );
					?>
					<button
						type="button"
						class="heb-acf-gallery__thumb<?php echo $is_active ? ' is-active' : ''; ?>"
						role="tab"
						aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
						data-main-src="<?php echo esc_url( $m['url'] ); ?>"
						<?php if ( $m['srcset'] ) : ?>
							data-main-srcset="<?php echo esc_attr( $m['srcset'] ); ?>"
							data-main-sizes="<?php echo esc_attr( $m['sizes'] ); ?>"
						<?php endif; ?>
						data-main-alt="<?php echo esc_attr( $m['alt'] ); ?>"
					>
						<img
							src="<?php echo esc_url( $t['url'] ); ?>"
							<?php if ( $t['srcset'] ) : ?>
								srcset="<?php echo esc_attr( $t['srcset'] ); ?>"
								sizes="<?php echo esc_attr( $t['sizes'] ); ?>"
							<?php endif; ?>
							alt=""
							loading="lazy"
							decoding="async"
						/>
					</button>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
