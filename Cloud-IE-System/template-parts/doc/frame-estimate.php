<div class="bill-wrap document-container">
<div class="container">
<div class="row">
<div class="col-xs-6">
<h1 class="bill-title">御見積書</h1>
<h2 class="bill-destination">
<span class="bill-destination-client">
<?php echo esc_html( bill_get_client_name( $post ) ); ?>
</span>
<span class="bill-destination-honorific">
<?php echo esc_html( bill_get_client_honorific( $post ) ); ?>
</span>
</h2>

<p class="bill-message">毎々格別のお引立てを賜りまして厚くお礼申し上げます。<br>
御連絡いただきました件、下記の通り御見積申し上げます。<br>
何卒ご用命下さいますようお願い申し上げます。</p>

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
			background-color: <?php echo nl2br( esc_attr( $options['m-table-bg-color'] ) ); ?> !important;
		}
		#invoice-top-bill-total.bill-total dd#bill-frame-total-price {
			background-color: transparent !important;
			border-left: none;
		}
	</style>
<?php


$bill_total_price_display = ( isset( $post->bill_total_price_display[0] ) ) ? $post->bill_total_price_display[0] : '';
if ( $bill_total_price_display != 'hidden' ) {
	// 税率の金額を格納する配列
	$bill_total = array();
	// 税率ごとの合計金額
	$tax_total  = bill_vektor_invoice_each_tax( $post );
?>
<dt>合計金額</dt>
<?php
global $post;
$bill_total = bill_vektor_invoice_total_tax( $post );
?>
<dd id="bill-frame-total-price">￥ <?php echo number_format( $bill_total ); ?><span class="caption">(消費税含)</span></dd>
<?php } // if ( $post->bill_total_price_display[0] != 'hidden' ) { ?>
</dl>
</div><!-- [ /.col-xs-6 ] -->

<div class="col-xs-5 col-xs-offset-1">
<table class="bill-info-table">
<tr>
<th>発行日</th>
<td><?php the_date(); ?></td>
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
if ( ! empty( $options['invoice-number'] ) ){
	echo '登録番号:' . nl2br( esc_textarea( $options['invoice-number'] ) );
}
?>
</div><!-- [ /.address-own ] -->
</div><!-- [ /.col-xs-5 col-xs-offset-1 ] -->
</div><!-- [ /.row ] -->
</div><!-- [ /.container ] -->


<div class="container">

<?php get_template_part( 'template-parts/doc/m-table-price' ); ?>

<dl class="bill-remarks">
<dt>備考</dt>
<dd>
<?php
if ( $post->bill_remarks ) {
	// 請求書個別の備考
	echo apply_filters( 'the_content', $post->bill_remarks );
} else {
	// 共通の備考
	if ( isset( $options['remarks-estimate'] ) ) {
		echo apply_filters( 'the_content', $options['remarks-estimate'] );
	}
}
?>
</dd>
</dl>
</div><!-- [ /.container ] -->
</div><!-- [ /.bill-wrap ] -->
