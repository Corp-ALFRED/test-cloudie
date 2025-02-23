<div class="bill-wrap document-container">
<div class="container">
<div class="row">
<div class="col-xs-6">
<h1 class="bill-title">御納品書</h1>
<h2 class="bill-destination">
<span class="bill-destination-client">
<?php echo esc_html( bill_get_client_name( $post ) ); ?>
</span>
<span class="bill-destination-honorific">
<?php echo esc_html( bill_get_client_honorific( $post ) ); ?>
</span>
</h2>

<p class="bill-message">平素は格別のご高配に賜り、誠にありがとう御座います。<br>
下記の通りご納品いたします。</p>
	
<dl class="bill-estimate-title">
<dt class="text-nowrap">件名</dt>
<dd><?php the_title(); ?></dd>
</dl>

<dl id="invoice-top-bill-total" class="bill-total">
	<?php $options = get_option( 'bill-setting', Bill_Admin::options_default() ); ?>
	<style>
		#invoice-top-bill-total.bill-total {
			width: auto;
			border: none;
		}
		#invoice-top-bill-total.bill-total dt, #invoice-top-bill-total.bill-total dd {
			display: inline-block;
			border: solid 1px #abb8c3;
			font-weight: bold;
			font-size: 18px;
			-webkit-print-color-adjust: exact;
			padding: 10px;
		}
		#invoice-top-bill-total.bill-total dt {
			background-color: <?php echo nl2br( esc_attr( $options['n-table-bg-color'] ) ); ?> !important;
		}
		#invoice-top-bill-total.bill-total dd#bill-frame-total-price {
			background-color: transparent !important;
			border-left: none;
		}
	</style>
<dt>合計金額</dt>
<?php
global $post;
$bill_total = bill_vektor_invoice_total_tax( $post );
?>
<dd id="bill-frame-total-price">￥ <?php echo number_format( $bill_total ); ?><span class="caption">(消費税含)</span></dd>
</dl>
</div>

<div class="col-xs-5 col-xs-offset-1">
<table class="bill-info-table">
<tr>
<th>納品番号</th>
<td><?php echo esc_html( $post->bill_id ); ?></td>
</tr>
<tr>
<th>納品日</th>
<td><?php the_date(); ?></td>
</tr>
<tr>
<th>事業者番号</th>
<?php $options = get_option( 'bill-setting', Bill_Admin::options_default() ); ?>
<td>T<?php if ( ! empty( $options['invoice-number'] ) ) {
	echo nl2br( esc_textarea( $options['invoice-number'] ) );
} ?></td>
</tr>
</table>

<div class="bill-address-own">
<?php $options = get_option( 'bill-setting', Bill_Admin::options_default() ); ?>
<h4><?php echo nl2br( esc_textarea( $options['own-name'] ) ); ?></h4>
<div class="bill-address"><?php echo nl2br( esc_textarea( $options['own-address'] ) ); ?></div>
<?php
if ( isset( $options['own-seal'] ) && $options['own-seal'] ) {
	$attr = array(
		'id'    => 'bill-seal',
		'class' => 'bill-seal',
		'alt'   => trim( strip_tags( get_post_meta( $options['own-seal'], '_wp_attachment_image_alt', true ) ) ),
	);
	echo wp_get_attachment_image( $options['own-seal'], 'medium', false, $attr );
}
?>
</div><!-- [ /.address-own ] -->
</div><!-- [ /.col-xs-5 col-xs-offset-1 ] -->
</div><!-- [ /.row ] -->
</div><!-- [ /.container ] -->


<div class="container">
	
<?php get_template_part( 'template-parts/doc/n-table-price' ); ?>

<dl class="bill-remarks">
<dt>備考</dt>
<dd>
<?php

	echo apply_filters( 'the_content', $post->bill_remarks );

?>
</dd>
</dl>

<div class="bill-payee">
<table class="table table-bordered">
<tr>
<th class="active">振込口座</th>
<td >
<p class="bill-payee-text">
<?php echo nl2br( esc_textarea( $options['own-payee'] ) ); ?>
</p>
<?php
if ( isset( $options['own-logo'] ) && $options['own-logo'] ) {
	$attr = array(
		'id'    => 'bill-payee-logo',
		'class' => 'bill-payee-logo',
		'alt'   => trim( strip_tags( get_post_meta( $options['own-logo'], '_wp_attachment_image_alt', true ) ) ),
	);
	echo wp_get_attachment_image( $options['own-logo'], 'medium', false, $attr );
}
?>
</td>
</tr>
</table>
</div><!-- [ /.bill-payee ] -->
</div><!-- [ /.container ] -->
</div><!-- [ /.bill-wrap ] -->
