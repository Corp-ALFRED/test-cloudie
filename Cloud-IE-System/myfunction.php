<?php
////////////////////////////////////////////////////////////////////////////////////////////////
// カスタム投稿画面用にCSSを追加する
////////////////////////////////////////////////////////////////////////////////////////////////
function custom_post_admin_css_include()
{
    global $post_type;
    if ($post_type == 'post') {
?>
        <style>
            .inline-edit-tags-wrap {
                display: none;
            }
        </style>
    <?php
    }
    if ($post_type == 'gaichu') {
    ?>
        <style>
            #title,
            .title,
            .column-title {
                display: none;
            }

            #date,
            .date,
            .column-date {
                display: none;
            }
        </style>
<?php
    }
}
add_action('admin_head', 'custom_post_admin_css_include');

////////////////////////////////////////////////////////////////////////////////////////////////
//カスタム投稿の一覧画面にカスタムフィールドのカラムを追加
////////////////////////////////////////////////////////////////////////////////////////////////
add_filter('manage_gaichu_posts_columns', 'my_custom_posts_columns');
function my_custom_posts_columns($defaults)
{
    $defaults['bill_nameclient'] = '表示名';
    $defaults['gaichu_limit_date'] = '発生日';
    $defaults['gaichu_junge'] = '仕訳【借方】';
    $defaults['gaichu_nyukin_jotai'] = '支払い方法【貸方】';
    $defaults['gaichu_sokei'] = '支払額';
    return $defaults;
}

// 追加したカラムに値を表示させる
add_action('manage_gaichu_posts_custom_column', 'my_custom_posts_custom_column', 10, 2);
function my_custom_posts_custom_column($column, $post_id)
{
    switch ($column) {
        case 'bill_nameclient':
            $post_meta = get_post_meta($post_id, 'bill_nameclient', true);
            $base_url = get_stylesheet_directory_uri();
            // URL を生成
            $edit_url = "{$base_url}/wp-admin/post.php?post={$post_id}&action=edit";
            $view_url = "{$base_url}/gaichu/{$post_id}/";
            $duplicate_url = "{$base_url}/wp-admin/post-new.php?post_type=gaichu&master_id={$post_id}&table_copy_type=all&duplicate_type=full";

            echo $post_meta ? "<a href='{$edit_url}' aria-label='{$post_meta}(編集)'>{$post_meta}</a><div class='row-actions'><span class='edit'><a href='{$edit_url}'>編集</a> | </span><span class='inline hide-if-no-js'><button type='button' class='button-link editinline' aria-expanded='false'>クイック編集</button> | </span><span class='trash'><a href='{$base_url}/wp-admin/post.php?post={$post_id}&action=trash' class='submitdelete'>ゴミ箱へ移動</a> | </span><span class='view'><a href='{$view_url}' rel='bookmark'>表示</a> | </span><span class='newlink'><a href='{$duplicate_url}'>複製</a></span></div>" : '';
            break;
        case 'gaichu_limit_date':
            $post_meta = get_post_meta($post_id, 'gaichu_limit_date', true);
            echo $post_meta ? $post_meta : '';
            break;
        case 'gaichu_junge':
            $post_meta = get_post_meta($post_id, 'gaichu_junge', true);
            echo $post_meta ? $post_meta : '';
            break;
        case 'gaichu_nyukin_jotai':
            $post_meta = get_post_meta($post_id, 'gaichu_nyukin_jotai', true);
            echo $post_meta ? $post_meta : '';
            break;
        case 'gaichu_sokei':
            $post_meta = get_post_meta($post_id, 'gaichu_sokei', true);
            echo $post_meta ? $post_meta : '';
            break;
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////
//クイック編集の中にカスタムフィールドの入力フィールドを作る
////////////////////////////////////////////////////////////////////////////////////////////////
add_action('quick_edit_custom_box', 'display_my_custom_quickedit', 10, 2);
function display_my_custom_quickedit($column_name, $post_type)
{
    static $print_nonce = TRUE;
    if ($print_nonce) {
        $print_nonce = FALSE;
        wp_nonce_field('quick_edit_action', $post_type . '_edit_nonce'); //リクエスト強要（CSRF）対策
    }

    echo "<fieldset class='inline-edit-col-right inline-custom-meta'>";
    echo "<div class='inline-edit-col column-<{$column_name}'>";
    echo "<label class='inline-edit-group'>";

    switch ($column_name) {
        case 'gaichu_limit_date':
            echo "<span class='title'>発生日	</span>";
            echo "<input class='form-control' type='text' name='gaichu_limit_date_y' size='5'>";
            echo "<select name='gaichu_limit_date_m'>";
            for ($i = 1; $i <= 12; $i++) {
                echo "<option value='" . str_pad($i, 2, 0, STR_PAD_LEFT) . "'>{$i}月</option>";
            }
            echo "</select>";
            echo "<select name='gaichu_limit_date_d'>";
            for ($i = 1; $i <= 31; $i++) {
                echo "<option value='" . str_pad($i, 2, 0, STR_PAD_LEFT) . "'>{$i}日</option>";
            }
            echo "</select>";
            break;
        case 'gaichu_sokei':
            echo "<span class='title'>支払額	</span><input class='form-control' type='text' name='gaichu_sokei'>";
            break;
        case 'gaichu_junge':
            echo "<span class='title'>仕訳【借方】</span><br>";
            $gaichu_junge = array(
                '仕入' => "仕入れ",
                '外注費' => "外注費",
                '業務委託費' => "業務委託費",
                'カスタム科目' => "カスタム科目"
            );
            foreach ($gaichu_junge as $key => $val) {
                echo "<label style='display:inline'><input type='radio' name='gaichu_junge' value='{$key}'>{$val}</label>";
            }
            echo "<br><span class='title'>支払い方法【貸方】	</span><br>";
            $gaichu_nyukin_jotai = array(
                '現金' => "現金払い",
                '普通預金[未処理]' => "銀行振込[未処理]",
                '普通預金[処理済]' => "銀行振込[処理済]",
                '売掛金' => "クレジットカード",
                '前払金' => "前払い"
            );
            foreach ($gaichu_nyukin_jotai as $key => $val) {
                echo "<label style='display:inline'><input type='radio' name='gaichu_nyukin_jotai' value='{$key}'>{$val}</label>";
            }
            break;
    }

    echo "</label>";
    echo "</div>";
    echo "</fieldset>";
}


////////////////////////////////////////////////////////////////////////////////////////////////
//クイック編集時に既存の値をフィールドに表示
////////////////////////////////////////////////////////////////////////////////////////////////
function my_admin_edit_foot()
{
    global $post_type;
    switch ($post_type) {
        case 'gaichu':
            echo '<script type="text/javascript" src="', get_stylesheet_directory_uri() . '/js/admin_edit.js', '"></script>';
            echo "<script>
            (function($) {
            $('.gaichu_datepicker').datepicker({
                dateFormat: 'yy年mm月dd日'
            }); })(jQuery);</script>";
            break;
        case 'post':
            echo '<script type="text/javascript" src="', get_stylesheet_directory_uri() . '/js/admin_edit_post.js', '"></script>';
            break;
    }
}
add_action('admin_footer-edit.php', 'my_admin_edit_foot');

////////////////////////////////////////////////////////////////////////////////////////////////
//クイック編集での変更を保存する
////////////////////////////////////////////////////////////////////////////////////////////////
function save_gaichu_custom_meta($post_id)
{
    $slug = 'gaichu'; //カスタム投稿タイプのpost_type
    if ('gaichu' !== get_post_type($post_id)) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    $_POST += array("{$slug}_edit_nonce" => '');
    if (!wp_verify_nonce($_POST["{$slug}_edit_nonce"], 'quick_edit_action')) {
        return;
    }
    if (isset($_REQUEST['gaichu_limit_date_y'])) {
        $gaichu_limit_date = "{$_REQUEST['gaichu_limit_date_y']}年{$_REQUEST['gaichu_limit_date_m']}月{$_REQUEST['gaichu_limit_date_d']}日";
        update_post_meta($post_id, 'gaichu_limit_date', $gaichu_limit_date);
    }
    if (isset($_REQUEST['gaichu_junge'])) {
        update_post_meta($post_id, 'gaichu_junge', $_REQUEST['gaichu_junge']);
    }
    if (isset($_REQUEST['gaichu_sokei'])) {
        update_post_meta($post_id, 'gaichu_sokei', $_REQUEST['gaichu_sokei']);
    }
    if (isset($_REQUEST['gaichu_nyukin_jotai'])) {
        update_post_meta($post_id, 'gaichu_nyukin_jotai', $_REQUEST['gaichu_nyukin_jotai']);
    }
}
add_action('save_post_gaichu', 'save_gaichu_custom_meta');
////////////////////////////////////////////////////////////////////////////////////////////////
//カスタムフィールドの値をフリー検索できるようにする
////////////////////////////////////////////////////////////////////////////////////////////////
add_filter('posts_search', 'custom_search_engine', 10, 2);
function custom_search_engine($search, $wp_query)
{
    global $post_type;
    global $wpdb;                       //wpdbクラス
    if (!$wp_query->is_search) {         //検索処理以外は本関数から抜ける
        return $search;
    }
    if (!isset($wp_query->query_vars)) { //検索では無い時は抜ける
        return $search;
    }

    $seach_words = isset($wp_query->query_vars['s']) ? $wp_query->query_vars['s'] : '';

    $search_words = explode(' ', $seach_words);     //検索対象文字列を分割(スペース区切りが前提)

    if (count($search_words) > 0) {


        $search = '';                               //$searchを初期化

        if ($post_type == 'gaichu') $search .= " AND post_type = 'gaichu'";   //カスタム投稿タイプ'gaichu'の検索  

        foreach ($search_words as $search_word) { //検索対象文字列を取り出し、複数条件のSQL分を作成する。
            if (!empty($search_word)) {
                $search_word = '%' . esc_sql($search_word) . '%'; //部分一致のため文字列を"%"で囲む
                //create sql
                $search .= " AND ({$wpdb->posts}.post_title LIKE '{$search_word}' OR {$wpdb->posts}.ID IN ( SELECT distinct post_id FROM {$wpdb->postmeta} WHERE meta_value LIKE '{$search_word}' ) )";
            }
        }
    }
    return $search;
}


////////////////////////////////////////////////////////////////////////////////////////////////
//投稿(請求書)の一覧画面にカスタムフィールドのカラムを追加
////////////////////////////////////////////////////////////////////////////////////////////////
add_filter('manage_post_posts_columns', 'my_posts_columns');
function my_posts_columns($defaults)
{
    $defaults['bill_client'] = 'お客様ID';
    $defaults['bill_nameclient'] = 'お客様名';
    $defaults['bill_nyukin_jotai'] = '入金進捗';
    $defaults['bill_limit_date'] = '請求期日';
    return $defaults;
}

////////////////////////////////////////////////////////////////////////////////////////////////
//投稿(請求書)の一覧画面にカスタムフィールドのカラム並び替え
////////////////////////////////////////////////////////////////////////////////////////////////
function sort_column($columns)
{
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => 'タイトル',
        'bill_client' => 'お客様ID',
        'bill_nameclient' => 'お客様名',
        'bill_nyukin_jotai' => '入金進捗',
        'bill_limit_date' => '請求期日',
        'date' => '請求日',
        'author' => '作成者',
    );
    return $columns;
}
add_filter('manage_posts_columns', 'sort_column');

//追加したカラムに値を表示させる
add_action('manage_post_posts_custom_column', 'my_posts_custom_column', 10, 2);
function my_posts_custom_column($column, $post_id)
{
    switch ($column) {
        case 'bill_client':
            $post_meta = get_post_meta($post_id, 'bill_client', true);
            echo $post_meta ? "{$post_meta}" : '';
            break;
        case 'bill_nameclient':
            $post_meta = get_post_meta($post_id, 'bill_nameclient', true);
            echo $post_meta ? $post_meta : '';
            break;
        case 'bill_limit_date':
            $post_meta = get_post_meta($post_id, 'bill_limit_date', true);
            echo $post_meta ? $post_meta : '';
            break;
        case 'bill_nyukin_jotai':
            $post_meta = get_post_meta($post_id, 'bill_nyukin_jotai', true);
            echo $post_meta ? $post_meta : '';
            break;
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////
//クイック編集の中にカスタムフィールドの入力フィールドを作る
////////////////////////////////////////////////////////////////////////////////////////////////
add_action('quick_edit_custom_box', 'display_my_quickedit', 10, 2);
function display_my_quickedit($column_name, $post_type)
{
    global $post_type;
    if ($post_type == "post") {
        static $print_nonce = TRUE;
        if ($print_nonce) {
            $print_nonce = FALSE;
            wp_nonce_field('quick_edit_action', $post_type . '_edit_nonce'); //リクエスト強要（CSRF）対策
        }

        echo "<fieldset class='inline-edit-col-right inline-custom-meta'>";
        echo "<div class='inline-edit-col column-<{$column_name}'>";
        echo "<label class='inline-edit-group'>";

        switch ($column_name) {
            case 'bill_limit_date':
                echo "<span class='title'>請求期日	</span>";
                echo "<select name='bill_limit_date_y'>";
                for ($i = -1; $i <= 5; $i++) {
                    $year = date("Y", strtotime("-{$i} year"));
                    echo "<option value='{$year}'>{$year}年</option>";
                }
                echo "</select>";
                echo "<select name='bill_limit_date_m'>";
                for ($i = 1; $i <= 12; $i++) {
                    echo "<option value='" . str_pad($i, 2, 0, STR_PAD_LEFT) . "'>{$i}月</option>";
                }
                echo "</select>";
                echo "<select name='bill_limit_date_d'>";
                for ($i = 1; $i <= 31; $i++) {
                    echo "<option value='" . str_pad($i, 2, 0, STR_PAD_LEFT) . "'>{$i}日</option>";
                }
                echo "</select>";
                break;
            case 'bill_nyukin_jotai':
                echo "<span class='title'>入金進捗</span><br>";
                $bill_nyukin_jotai = array(
                    '未入金' => "未入金",
                    '入金済み' => "入金済み",
                    'ポイント支払済' => "ポイント支払済"
                );
                foreach ($bill_nyukin_jotai as $key => $val) {
                    echo "<label style='display:inline'><input type='radio' name='bill_nyukin_jotai' value='{$key}'>{$val}</label>";
                }
                break;
        }

        echo "</label>";
        echo "</div>";
        echo "</fieldset>";
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////
//クイック編集での変更を保存する
////////////////////////////////////////////////////////////////////////////////////////////////
function save_custom_meta($post_id)
{

    $slug = 'post'; //カスタムフィールドの保存処理をしたい投稿タイプを指定

    if ($slug !== get_post_type($post_id)) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    $_POST += array("{$slug}_edit_nonce" => '');
    if (!wp_verify_nonce($_POST["{$slug}_edit_nonce"], 'quick_edit_action')) {
        return;
    }

    if (isset($_REQUEST['bill_limit_date_y'])) {
        $bill_limit_date = "{$_REQUEST['bill_limit_date_y']}{$_REQUEST['bill_limit_date_m']}{$_REQUEST['bill_limit_date_d']}";
        update_post_meta($post_id, 'bill_limit_date', $bill_limit_date);
    }
    if (isset($_REQUEST['bill_nyukin_jotai'])) {
        update_post_meta($post_id, 'bill_nyukin_jotai', $_REQUEST['bill_nyukin_jotai']);
    }
}
add_action('save_post_post', 'save_custom_meta');

?>