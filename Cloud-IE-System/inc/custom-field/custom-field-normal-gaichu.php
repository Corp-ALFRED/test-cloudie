<?php
/*
* 請求書のカスタムフィールド（品目以外）
*/

class Gaichu_Normal_Custom_Fields
{
	public static function init()
	{
		add_action('admin_menu', array(__CLASS__, 'add_metabox'), 10, 2);
		add_action('save_post', array(__CLASS__, 'save_custom_fields'), 10, 2);
	}

	// add meta_box
	public static function add_metabox()
	{

		$id            = 'meta_box_gaichu_normal';
		$title		   = __('領収書受請求書項目', '');
		$callback      = array(__CLASS__, 'fields_form');
		$screen        = 'gaichu';
		$context       = 'advanced';
		$priority      = 'high';
		$callback_args = '';

		add_meta_box($id, $title, $callback, $screen, $context, $priority, $callback_args);
	}

	public static function fields_form()
	{
		global $post;

		$custom_fields_array = Gaichu_Normal_Custom_Fields::custom_fields_array();
		$befor_custom_fields = '';
		VK_Custom_Field_Builder::form_table($custom_fields_array, $befor_custom_fields);
	}

	public static function save_custom_fields()
	{
		$custom_fields_array = Gaichu_Normal_Custom_Fields::custom_fields_array();
		// $custom_fields_array_no_cf_builder = arra();
		// $custom_fields_all_array = array_merge(  $custom_fields_array, $custom_fields_array_no_cf_builder );
		VK_Custom_Field_Builder::save_cf_value($custom_fields_array);
	}

	public static function custom_fields_array()
	{

		$args         = array(
			'post_type'      => 'client',
			'posts_per_page' => -1,
			'order'          => 'ASC',
			'orderby'        => 'title',
		);
		$client_posts = get_posts($args);
		if ($client_posts) {
			$client = array('' => '選択してください');
			foreach ($client_posts as $key => $post) {
				// プルダウンに表示するかしないかの情報を取得
				$client_hidden = get_post_meta($post->ID, 'client_hidden', true);
				// プルダウン非表示にチェックが入っていない項目だけ出力
				if (!$client_hidden) {
					$client[$post->ID] = $post->post_title . "｜" . $post->client_invono;
				}
			}
		} else {
			$client = array('0' => '取引先が登録されていません');
		}

		$custom_fields_array = array(
			'bill_nameclient' => array(
				'label' => __('表示名', 'cloud-ie-system'),
				'type' => 'text',
				'description' => '',
				'required' => false,
			),
			'bill_nameclient_invoice' => array(
				'label' => __('インボイス番号', 'cloud-ie-system'),
				'type' => 'text',
				'before_text' => 'Ｔ',
				'description' => '',
				'required' => false,
			),
			'bill_client_name_manual'     => array(
				'label'       => __('取引先（イレギュラー）', 'cloud-ie-system'),
				'type'        => 'text',
				'description' => '複数回依頼の見込みのない取引先の場合はこちらに入力してください。<br>取引の多い取引先の場合は<a href="' . admin_url('/post-new.php?post_type=client') . '" target="_blank">予め登録</a>すると便利です。',
				'required'    => false,
			),
			'bill_client'     => array(
				'label'       => __('取引先（登録済）', 'cloud-ie-system'),
				'type'        => 'select',
				'description' => '取引先は<a href="' . admin_url('/post-new.php?post_type=client') . '" target="_blank">こちら</a>から登録してください。',
				'required'    => '',
				'options'     => $client,
			),
			'gaichu_limit_date' => array(
				'label' => __('発生日', 'cloud-ie-system'),
				'type'        => 'datepicker',
				'description' => '',
				'required'    => true,
			),
			'gaichu_junge' => array(
				'label' => __('仕訳【借方】', 'cloud-ie-system'),
				'type'        => 'radio',
				'description' => '',
				'required'    => false,
				'options'     => array(
					'仕入' => __('仕入れ', 'cloud-ie-system'),
					'外注費' => __('外注費', 'cloud-ie-system'),
					'業務委託費' => __('業務委託費', 'cloud-ie-system'),
					'カスタム科目' => __('カスタム科目', 'cloud-ie-system'),
				),
			),
			'gaichu_tekiyo' => array(
				'label' => __('摘要', 'cloud-ie-system'),
				'type' => 'text',
				'before_text' => '',
				'description' => '',
				'required' => false,
			),
			'gaichu_sokei' => array(
				'label' => __('支払い額', 'cloud-ie-system'),
				'type' => 'text',
				'before_text' => '￥',
				'description' => '',
				'required' => false,
			),
			'gaichu_zei' => array(
				'label' => __('税区分', 'cloud-ie-system'),
				'type'        => 'radio',
				'description' => '',
				'required'    => false,
				'options'     => array(
					'課税取引' => __('課税取引', 'cloud-ie-system'),
					'非課税取引' => __('非課税取引', 'cloud-ie-system'),
					'不課税取引' => __('不課税取引', 'cloud-ie-system'),
					'免税取引' => __('免税取引', 'cloud-ie-system'),
				),
			),
			'gaichu_nyukin_jotai' => array(
				'label' => __('支払い方法【貸方】', 'cloud-ie-system'),
				'type'        => 'radio',
				'description' => '',
				'required'    => false,
				'options'     => array(
					'現金' => __('現金払い', 'cloud-ie-system'),
					'普通預金[未処理]' => __('銀行振込[未処理]', 'cloud-ie-system'),
					'普通預金[処理済]' => __('銀行振込[処理済]', 'cloud-ie-system'),
					'売掛金' => __('クレジットカード', 'cloud-ie-system'),
					'前払金' => __('前払い', 'cloud-ie-system'),
				),
			),
			'gaichu_memo'       => array(
				'label'       => __('メモ', 'cloud-ie-system'),
				'type'        => 'textarea',
				'description' => 'この項目は表示されません',
				'required'    => false,
			),
			'gaichu_send_pdf'   => array(
				'label' => __('証憑および請求書保存', 'cloud-ie-system'),
				'type'        => 'image',
				'description' => '領収書および支払い請求書を保存できます。',
				'hidden'      => true,
			),
		);
		return $custom_fields_array;
	}
}

Gaichu_Normal_Custom_Fields::init();
