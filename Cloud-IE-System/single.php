<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php if ( have_posts() ) { ?>
<?php
while ( have_posts() ) :
	the_post();
?>
<?php
$doc_change = false;
add_filter( 'cloud-ie-system-doc-change', $doc_change );
if ( ! $doc_change ) {
	if ( get_post_type() == 'post' ) {
		get_template_part( 'template-parts/doc/frame-bill' );
	} elseif ( get_post_type() == 'nohin' ) {
		get_template_part( 'template-parts/doc/frame-nohin' );
	} elseif ( get_post_type() == 'estimate' ) {
		get_template_part( 'template-parts/doc/frame-estimate' );
	} elseif ( get_post_type() == 'client' ) {
		get_template_part( 'template-parts/doc/frame-client' );
	}
}
do_action( 'cloud-ie-system-doc-frame' );
?>
<?php endwhile; ?>
<?php } ?>

<div class="bill-no-print">
<div class="container">
<p>このエリアは印刷されません。</p>
<?php $page_id = get_the_ID();?>
<p class="handp-btn">
	<a class="btn btn-flat document-edit-btn" href="<?php echo esc_url( home_url( '/') ); ?>wp-admin/post.php?post=<?php echo $page_id;?>&action=edit" target="_blank"><span>この書類を編集する</span></a>
	<a class="btn btn-flat document-print-btn" href="javascript:void(0)" onclick="window.print();return false;"><span>この書類を印刷する</span></a>
</p>
<div class="row">
<?php get_template_part( 'template-parts/breadcrumb' ); ?>
</div>
</div>
</div>

<?php wp_footer(); ?>
</body>
</html>
