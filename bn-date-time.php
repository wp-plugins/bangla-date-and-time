<?php
/*
Plugin Name: Bangla Date and Time
Plugin URI: http://mithu.info/
Description: Bangla Date and Time simply converts date, time and all english numbers into bangla number including twelve english months name.
Version: 1.3
Author: m.h.mithu
Author URI: http://mithu.info/
*/
function bn($mhm)
{
	$eng = array('January','February','March','April','May','June','July','August','September','October','November','December',
				 'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec',
				 'Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday','Friday',
				 'Sat','Sun','Mon','Tue','Wed','Thu','Fri',
				 '1','2','3','4','5','6','7','8','9','0',
				 'am','pm','st','nd','rd','th');
	$bng = array('জানুয়ারি','ফেব্রুয়ারি','মার্চ','এপ্রিল','মে','জুন','জুলাই','আগস্ট','সেপ্টেম্বর','অক্টোবর','নভেম্বর','ডিসেম্বর',
				 'জানু','ফেব্রু','মার্চ','এপ্রি','মে','জুন','জুলা','আগ','সেপ্টে','অক্টো','নভে','ডিসে',
				 'শনিবার','রবিবার','সোমবার','মঙ্গলবার','বুধবার','বৃহস্পতিবার','শুক্রবার',
				 'শনি','রবি','সোম','মঙ্গল','বুধ','বৃহঃ','শুক্র',
				 '১','২','৩','৪','৫','৬','৭','৮','৯','০',
				 'পূর্বাহ্ণ','অপরাহ্ণ','&#173;','&#173;','&#173;','&#173;');
	$con = str_replace($eng, $bng, $mhm);
	return $con;
}
add_action('wp_head', function() {
echo '<meta name="bangla-date-and-time" content="bangla date and time v1.3" />';});
add_filter('the_date', 'bn');
add_filter('the_time', 'bn');
add_filter('the_views', 'bn');
add_filter('comments_number', 'bn');
add_filter('get_comment_date', 'bn');
add_filter('get_comment_time', 'bn');
?>
