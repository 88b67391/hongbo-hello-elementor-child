<?php
/**
 * WP-CLI 调用（需在已启用本子主题时执行）。
 *
 * wp eval-file wp-content/themes/hello-elementor-child/sample-data/seed-via-wpcli.php
 */

if ( ! function_exists( 'hello_elementor_child_import_cs_products_from_json_file' ) ) {
	echo "请先启用 Hello Elementor Child 子主题。\n";
	exit( 1 );
}

$json = __DIR__ . '/cs-products-sample.json';
$result = hello_elementor_child_import_cs_products_from_json_file( $json );

foreach ( $result['log'] as $line ) {
	echo $line . "\n";
}

echo $result['success'] ? "完成。\n" : "结束（含错误时请查看上方）。\n";
