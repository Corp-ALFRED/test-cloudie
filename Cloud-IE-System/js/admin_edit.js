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
//発行日
var $gaichu_limit_date= $( '.column-gaichu_limit_date', $post_row ).html();
var $gaichu_limit_date_y = $gaichu_limit_date.slice(0, 4);
var $gaichu_limit_date_m = $gaichu_limit_date.slice(5, 7);
var $gaichu_limit_date_d = $gaichu_limit_date.slice(8, 10);
    
$( ':input[name="gaichu_limit_date_y"]', $edit_row ).val( $gaichu_limit_date_y );
$( ':input[name="gaichu_limit_date_m"]', $edit_row ).val( $gaichu_limit_date_m );
$( ':input[name="gaichu_limit_date_d"]', $edit_row ).val( $gaichu_limit_date_d );
    
$( ':input[name="gaichu_limit_date"]', $edit_row ).val( $gaichu_limit_date );
//仕訳【借方】
var $gaichu_junge= $( '.column-gaichu_junge', $post_row ).html();
$( ':input[value="'+$gaichu_junge+'"]', $edit_row ).prop('checked', true);
//支払い方法【貸方】
var $gaichu_nyukin_jotai= $( '.column-gaichu_nyukin_jotai', $post_row ).html();
$( ':input[value="'+$gaichu_nyukin_jotai+'"]', $edit_row ).prop('checked', true);
//支払い方法【貸方】
var $gaichu_sokei= $( '.column-gaichu_sokei', $post_row ).html();
$( ':input[name="gaichu_sokei"]', $edit_row ).val( $gaichu_sokei );
}
};  
})(jQuery);