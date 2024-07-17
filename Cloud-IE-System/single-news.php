<?php get_header(); ?>

<?php
$page_post_type = bill_get_post_type();
$terms = get_the_terms(get_the_ID(), 'your_taxonomy'); // 'your_taxonomy' を実際のタクソノミー名に変更
?>

<?php get_template_part('template-parts/breadcrumb'); ?>

<div class="container">
    <div class="row">

        <?php get_sidebar(); ?>

        <!-- [ #main ] -->
        <div id="main" class="col-md-9 section">

            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                <!-- [ 記事のループ ] -->
                <header>
                    <h3 class="ttl"><?php the_title(); ?></h3>
                    <time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('Y.m.d'); ?></time>

					<?php if (!empty($terms) && is_array($terms)) : ?>
					<?php foreach ($terms as $term) : ?>
					<span class="category"><?php echo $term->name; ?></span>
					<?php endforeach; ?>
					<?php endif; ?>

                </header>
                <div class="edit-area">
                    <?php the_content(); ?>
                </div>

            <?php endwhile; endif; ?>
            <!-- [ /記事のループ ] -->

        </div>
        <!-- [ /#main ] -->

    </div>
</div>

<?php get_footer(); ?>
