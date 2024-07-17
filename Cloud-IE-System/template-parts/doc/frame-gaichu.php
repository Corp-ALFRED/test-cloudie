<div class="bill-wrap gaichu-wrap">
	<div class="container">
		<div class="row">
			<div class="col-xs-6">
				<h1 class="bill-title">支払仕訳帳</h1>
			</div>
			<div class="container shiwake-info-wrapper">
				<div  class="shiwake-info-content">
					<dl class="bill-estimate-title">
						<dt class="text-nowrap">入力日</dt>
						<dd><?php the_date(); ?></dd>
					</dl>

					<dl class="bill-estimate-title">
						<dt class="text-nowrap">支払い件名</dt>
						<dd><?php the_title(); ?></dd>
					</dl>

					<dl class="bill-estimate-title">
						<dt class="text-nowrap">支払い先名</dt>
						<dd><?php echo esc_html(bill_get_client_name($post)); ?></dd>
					</dl>

					<dl class="bill-estimate-title">
						<dt class="text-nowrap">事業者番号</dt>
						<dd>T<?php echo esc_html($post->bill_nameclient_invoice); ?></dd>
					</dl>
				</div>
				<div class="gaichu-post-img">
					<?php
					$img_id = esc_html($post->gaichu_send_pdf);
					$img_url = wp_get_attachment_url($img_id);

					if (substr($img_url, -4) === '.pdf') {
						echo '<a href="' . esc_url($img_url) . '" target="_blank">支払い書類を見る</a>';
					} else {
						// If it's not a PDF file, display it as an image
						echo '<img src="' . esc_url($img_url) . '" alt="">';
					}
					?>
				</div><!-- [ /.bill-payee ] -->
			</div>
		</div><!-- [ /.row ] -->
	</div><!-- [ /.container ] -->


	<div class="container shiwake-container">

		<?php get_template_part('template-parts/doc/table-gaichu'); ?>

	</div><!-- [ /.container ] -->
</div><!-- [ /.bill-wrap ] -->