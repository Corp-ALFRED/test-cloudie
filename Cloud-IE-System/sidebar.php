<!-- [ #sub ] -->
<div id="sub" class="col-md-3">

	<nav class="sub-section section">

		<h3 class="sub-section-title"><a href="<?php echo get_post_type_archive_link('estimate'); ?>">見積書</a></h3>

		<?php
		$args         = array(
			'title_li'         => '',
			'taxonomy'         => 'estimate-cat',
			'echo'             => 0,
			'show_option_none' => '',
		);
		$estimate_cat = wp_list_categories($args);
		if ($estimate_cat) {
			echo '<ul>';
			echo $estimate_cat;
			echo '</ul>';
		}
		?>

		<h3 class="sub-section-title"><a href="<?php echo home_url('/?post_type=post'); ?>">請求書<i class="fa fa-angle-right" aria-hidden="true"></i></a></h3>

		<?php
		$args     = array(
			'title_li'         => '',
			'echo'             => 0,
			'show_option_none' => '',
		);
		$category = wp_list_categories($args);
		if ($category) {
			echo '<ul>';
			echo $category;
			echo '</ul>';
		}
		?>
	</nav>
<nav class="sub-section section">

		<h3 class="sub-section-title"><a href="<?php echo get_post_type_archive_link('gaichu'); ?>">受請求・支払明細</a></h3>

		<?php
		$args         = array(
			'title_li'         => '',
			'taxonomy'		   =>'gaichu-cat',
			'echo'             => 0,
			'show_option_none' => '',
		);
		$estimate_cat = wp_list_categories($args);
		if ($estimate_cat) {
			echo '<ul>';
			echo $estimate_cat;
			echo '</ul>';
		}
		?>
	</nav>
	<nav class="sub-section section">
		<h3 class="sub-section-title">取引先</h3>
		<ul>
			<?php
			$args = array(
				'numberposts' => -1,
				'post_type' => 'client',
				'post_status' => 'publish',
				'order'          => 'ASC',
				'orderby'        => 'title',
			);
			$posts_slide = get_posts($args);
			global $post;
			?>
			<?php if ($posts_slide) : foreach ($posts_slide as $post) : setup_postdata($post); ?>
					<li>
						<a href="<?php echo get_permalink(); ?>" target="_blank"><?php echo get_the_title(); ?></a>
					</li>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if (empty($posts_slide)) : // 記事が存在しない場合 
			?>
				<li>
					<p>現在、お知らせ情報はありません。</p>
				</li>
			<?php endif; ?>
		</ul>
	</nav>

	<nav class="sub-section section">
		<h3 class="sub-section-title">検索</h3>
		<form id="form" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
			<input id="s-box" name="s" type="text" placeholder="キーワードを入力" />
			<button type="submit" id="s-btn-area">
				<div id="s-btn">検索</div>
			</button>
		</form>
	</nav>
</div>
<!-- [ /#sub ] -->