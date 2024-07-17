jQuery(function() {
    //セレクトボックスが切り替わったら発動
	jQuery(document).ready(function() {
		// ページ読み込み時にも実行されるコード
		var bunsho = jQuery('[name=bill_client] option:selected').text();
		var result = bunsho.split('｜');
		if (bunsho == '選択してください') {
			jQuery('input#bill_nameclient').val('登録なし');
			jQuery('input#bill_nameclient_invoice').val('登録なし');
		} else {
			jQuery('input#bill_nameclient').val(result[0]);
			jQuery('input#bill_nameclient_invoice').val(result[1]);
		}

		// 要素の値が変更された場合のコード
		jQuery('[name=bill_client]').change(function() {
			var bunsho = jQuery('[name=bill_client] option:selected').text();
			var result = bunsho.split('｜');
			if (bunsho == '選択してください') {
				jQuery('input#bill_nameclient').val('登録なし');
				jQuery('input#bill_nameclient_invoice').val('登録なし');
			} else {
				jQuery('input#bill_nameclient').val(result[0]);
				jQuery('input#bill_nameclient_invoice').val(result[1]);
			}
		});
	});
jQuery('[name=gaichu_limit_date]').change(function() {
      var val = jQuery('[name=gaichu_limit_date]').val();
      var valY = val.substr( 0, 4 );
	  var valM = val.substring( 4, 6 );
	  var valD = val.substring( 6, 8 );
	  jQuery('input#gaichu_limit_date').val(valY+'年'+valM+'月'+valD+'日');
    });
jQuery('[name=gaichu_sokei]').click(function() {
	    var kanmanuki = jQuery('[name=gaichu_sokei]').val();
		var kanmanashi = String(kanmanuki).replace(/,/g, '');
	    jQuery('input#gaichu_sokei').val(kanmanashi);
    });

jQuery('[name=gaichu_sokei]').change(function() {
		var kane = jQuery('[name=gaichu_sokei]').val();
		var kanekei = String(kane).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
	    jQuery('input#gaichu_sokei').val(kanekei);
    });
jQuery('[name=gaichu_sokei]').blur(function() {
		var kane = jQuery('[name=gaichu_sokei]').val();
		var kane2 = String(kane).replace(/,/g, '');
		var kanekei = String(kane2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
	    jQuery('input#gaichu_sokei').val(kanekei);
    });
	
	
  });