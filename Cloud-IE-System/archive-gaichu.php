<?php get_header(); ?>

<style>
    .quickeditarea{
        display: none;
    }
</style>
<?php $page_post_type = bill_get_post_type(); ?>

<?php get_template_part('template-parts/breadcrumb'); ?>
<?php
if(isset($_REQUEST['pg']) && $_REQUEST['pg']==1){
    if(isset($_REQUEST['gaichu_limit_date']) ){
        update_post_meta( $_REQUEST['post_id'], 'gaichu_limit_date', $_REQUEST['gaichu_limit_date'] ,'');
    }
    if(isset($_REQUEST['gaichu_sokei']) ){
        update_post_meta( $_REQUEST['post_id'], 'gaichu_sokei', $_REQUEST['gaichu_sokei'] ,'');
    }
    if(isset($_REQUEST['gaichu_junge']) ){
        update_post_meta( $_REQUEST['post_id'], 'gaichu_junge', $_REQUEST['gaichu_junge'] ,'');
    }
    if(isset($_REQUEST['gaichu_nyukin_jotai']) ){
        update_post_meta( $_REQUEST['post_id'], 'gaichu_nyukin_jotai', $_REQUEST['gaichu_nyukin_jotai'] ,'');
    }
}
?>

<div class="container">
	<div class="row">

		<!-- [ #main ] -->
		<div id="main" class="col-md-12">
			<!-- [ 記事のループ ] -->

			<?php if (is_front_page() || is_archive() || is_tax()) { ?>

				<form action="" method="get">

					<div class="section" id="search-box">
						<?php get_template_part('template-parts/search-box-gaichu'); ?>
					</div>

					<?php $post_type = bill_get_post_type(); ?>

					<div class="section">
						<?php
$bill_all_total = 0; // $bill_all_total を初期化
                              
$args = array(
    'post_type' => "gaichu",
    'posts_per_page' => -1,
    'orderby'=>'meta_value',
    'order' => 'ASC',
    'meta_key'=>'gaichu_limit_date'
    );
if(isset($_REQUEST["start_date2"])){
$args['meta_key'] = 'gaichu_limit_date';
$args['meta_value'] = date("Y年m月d日",strtotime($_REQUEST["start_date2"]));      
$dat=date("Y年m月d日",strtotime($_REQUEST["start_date2"]));
}
if(isset($_REQUEST["bill_client"])){
$args['meta_key'] = 'bill_client';
$args['meta_value'] = $_REQUEST["bill_client"];
}
                                                              

$loop = new WP_Query($args);
                                                                    
if ($loop -> have_posts()) {
						?>
						<table class="table table-striped table-borderd">
							<tr>
								<th>発行日/支払日</th>
								<th>支払い先</th>
								<th>適用</th>
								<th>借方科目</th>
								<th>支払方法</th>
								<th class="price_tit">支払い金額<span class="caption">(税込)</span></th>
								<th>取引詳細</th>
							</tr>
							<?php
																		while ($loop -> have_posts()) :
																		$loop ->the_post();
							?>
							<tr>
								<!-- [ 発行日 ] -->
								<td><?php echo esc_html( $post->gaichu_limit_date ); ?></td>

								<?php if ($post_type['slug'] != 'salary') { ?>
								<!-- [ 支払先 ] -->
								<td class="text-nowrap">
									<?php
										if ($post->bill_client_name_manual) {
											echo esc_html($post->bill_client_name_manual);
										} else {
											$client_id   = $post->bill_client;
											$client_name = get_post_meta($client_id, 'client_short_name', true);
											if (!$client_name) {
												$client_name = get_the_title($client_id);
											}
											echo '<a href="' . get_the_permalink($client_id) . '" target="_blank">' . esc_html($client_name) . '</a>';
										}
									?>
								</td>
								<?php } ?>

								<!-- [ 摘要 ] -->
								<td><a href="<?php the_permalink(); ?>" target="_blank"><?php echo esc_html( $post->gaichu_tekiyo ); ?></a></td>
								<!-- [ 借方科目 ] -->
								<td><?php echo esc_html( $post->gaichu_junge ); ?></td>
								<!-- [ 支払方法 ] -->
								<td><?php echo esc_html( $post->gaichu_nyukin_jotai ); ?></td>
								<!-- [ 金額の表示 ] -->
								<?php
																				global $post;
																				$kugiri_number = $post->gaichu_sokei;
																				$kugirigo = str_replace(',', '',$kugiri_number);
																				$bill_total = (int) $kugirigo;
																				$bill_all_total += $bill_total;
								?>
								<td>￥ <?php echo ($bill_total); ?></td>
								<!-- [ 支払方法 ] -->
								<td>
                                    <a href="<?php the_permalink(); ?>" target="_blank">詳細を見る</a><br>
                                    <a href="#" class='quickedit'>クイック編集</a><br></td>
							</tr>
                            
                            <?php
                                    echo "<tr class='quickeditarea'><td colspan=7>";
                                    echo "<form method='post' action=''>";
                                    echo "<input type='hidden' name='pg' value='1'>";
                                    echo "<input type='hidden' name='post_id' value='".$post->ID."'>";
                                    
                                    echo "<label style='width:120px;'>発行日</label><input type='text' class='gaichu_datepicker' name='gaichu_limit_date' value='".$post->gaichu_limit_date."'><br>";
                                    echo "<label style='width:120px;'>支払額</label><input type='text' name='gaichu_sokei' value='".$post->gaichu_sokei."'><br>";
                                    echo "<label style='width:120px;'>仕訳【借方】</label>";
                                    $gaichu_junge=array(
                                        '仕入'=>"仕入れ",
                                        '外注費'=>"外注費",
                                        '業務委託費'=>"業務委託費",
                                        'カスタム科目'=>"カスタム科目");
                                    foreach($gaichu_junge as $key=>$val){
                                        $s = $key==$post->gaichu_junge ? "checked" :"";
                                        echo "<label style='padding:0 5px;'><input type='radio' name='gaichu_junge' value='{$key}' {$s}>{$val}</label>";
                                    }
                                    echo "<br>";
                                    echo "<label style='width:120px;'>支払い方法【貸方】</label>";
                                    $gaichu_nyukin_jotai=array(
                                        '現金'=>"現金払い",
                                        '普通預金[未処理]'=>"銀行振込[未処理]",
                                        '普通預金[処理済]'=>"銀行振込[処理済]",
                                        '売掛金'=>"クレジットカード",
                                        '前払金'=>"前払い");
                                    foreach($gaichu_nyukin_jotai as $key=>$val){
                                        $s = $key==$post->gaichu_nyukin_jotai ? "checked" :"";
                                        echo "<label style='padding:0 5px;'><input type='radio' name='gaichu_nyukin_jotai' value='{$key}' {$s}>{$val}</label>";
                                    }
                                    echo "<br>";
                                    echo "<button class='btn-edit'>更新</button>";
                                    echo "<button class='btn-cancel'>キャンセル</button>";
                                    echo "</form>";
                                    echo "</td>";
                                    echo "</tr>";
                            ?>
                            
							<?php endwhile; ?>
						</table>
						<?php the_posts_pagination(); ?>
						<?php
																	} else {
																		echo '<p>該当の書類はありません。</p>';
																	} // if ( have_posts() ) {
						?>

						<!-- [ 合計金額の表示 ] -->
						<div align="right" style="font-size:large;border-bottom: inset 2px #000000;">合計金額　<?php echo number_format($bill_all_total); ?>円<span class="caption">(税込)</span></div>
					</div>

					<div id="news" class="section">
						<h3>お知らせ</h3>
						<ul class="post-list news-list" id="newsEntries">
							<?php
							$args = array(
								'numberposts' => 5,
								'post_type' => 'news',
								'post_status' => 'publish'
							);
							$posts = get_posts($args);
							global $post;
							?>
							<?php if ($posts) : foreach ($posts as $post) : setup_postdata($post); ?>
									<li>
										<span class="news-date"><?php echo get_post_time('Y.m.d'); ?></span>
										<span class="news-title"><a href="<?php echo get_permalink(); ?>" target="_blank"><?php echo get_the_title(); ?></a></span>
									</li>
								<?php endforeach; ?>
							<?php endif; ?>
							<?php if (empty($posts)) : // 記事が存在しない場合 
							?>
								<li>
									<p>現在、お知らせ情報はありません。</p>
								</li>
							<?php endif; ?>
						</ul>
					</div>

				<div id="csv-export" class="section">
						<?php get_template_part('template-parts/export-box'); ?>
					</div>
	

				</form>

			<?php } else { ?>

				<?php if (have_posts()) { ?>
					<?php
					while (have_posts()) :
						the_post();
					?>
						<article class="section">
							<header class="page-header">
								<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
								<div class="wck_post_meta">
									<span class="glyphicon glyphicon-time" aria-hidden="true"></span> <?php the_date(); ?>　
									<span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span> <?php the_category(','); ?>
								</div>
							</header>
							<div>
								<!-- [ 記事の本文 ] -->
								<?php the_content(); ?>
								<!-- [ /記事の本文 ] -->
							</div>
						</article>
					<?php endwhile; ?>
				<?php } // if ( have_posts() ) { 
				?>
			<?php } ?>
			
			<!-- [ /記事のループ ] -->
		</div>
		<!-- [ /#main ] -->

	</div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
(function($) {
    $('.gaichu_datepicker').datepicker({
        dateFormat: 'yy年mm月dd日'
    });
    $(".quickedit").click(function(){
        $(".quickeditarea").hide();
        $(this).parent().parent().next(".quickeditarea").show()
    })
})(jQuery);
</script>
<?php get_footer(); ?>