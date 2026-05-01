<?php
/**
 * 从 JSON 批量创建产品文章 + ACF（供后台工具与 WP-CLI 共用）。
 *
 * 文章类型默认 `products`（后台菜单名常为 “Products”），可通过过滤器修改：
 * add_filter( 'hello_elementor_child_sample_product_post_type', fn () => 'cs-products' );
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 示例导入 / 校验所用的产品文章类型。
 */
function hello_elementor_child_get_sample_product_post_type() {
	return apply_filters( 'hello_elementor_child_sample_product_post_type', 'products' );
}

/**
 * @param string $json_file Absolute path to cs-products-sample.json.
 * @return array{success: bool, log: string[]}
 */
function hello_elementor_child_import_cs_products_from_json_file( $json_file ) {
	$log       = [];
	$post_type = hello_elementor_child_get_sample_product_post_type();

	if ( ! function_exists( 'update_field' ) ) {
		$log[] = __( '错误：需要安装并启用 Advanced Custom Fields（需存在 update_field）。', 'hello-elementor-child' );
		return [ 'success' => false, 'log' => $log ];
	}

	if ( ! post_type_exists( $post_type ) ) {
		$log[] = sprintf(
			/* translators: %s post type slug */
			__( '错误：未注册文章类型 %s。', 'hello-elementor-child' ),
			$post_type
		);
		return [ 'success' => false, 'log' => $log ];
	}

	if ( ! is_readable( $json_file ) ) {
		$log[] = sprintf(
			/* translators: %s file path */
			__( '找不到或无法读取文件：%s', 'hello-elementor-child' ),
			$json_file
		);
		return [ 'success' => false, 'log' => $log ];
	}

	$data = json_decode( file_get_contents( $json_file ), true );
	if ( empty( $data['products'] ) || ! is_array( $data['products'] ) ) {
		$log[] = __( '错误：JSON 中缺少 products 数组或格式无效。', 'hello-elementor-child' );
		return [ 'success' => false, 'log' => $log ];
	}

	foreach ( $data['products'] as $p ) {
		$title   = isset( $p['title'] ) ? $p['title'] : '';
		$slug    = isset( $p['slug'] ) ? sanitize_title( $p['slug'] ) : '';
		$content = isset( $p['content'] ) ? $p['content'] : '';

		if ( '' === $title || '' === $slug ) {
			continue;
		}

		$existing = get_posts(
			[
				'post_type'      => $post_type,
				'name'           => $slug,
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'fields'         => 'ids',
			]
		);

		if ( ! empty( $existing ) ) {
			$log[] = sprintf(
				/* translators: %s post slug */
				__( '已存在，跳过：%s', 'hello-elementor-child' ),
				$slug
			);
			continue;
		}

		$post_id = wp_insert_post(
			[
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_content' => $content,
				'post_status'  => 'publish',
				'post_type'    => $post_type,
			],
			true
		);

		if ( is_wp_error( $post_id ) ) {
			$log[] = sprintf(
				/* translators: 1: slug, 2: error message */
				__( '创建失败 %1$s：%2$s', 'hello-elementor-child' ),
				$slug,
				$post_id->get_error_message()
			);
			continue;
		}

		$acf = isset( $p['acf'] ) && is_array( $p['acf'] ) ? $p['acf'] : [];
		foreach ( $acf as $key => $value ) {
			if ( 'product_images' === $key ) {
				continue;
			}
			update_field( $key, $value, $post_id );
		}

		$log[] = sprintf(
			/* translators: 1: slug, 2: post ID */
			__( '已创建：%1$s（ID %2$d）', 'hello-elementor-child' ),
			$slug,
			(int) $post_id
		);
	}

	return [ 'success' => true, 'log' => $log ];
}
