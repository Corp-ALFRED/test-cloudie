<?php
/*
-------------------------------------------
  Load Module
  Theme setup
  Load Theme CSS & JS
  Load Admin CSS & JS
  WidgetArea initiate
  Add Post Type Client
  Remove_post_editor_support
  Replace Post Label
  Replace Document Title
  ログイン画面のロゴ
-------------------------------------------
*/

$theme_opt = wp_get_theme(get_template());
define('CLOUDIESYSTEM_THEME_VERSION', $theme_opt->Version);

/**
 * Composer Autoload
 */
$autoload_path = plugin_dir_path(__FILE__) . 'vendor/autoload.php';
// vendor ディレクトリがない状態で誤配信された場合に Fatal Error にならないようにファイルの存在確認.
if (file_exists($autoload_path)) {
	// Composer のファイルを読み込み ( composer install --no-dev )
	require_once $autoload_path;
}

/**
 * 存在する消費税率の配列
 * 
 * 消費税率の高い順に並べた配列。2番目以降は軽減税率フラグが立つ
 * @return $tax_array : 10%, 8%, 0%
 */
function bill_vektor_tax_array()
{
	$tax_array = array('10%', '8%', '0%');
	return $tax_array;
}

/** WordPressの更新通知を非表示にする **/
add_filter('pre_site_transient_update_core', '__return_null');

/*
-------------------------------------------
  Load Module
-------------------------------------------
*/
require_once dirname(__FILE__) . '/inc/custom-field-builder-config.php';
require_once dirname(__FILE__) . '/inc/custom-field/custom-field-normal-bill.php';
require_once dirname(__FILE__) . '/inc/custom-field/custom-field-normal-client.php';
require_once dirname(__FILE__) . '/inc/custom-field/custom-field-normal-estimate.php';
require_once dirname(__FILE__) . '/inc/custom-field/custom-field-normal-nohin.php';
require_once dirname(__FILE__) . '/inc/custom-field/custom-field-normal-gaichu.php';
require_once dirname(__FILE__) . '/inc/custom-field/custom-field-table.php';
require_once dirname(__FILE__) . '/inc/custom-field/custom-field-table-bill.php';
require_once dirname(__FILE__) . '/inc/setting-page/setting-page.php';
require_once dirname(__FILE__) . '/inc/duplicate-doc/duplicate-doc.php';
require_once dirname(__FILE__) . '/inc/export/class.csv-export.php';

get_template_part('inc/template-tags');
get_template_part('inc/functions-limit-view');
get_template_part('inc/functions-pre-get-posts');



/*
-------------------------------------------
  Theme setup
-------------------------------------------
*/
function bill_theme_title()
{
	// title tag
	add_theme_support('title-tag');
	// custom menu
	register_nav_menus(array('Header Navigation' => 'Header Navigation'));
}
add_action('after_setup_theme', 'bill_theme_title');

/*
-------------------------------------------
  Load Theme CSS & JS
-------------------------------------------
*/
function bill_theme_scripts()
{

	// 静的HTMLで読み込んでいたCSSを読み込む
	wp_enqueue_style('bill-css-bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), '3.3.6');
	wp_enqueue_style('bill-css', get_template_directory_uri() . '/assets/css/style.css', array('bill-css-bootstrap'), CLOUDIESYSTEM_THEME_VERSION);
	wp_enqueue_style('custom-css', get_template_directory_uri() . '/assets/css/custom.css', array('bill-css'), '');

	// テーマディレクトリ直下にある style.css を出力
	wp_enqueue_style('bill-theme-style', get_stylesheet_uri(), array('bill-css'), CLOUDIESYSTEM_THEME_VERSION);

	// テーマ用のjsを読み込む
	wp_enqueue_script('bill-js-bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), CLOUDIESYSTEM_THEME_VERSION, true);
	wp_register_script('datepicker', get_template_directory_uri() . '/inc/custom-field-builder/js/datepicker.js', array('jquery', 'jquery-ui-datepicker'), CLOUDIESYSTEM_THEME_VERSION, true);
	wp_enqueue_script('datepicker');
	wp_enqueue_script('height-catch', get_template_directory_uri() . '/assets/js/height-catch.js', array('jquery'), true);
	wp_enqueue_script('hover', get_template_directory_uri() . '/assets/js/hover.js', array('jquery'), true);
}
add_action('wp_enqueue_scripts', 'bill_theme_scripts');

/*
-------------------------------------------
  Load Admin CSS & JS
-------------------------------------------
*/
function bill_admin_scripts()
{
	// 管理画面用のcss
	wp_enqueue_style('bill-admin-css', get_template_directory_uri() . '/assets/css/admin-style.css', CLOUDIESYSTEM_THEME_VERSION, null);
	wp_enqueue_style('custom-admin-css', get_template_directory_uri() . '/assets/css/custom-admin.css', '');
}
add_action('admin_enqueue_scripts', 'bill_admin_scripts');

/*
-------------------------------------------
  faviconの設定
-------------------------------------------
*/
// 使用例：テーマ内の assets/images/favicon.png を指定
add_filter('get_site_icon_url', 'my_site_icon_url');

function my_site_icon_url($url)
{
	return get_theme_file_uri('assets/images/favicon.png');
}

/*
-------------------------------------------
  WidgetArea initiate
-------------------------------------------
*/
function bill_widgets_init()
{
	register_sidebar(
		array(
			'name'          => 'Sidebar',
			'id'            => 'sidebar-widget-area',
			'before_widget' => '<aside class="sub-section section %2$s" id="%1$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="sub-section-title">',
			'after_title'   => '</h4>',
		)
	);
}
add_action('widgets_init', 'bill_widgets_init');


/*
-------------------------------------------
  Add Post Type Client
-------------------------------------------
*/
add_action('init', 'bill_add_post_type_client', 0);
function bill_add_post_type_client()
{
	register_post_type(
		'client', /* カスタム投稿タイプのスラッグ */
		array(
			'labels'             => array(
				'name'      => '取引先・送付状',
				'view_item' => '送付状を表示',
				'edit_item' => '送付状を編集',
			),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'has_archive'        => false,
			'supports'           => array('title'),
			'menu_icon'          => 'dashicons-building',
			'menu_position'      => 3,
		)
	);
}
/*
-------------------------------------------
  Add Post Type Estimate
-------------------------------------------
*/
add_action('init', 'bill_add_post_type_estimate', 0);
function bill_add_post_type_estimate()
{
	register_post_type(
		'estimate',
		array(
			'labels'             => array(
				'name'         => '見積書',
				'edit_item'    => '見積書の編集',
				'add_new_item' => '見積書の作成',
			),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'has_archive'        => true,
			'supports'           => array('title'),
			'menu_icon'          => 'dashicons-media-spreadsheet',
			'menu_position'      => 5,
		)
	);
	register_taxonomy(
		'estimate-cat',
		'estimate',
		array(
			'hierarchical'          => true,
			'update_count_callback' => '_update_post_term_count',
			'label'                 => '見積書カテゴリー',
			'singular_label'        => '見積書カテゴリー',
			'public'                => true,
			'show_ui'               => true,
		)
	);
}
/*
-------------------------------------------
  Add Post Type nohin
-------------------------------------------
*/
add_action('init', 'bill_add_post_type_nohin', 0);
function bill_add_post_type_nohin()
{
	register_post_type(
		'nohin',
		array(
			'labels'             => array(
				'name'         => '納品書',
				'edit_item'    => '納品書の編集',
				'add_new_item' => '納品書の作成',
			),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'has_archive'        => true,
			'supports'           => array('title'),
			'menu_icon'          => 'dashicons-cart',
			'menu_position'      => 5,
		)
	);
	register_taxonomy(
		'nohin-cat',
		'nohin',
		array(
			'hierarchical'          => true,
			'update_count_callback' => '_update_post_term_count',
			'label'                 => '納品書カテゴリー',
			'singular_label'        => '納品書カテゴリー',
			'public'                => true,
			'show_ui'               => true,
		)
	);
}
/*
-------------------------------------------
  Add Post Type gaichu
-------------------------------------------
*/
add_action('init', 'bill_add_post_type_gaichu', 0);
function bill_add_post_type_gaichu()
{
	register_post_type(
		'gaichu',
		array(
			'labels' => array(
				'name' => '支払仕訳',
				'edit_item' => '領収書・受請求書の編集',
				'add_new_item' => '領収書・受請求書の追加',
			),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'has_archive'        => true,
			'supports'           => array('title'),
			'menu_icon'          => 'dashicons-exit',
			'menu_position' => 6,
		)
	);
	register_taxonomy(
		'gaichu-cat',
		'gaichu',
		array(
			'hierarchical'          => true,
			'update_count_callback' => '_update_post_term_count',
			'label'                 => '領収書・受請求書カテゴリー',
			'singular_label'        => '領収書・受請求書カテゴリー',
			'public'                => true,
			'show_ui'               => true,
		)
	);
}
/*
-------------------------------------------
  Remove_post_editor_support
-------------------------------------------
*/
function bill_remove_post_editor_support()
{
	remove_post_type_support('post', 'editor');
}
add_action('init', 'bill_remove_post_editor_support');


/*
-------------------------------------------
  Replace Post Label
-------------------------------------------
*/
function bill_change_post_type_args_post($args)
{
	if (isset($args['rest_base']) && $args['rest_base'] == 'posts') {
		$args['labels']['name_admin_bar'] = '請求書';
		$args['labels']['name']           = '請求書';
		$args['labels']['edit_item']      = '請求書の編集';
		$args['labels']['add_new_item']   = '請求書の作成';
	}
	return $args;
}
add_filter('register_post_type_args', 'bill_change_post_type_args_post');

/*
-------------------------------------------
  Replace Document Title
-------------------------------------------
*/
function bill_title_custom($title)
{
	$target_post_types = array('post', 'estimate', 'receipt');

	if (is_single()) {
		global $post;
		setup_postdata($post);
		$post_type = bill_get_post_type();
		if (in_array($post_type['slug'], $target_post_types)) {
			// 書類種別
			$title = $post_type['name'] . '_';
			// 取引先名
			$title .= bill_get_client_name($post);
			// 敬称
			$client_honorific = esc_html(get_post_meta($post->bill_client, 'client_honorific', true));
			if ($client_honorific) {
				$title .= $client_honorific . '_';
			} else {
				$title .= '御中_';
			}
			// 件名
			$title     .= get_the_title() . '_';
			$title .= get_the_date('Ymd');
		}
	}
	return strip_tags($title);
}
add_filter('wp_title', 'bill_title_custom', 11);
add_filter('pre_get_document_title', 'bill_title_custom', 11);

/*
-------------------------------------------
  未来の投稿の公開
-------------------------------------------
*/
// 予約投稿機能を無効化
add_action('save_post', 'bill_future_publish', 99);
add_action('edit_post', 'bill_future_publish', 99);
function bill_future_publish()
{
	global $wpdb;
	$sql  = 'UPDATE `' . $wpdb->prefix . 'posts` ';
	$sql .= 'SET post_status = "publish" ';
	$sql .= 'WHERE post_status = "future"';
	$wpdb->get_results($sql);
}

function bill_immediately_publish($id)
{
	global $wpdb;
	$q = 'UPDATE ' . $wpdb->posts . " SET post_status = 'publish' WHERE ID = " . (int) $id;
	$wpdb->get_results($q);
}
add_action('future_event', 'bill_immediately_publish');



/*
-------------------------------------------
  ログイン画面のロゴ
-------------------------------------------
*/

function custom_login_logo()
{ ?>
	<style>
		.login #login h1 a {
			width: 100%;
			height: 65px;
			background: url(<?php echo get_template_directory_uri(); ?>/assets/images/head_logo.png) no-repeat 0 0;
			background-size: contain;
			background-position: bottom;
		}
	</style>
<?php
}
add_action('login_enqueue_scripts', 'custom_login_logo');

/***********************************************************
お知らせ投稿　カスタム投稿
 ***********************************************************/

function create_post_type_news()
{
	register_post_type(
		'news',
		array(
			'labels' => array(
				'name' => 'お知らせ',
				'add_new' => '新規お知らせを追加',
				'add_new_item' => '新規お知らせを追加'
			),
			'public' => true,
			'has_archive' => true,
			'hierarchical' => true,
			'supports' => array('title', 'editor', 'thumbnail', 'author'),
			'show_in_rest' => true,
			'menu_position' => 6,
			'menu_icon' => get_stylesheet_directory_uri() . '/assets/images/custom-post-icon.png', /* the icon for the custom post type menu */
			'exclude_from_search' => false,
		)
	);
	register_taxonomy_for_object_type('post_tag', 'news');
}
add_action('init', 'create_post_type_news');


/***********************************************************
 固定ページの複製
 ***********************************************************/
function duplicate_page()
{
	global $wpdb;
	if (!(isset($_GET['post']) || isset($_POST['post'])  || (isset($_REQUEST['action']) && 'duplicate_page' == $_REQUEST['action']))) {
		wp_die('No post to duplicate has been supplied!');
	}

	// ページIDを取得
	$post_id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);
	// ページ情報の取得
	$post = get_post($post_id);

	// 新規で固定ページの作成
	$new_page = array(
		'post_title' => $post->post_title . ' (Copy)',
		'post_content' => $post->post_content,
		'post_status' => 'draft',
		'post_date' => current_time('mysql'),
		'post_author' => $post->post_author,
		'post_type' => $post->post_type
	);

	// データベースに固定ページを作成
	$new_page_id = wp_insert_post($new_page);

	// 情報を取得して挿入
	$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
	if (count($post_meta_infos) != 0) {
		$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
		foreach ($post_meta_infos as $post_meta_info) {
			$meta_key = $post_meta_info->meta_key;
			$meta_value = addslashes($post_meta_info->meta_value);
			$sql_query_sel[] = "SELECT $new_page_id, '$meta_key', '$meta_value'";
		}
		$sql_query .= implode(" UNION ALL ", $sql_query_sel);
		$wpdb->query($sql_query);
	}

	// 複製完了したらリダイレクト
	wp_redirect(admin_url('post.php?action=edit&post=' . $new_page_id));
	exit;
}

add_action('admin_action_duplicate_page', 'duplicate_page');

function add_duplicate_pagelink($actions, $post)
{
	if (current_user_can('edit_pages')) {
		$actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=duplicate_page&amp;post=' . $post->ID, basename(__FILE__), 'duplicate_nonce') . '" title="Duplicate this item" rel="permalink">ページの複製</a>';
	}
	return $actions;
}
/***********************************************************
 権限名の変更
 ***********************************************************/
function change_role_name()
{
	global $wp_roles;
	if (!isset($wp_roles)) $wp_roles = new WP_Roles();

	$wp_roles->roles['administrator']['name'] = 'システムマスター';
	$wp_roles->role_names['administrator'] = 'システムマスター';

	$wp_roles->roles['editor']['name'] = '管理マスター';
	$wp_roles->role_names['editor'] = '管理マスター';

	$wp_roles->roles['author']['name'] = 'スタッフ';
	$wp_roles->role_names['author'] = 'スタッフ';

	$wp_roles->roles['contributor']['name'] = '記事閲覧・作成のみ';
	$wp_roles->role_names['contributor'] = '記事閲覧・作成のみ';

	$wp_roles->roles['subscriber']['name'] = '閲覧のみ';
	$wp_roles->role_names['subscriber'] = '閲覧のみ';
}
add_action('init', 'change_role_name');

// コメント非表示
function comment_status_none($open, $post_id)
{
	$post = get_post($post_id);
	//投稿のコメントを投稿できないようにします
	if ($post->post_type == 'post') {
		return false;
	}
	//固定ページのコメントを投稿できないようにします
	if ($post->post_type == 'page') {
		return false;
	}
	//メディアのコメントを投稿できないようにします
	if ($post->post_type == 'attachment') {
		return false;
	}
	return false;
}
add_filter('comments_open', 'comment_status_none', 10, 2);
// 編集者（editor）にユーザー関連の管理権限を付与
add_action('admin_init', function () {
	$role = get_role('editor');


	$role->add_cap('create_users');
	$role->add_cap('delete_users');
	$role->add_cap('edit_users');
	$role->add_cap('list_users');
	$role->add_cap('promote_users');
	$role->add_cap('remove_users');
	$role->add_cap('create_posts');
	$role->remove_cap('edit_theme_options');
	$role->remove_cap('delete_others_pages');
	$role->remove_cap('delete_pages');
	$role->remove_cap('delete_private_pages');
	$role->remove_cap('delete_published_pages');
	$role->remove_cap('edit_others_pages');
	$role->remove_cap('edit_pages');
	$role->remove_cap('edit_private_pages');
	$role->remove_cap('edit_published_pages');
	$role->remove_cap('publish_pages');
	$role->remove_cap('read_private_pages');
	$role->remove_cap('edit_dashboard');
	$role->remove_cap('moderate_comments');
	$role->remove_cap('edit_dashboard');
});

// 編集者（author）にユーザー関連の管理権限を付与
add_action('admin_init', function () {
	$role = get_role('author');


	$role->add_cap('edit_others_posts');
});


// サイドメニューを非表示
function remove_menus()
{
	remove_menu_page('index.php'); // dashbord
	remove_menu_page('edit.php?post_type=page'); // 固定ページ
	remove_menu_page('edit-comments.php'); // コメント
	remove_menu_page('tools.php'); // ツール
}
add_action('admin_menu', 'remove_menus', 999);

/***********************************************************
 アーカイブカスタムフィールド順変更
 ***********************************************************/

function change_posts_per_page($query)
{

	/* 管理画面,メインクエリに干渉しないために必須 */
	if (is_admin() || !$query->is_main_query()) {
		return;
	}

	/* カスタム投稿「gaichu」アーカイブページの表示件数を10件、投稿日の昇順でソート */
	if ($query->is_post_type_archive('gaichu')) {
		$query->set('posts_per_page', '-1'); // 10件
		$query->set('order', 'ASC'); // 昇順
		$query->set('meta_key', 'gaichu_limit_date'); // カスタムフィールドの値
		$query->set('orderby', 'meta_value'); // 投稿日
		return;
	}
}

add_action('pre_get_posts', 'change_posts_per_page');

/***********************************************************
プラグインの更新通知を非表示にする
 ***********************************************************/
remove_action('load-update-core.php', 'wp_update_plugins');
add_filter('pre_site_transient_update_plugins', '__return_null');


require_once dirname(__FILE__) . '/myfunction.php';

/***********************************************************
//更新の確認
 ***********************************************************/
require 'plugin-update-checker-master/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/Corp-ALFRED/test-cloudie',
	__FILE__, //Full path to the main plugin file or functions.php.
	'Cloud IE System'
);

//Optional: If you're using a private repository, specify the access token like this:
$myUpdateChecker->setAuthentication('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');

//Optional: Set the branch that contains the stable release.
$myUpdateChecker->setBranch('master');
