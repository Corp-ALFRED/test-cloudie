<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head();?>
</head>
<body <?php body_class(); ?>>
<header class="header">
  <div class="container">
    <div class="row">
      <div class="col-md-8">
      <h1 class="header-logo">
      <a href="<?php echo home_url( '/' ); ?>">
      <?php 
      $head_logo = '<img src="'.get_template_directory_uri().'/assets/images/head_logo.png" alt="Cloud IE System" />';
      echo apply_filters('bill_head_logo', $head_logo );
     ?>
      </a></h1>
		  <h2 class="header-description">見積請求顧客会計管理システム<span class="pc-only">｜</span><br class="sp-only">Cloud IE System Full Master</h2>
		  <a class="prepare-document-btn" href="<?php echo home_url('/wp-admin/'); ?>">各種書類を作成</a>
		</div>
		 <div class="col-md-4 sp-model">
			 <?php $options = get_option( 'bill-setting', Bill_Admin::options_default() ); ?>
			<p>
			ログイン企業アカウント：<?php echo nl2br( esc_textarea( $options['own-name'] ) ); ?>
			</p>
			 <?php $userdata = wp_get_current_user(); ?>
			<p>
			ログインユーザー：<?php echo esc_html($userdata->last_name); echo esc_html($userdata->first_name);?>【ID:<?php echo esc_html($userdata->user_login);?>】
			</p>
			<!-- <p>
			ユーザーアドレス：<?php echo esc_html($userdata->user_email);?>
			</p>
			-->
			<p>
				<?php global $wp_roles; $user_role_slug = $userdata->roles[0]; $namerole = $wp_roles->roles[$user_role_slug]['name'];?>
			ユーザー権限：<?php echo esc_html($namerole);?>
			</p>
		</div>
    </div>
<?php /*
    <div class="navbar navbar-inverse">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-ex-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        </button>
      </div>
      <div class="collapse navbar-collapse" id="navbar-ex-collapse">
      <?php
      $args = array(
          'theme_location' => 'Header Navigation',
          'items_wrap'     => '<ul id="%1$s" class="%2$s nav navbar-nav nav">%3$s</ul>',
          'fallback_cb'    => '',
          'echo'           => false,
      );
      $menu = wp_nav_menu( $args ) ;?>
      <?php if ( $menu ) : ?>
        <?php echo $menu; ?>
      <?php else : ?>
        <div class="menu-menu-1-container">
          <ul id="menu-menu-1" class="menu nav navbar-nav nav">
          <li class="menu-item"><a href="<?php echo home_url('/');?>">ホーム</a></li>
          <li class="menu-item"><a href="<?php echo home_url('/').'?post_type=estimate';?>">見積書</a></li>
          <li class="menu-item"><a href="<?php echo home_url('/').'?post_type=post';?>">請求書</a></li>
          </ul>
          </div>
      <?php endif; ?>
      </div>
      */?>
    </div>
  </div><!-- [ /.container ] -->
</header>