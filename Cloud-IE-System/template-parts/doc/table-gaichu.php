<?php $options = get_option('bill-setting', Bill_Admin::options_default()); ?>
<style>
	table.table-bill thead tr th {
		background-color: #B3D9FF !important;
		border: 1px solid #fff;
		vertical-align: middle;
		font-size: 16px;
	}

	table.table-bill tbody tr.tekiyou-content th {
		background-color: #B3D9FF !important;
		vertical-align: middle;
		font-size: 16px;
		font-weight: bold;
	}

	table.table-bill>tbody>tr>td {
		vertical-align: middle;
		text-align: center;
		background-color: #fff;
		font-size: 16px;
	}

	table.table-bill>tbody>tr>td.price {
		text-align: center;
	}

	table.table-bill>tbody>tr.tekiyou-content>td {
		text-align: start;
	}
</style>
<table class="table table-bordered table-striped table-bill">
	<thead>
		<tr>
			<th class="text-center bill-cell-days" rowspan="2">発生日</th>
			<th class="text-center bill-cell-name" colspan="3">借方</th>
			<th class="text-center bill-cell-count" colspan="3">貸方</th>
		</tr>
		<tr>
			<th class="text-center">勘定科目</th>
			<th class="text-center">金額</th>
			<th class="text-center">税区分</th>
			<th class="text-center">勘定科目</th>
			<th class="text-center">金額</th>
			<th class="text-center">税区分</th>
		</tr>
	</thead>
	<tbody>
		<?php
		global $post;
		?>
		<tr>
			<td><?php echo esc_html($post->gaichu_limit_date); ?></td>
			<td><?php echo esc_html($post->gaichu_junge); ?></td>
			<td class="price"><?php echo '¥ ' . esc_html($post->gaichu_sokei); ?></td>
			<td><?php echo esc_html($post->gaichu_zei); ?></td>
			<td><?php echo esc_html($post->gaichu_nyukin_jotai); ?></td>
			<td class="price"><?php echo '¥ ' . esc_html($post->gaichu_sokei); ?></td>
			<td><?php echo esc_html($post->gaichu_zei); ?></td>
		</tr>
		<tr class="tekiyou-content">
			<th class="text-center">摘要</th>
			<td colspan="6"><?php echo esc_html($post->gaichu_tekiyo); ?></td>
		</tr>
	</tbody>
</table>