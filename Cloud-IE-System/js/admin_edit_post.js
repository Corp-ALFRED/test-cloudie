// JavaScript Document
(function($) {
var $wp_inline_edit = inlineEditPost.edit;
inlineEditPost.edit = function( id ) {
$wp_inline_edit.apply( this, arguments );
var $post_id = 0;
if ( typeof( id ) == 'object' )
$post_id = parseInt( this.getId( id ) );
if ( $post_id > 0 ) {
var $edit_row = $( '#edit-' + $post_id );
var $post_row = $( '#post-' + $post_id );
//請求期日
var $bill_limit_date= $( '.column-bill_limit_date', $post_row ).html();
var $bill_limit_date_y = $bill_limit_date.slice(0, 4);
var $bill_limit_date_m = $bill_limit_date.slice(4, 6);
var $bill_limit_date_d = $bill_limit_date.slice(6, 8);
    
$( ':input[name="bill_limit_date_y"]', $edit_row ).val( $bill_limit_date_y );
$( ':input[name="bill_limit_date_m"]', $edit_row ).val( $bill_limit_date_m );
$( ':input[name="bill_limit_date_d"]', $edit_row ).val( $bill_limit_date_d );
    
$( ':input[name="bill_limit_date"]', $edit_row ).val( $bill_limit_date );
//入金進捗
var $bill_nyukin_jotai= $( '.column-bill_nyukin_jotai', $post_row ).html();
$( ':input[value="'+$bill_nyukin_jotai+'"]', $edit_row ).prop('checked', true);
}
};  
    
})(jQuery);