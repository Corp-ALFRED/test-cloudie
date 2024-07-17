<?php get_header(); ?>
<style>
    .quickeditarea{
        display: none;
    }
</style>
<?php
$page_post_type = bill_get_post_type();
?>

<?php get_template_part('template-parts/breadcrumb'); ?>
<?php
if(isset($_REQUEST['pg']) && $_REQUEST['pg']==1){
    if(isset($_REQUEST['bill_limit_date']) ){
        update_post_meta( $_REQUEST['post_id'], 'bill_limit_date', $_REQUEST['bill_limit_date'] ,'');
    }
    if(isset($_REQUEST['bill_nyukin_jotai']) ){
        update_post_meta( $_REQUEST['post_id'], 'bill_nyukin_jotai', $_REQUEST['bill_nyukin_jotai'] ,'');
    }
    if(isset($_REQUEST['post_category']) ){
        wp_set_object_terms( $_REQUEST['post_id'], $_REQUEST['post_category'], 'category'); 
    }
}
?>

<div class="container">
	<div class="row">

		<?php get_sidebar(); ?>

		<!-- [ #main ] -->
		<div id="main" class="col-md-9">
			<!-- [ 記事のループ ] -->

			<?php if (is_front_page() || is_archive() || is_tax()) {?>

				<form action="" method="get">

					<div class="section" id="search-box">
						<?php get_template_part('template-parts/search-box'); ?>
					</div>

					<?php $post_type = bill_get_post_type();
                    ?>

					<div class="section">
						<div class="document-wrapper">
							<?php
							$bill_all_total = 0; // $bill_all_total を初期化
							if (have_posts()) {
							?>
								<table class="table table-striped table-borderd">
									<tr>
										<th>書類</th>
										<?php if ($page_post_type['slug'] != 'client') { ?>
											<th>発行日</th>
										<?php } ?>

										<?php if ($post_type['slug'] != 'salary') { ?>
											<th>取引先</th>
										<?php } ?>

										<?php
										global $post; // 追加: $post をグローバルに設定

										if ($page_post_type['slug'] != 'client') {
										?>
											<th><?php echo ($post->post_type == 'client') ? '取引先' : '件名'; ?></th>
											<?php
											if ($post_type['slug'] != 'salary') {
											?>
												<th>カテゴリー</th>
											<?php
											} elseif ($post_type['slug'] == 'salary') {
											?>
												<th>支給分</th>
										<?php
											}
										}
										?>
										<th class="price_tit">金額<span class="caption">(税込)</span></th>
                                        <th></th>
									</tr>
									<?php
									while (have_posts()) :
										the_post();
									?>
										<tr>
											<!-- [ 書類 ] -->
											<td class="text-nowrap">
												<?php
												$post_type = bill_get_post_type();
												$post_type_slug = get_post_type();
												$post_type_object = get_post_type_object($post_type_slug);
												echo '<a href="' . esc_url(get_post_type_archive_link('url')) . '">' . esc_html($post_type_object->labels->name) . '</a>';
												?>
											</td>

											<?php if ($page_post_type['slug'] != 'client') { ?>
												<!-- [ 発行日 ] -->
												<td><?php echo esc_html(get_the_date('Y.m.d')); ?></td>
											<?php } ?>

											<?php if ($post_type['slug'] != 'salary') { ?>
												<!-- [ 取引先 ] -->
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

											<?php if ($page_post_type['slug'] != 'client') { ?>
												<!-- [ 件名 ] -->
												<td><a href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a></td>
												<!-- [ カテゴリー ] -->
												<td><?php echo bill_get_terms(); ?></td>
												<!-- [ 金額の表示 ] -->
												<?php
												global $post;
												$bill_total = bill_vektor_invoice_total_tax($post);
												$bill_all_total += $bill_total;
												?>
												<td>￥ <?php echo number_format($bill_total); ?></td>
                                                <td>
                                                    <?php if ($post_type_object->labels->name == '請求書') {
                                                        echo "<a href='#' class='quickedit'>クイック編集</a>";
                                                    }?>
                                                </td>
											<?php } ?>

										</tr>
                                    
                                    
                            <?php
                                    if ($post_type_object->labels->name == '請求書') {
                                    $args = array(
                                        'orderby' => 'id',
                                        'order' => 'ASC',
                                        'hide_empty' => false,
                                        // 引数「hide_empty」をfalseにすることで投稿がない場合でも表示
                                    );
                                    $categories = get_terms('category',$args);
                                    echo "<tr class='quickeditarea'><td colspan=7>";
                                    echo "<form method='post' action=''>";
                                    echo "<input type='hidden' name='pg' value='1'>";
                                    echo "<input type='hidden' name='post_id' value='".$post->ID."'>";
                                    
                                    echo "<label style='width:120px;'>請求期日</label><input type='text' class='gaichu_datepicker' name='bill_limit_date' value='".$post->bill_limit_date."'><br>";
                                    echo "<label style='width:120px;'>入金進捗</label>";
                                    $bill_nyukin_jotai=array(
                                        '未入金'=>"未入金",
                                        '入金済み'=>"入金済み",
                                        'ポイント支払済'=>"ポイント支払済");
                                    foreach($bill_nyukin_jotai as $key=>$val){
                                        $s = $key==$post->bill_nyukin_jotai ? "checked" :"";
                                        echo "<label style='padding:0 5px;'><input type='radio' name='bill_nyukin_jotai' value='{$key}' {$s}>{$val}</label>";
                                    }
                                    echo "<br>";
                                    echo "<label style='width:120px;'>請求カテゴリー</label>";
                                    $term_id = get_the_category();
                                    foreach( $term_id as $row ) {
                                        $term_id_a[]=$row->name;
                                    }
                                    foreach( $categories as $category ) {
                                        $s = in_array($category->name,$term_id_a) ? "checked" :"";
                                        echo "<label style='padding:0 5px;'><input type='checkbox' name='post_category[]' value='{$category->name}' {$s}>{$category->name}</label>";
                                    }
                                    echo "<br>";
                                    echo "<button class='btn-edit'>更新</button>";
                                    echo "<button class='btn-cancel'>キャンセル</button>";
                                    echo "</form>";
                                    echo "</td>";
                                    echo "</tr>";
                                    echo "<tr class='quickeditarea'><td colspan=7></td></tr>";
                            }
                                
                            ?>
                                    
                                    
                                    
									<?php endwhile; ?>
								</table>
								<?php the_posts_pagination(); ?>
							<?php
							} else {
								echo '<p>該当の書類はありません。</p>';
							} // if ( have_posts() ) {
							?>
						</div>
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
        dateFormat: 'yymmdd'
    });
    $(".quickedit").click(function(){
        $(".quickeditarea").hide();
        $(this).parent().parent().next(".quickeditarea").show()
    })
})(jQuery);
</script>
<?php get_footer(); ?>