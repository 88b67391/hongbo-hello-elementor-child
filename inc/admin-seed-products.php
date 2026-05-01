<?php
/**
 * 工具 → CS 示例产品：一键导入 JSON（无需 WP-CLI）。
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return string Absolute path to bundled sample JSON.
 */
function hello_elementor_child_get_cs_products_sample_json_path() {
	return trailingslashit( get_stylesheet_directory() ) . 'sample-data/cs-products-sample.json';
}

add_action(
	'admin_menu',
	function () {
		add_management_page(
			__( 'Import sample CS products', 'hello-elementor-child' ),
			__( 'CS 示例产品', 'hello-elementor-child' ),
			'manage_options',
			'heb-seed-cs-products',
			'hello_elementor_child_render_seed_products_page'
		);
	}
);

/**
 * Tools screen: form + log from last run.
 */
function hello_elementor_child_render_seed_products_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$file = hello_elementor_child_get_cs_products_sample_json_path();

	if ( isset( $_GET['heb_seeded'] ) && '1' === $_GET['heb_seeded'] ) {
		$tkey = 'heb_seed_cs_log_' . get_current_user_id();
		$log  = get_transient( $tkey );
		if ( is_array( $log ) ) {
			delete_transient( $tkey );
			echo '<div class="notice notice-info"><p><strong>' . esc_html__( '导入结果', 'hello-elementor-child' ) . '</strong></p><ul style="list-style:disc;margin-left:1.5em;">';
			foreach ( $log as $line ) {
				echo '<li>' . esc_html( $line ) . '</li>';
			}
			echo '</ul></div>';
		}
	}

	?>
	<div class="wrap">
		<h1><?php echo esc_html__( '导入示例 CS Products', 'hello-elementor-child' ); ?></h1>
		<p>
			<?php
			echo esc_html__( '从子主题内 JSON 批量创建产品文章（默认 post type：products），并写入 ACF 字段。已存在的 slug 会跳过。', 'hello-elementor-child' );
			?>
		</p>
		<p>
			<code><?php echo esc_html( $file ); ?></code>
			<?php if ( ! is_readable( $file ) ) : ?>
				<br><span class="notice notice-error" style="display:inline-block;padding:4px 8px;margin-top:8px;">
					<?php esc_html_e('文件不存在或不可读。请确认已上传子主题 sample-data 目录。', 'hello-elementor-child'); ?>
				</span>
			<?php endif; ?>
		</p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'hello_elementor_child_seed_cs_products' ); ?>
			<input type="hidden" name="action" value="hello_elementor_child_seed_cs_products" />
			<?php
			$btn_attrs = [];
			if ( ! is_readable( $file ) ) {
				$btn_attrs['disabled'] = 'disabled';
			}
			submit_button( __( '开始导入', 'hello-elementor-child' ), 'primary', 'submit', true, $btn_attrs );
			?>
		</form>
	</div>
	<?php
}

add_action(
	'admin_post_hello_elementor_child_seed_cs_products',
	function () {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( '权限不足。', 'hello-elementor-child' ) );
		}
		check_admin_referer( 'hello_elementor_child_seed_cs_products' );

		$file   = hello_elementor_child_get_cs_products_sample_json_path();
		$result = hello_elementor_child_import_cs_products_from_json_file( $file );

		set_transient( 'heb_seed_cs_log_' . get_current_user_id(), $result['log'], 120 );

		wp_safe_redirect(
			add_query_arg(
				'heb_seeded',
				'1',
				admin_url( 'tools.php?page=heb-seed-cs-products' )
			)
		);
		exit;
	}
);
