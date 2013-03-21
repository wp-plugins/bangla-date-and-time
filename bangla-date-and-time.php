<?php
/*
Plugin Name: Bangla Date and Time
Plugin URI: http://mithu.me/
Description: Bangla Date and Time simply converts date, time and all latin numbers into bangla number.
Version: 1.7.0
Author: m.h.mithu
Author URI: http://mithu.me/
License: GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
*/

function bangla_month_day( $str )
{
    $enMonth = array ( 'lm1' => 'January',
                       'lm2' => 'February',
                       'lm3' => 'March',
                       'lm4' => 'April',
                       'lm5' => 'May',
                       'lm6' => 'June',
                       'lm7' => 'July',
                       'lm8' => 'August',
                       'lm9' => 'September',
                       'lm10'=> 'October',
                       'lm11'=> 'November',
                       'lm12'=> 'December',
                       'sm1' => 'Jan',
                       'sm2' => 'Feb',
                       'sm3' => 'Mar',
                       'sm4' => 'Apr',
                       'sm5' => 'May',
                       'sm6' => 'Jun',
                       'sm7' => 'Jul',
                       'sm8' => 'Aug',
                       'sm9' => 'Sep',
                       'sm10'=> 'Oct',
                       'sm11'=> 'Nov',
                       'sm12'=> 'Dec'
                       );

    $enWeeks = array ( 'ld1' => 'Saturday',
                       'ld2' => 'Sunday',
                       'ld3' => 'Monday',
                       'ld4' => 'Tuesday',
                       'ld5' => 'Wednesday',
                       'ld6' => 'Thursday',
                       'ld7' => 'Friday',
                       'sd1' => 'Sat',
                       'sd2' => 'Sun',
                       'sd3' => 'Mon',
                       'sd4' => 'Tue',
                       'sd5' => 'Wed',
                       'sd6' => 'Thu',
                       'sd7' => 'Fri'
                       );

    $bnMonth = array ( 'lm1' => 'জানুয়ারি',
                       'lm2' => 'ফেব্রুয়ারি',
                       'lm3' => 'মার্চ',
                       'lm4' => 'এপ্রিল',
                       'lm5' => 'মে',
                       'lm6' => 'জুন',
                       'lm7' => 'জুলাই',
                       'lm8' => 'আগস্ট',
                       'lm9' => 'সেপ্টেম্বর',
                       'lm10'=> 'অক্টোবর',
                       'lm11'=> 'নভেম্বর',
                       'lm12'=> 'ডিসেম্বর',
                       'sm1' => 'জানু',
                       'sm2' => 'ফেব্রু',
                       'sm3' => 'মার্চ',
                       'sm4' => 'এপ্রি',
                       'sm5' => 'মে',
                       'sm6' => 'জুন',
                       'sm7' => 'জুলা',
                       'sm8' => 'আগ',
                       'sm9' => 'সেপ্টে',
                       'sm10'=> 'অক্টো',
                       'sm11'=> 'নভে',
                       'sm12'=> 'ডিসে'
                       );

    $bnWeeks = array ( 'ld1' => 'শনিবার',
                       'ld2' => 'রবিবার',
                       'ld3' => 'সোমবার',
                       'ld4' => 'মঙ্গলবার',
                       'ld5' => 'বুধবার',
                       'ld6' => 'বৃহস্পতিবার',
                       'ld7' => 'শুক্রবার',
                       'sd1' => 'শনি',
                       'sd2' => 'রবি',
                       'sd3' => 'সোম',
                       'sd4' => 'মঙ্গল',
                       'sd5' => 'বুধ',
                       'sd6' => 'বৃহঃ',
                       'sd7' => 'শুক্র'
                       );

    $mergeA1 = array_merge( $enMonth, $enWeeks );
    $mergeA2 = array_merge( $bnMonth, $bnWeeks );

    array_push( $mergeA1, 'am', 'pm' );
    array_push( $mergeA2, 'পূর্বাহ্ণ', 'অপরাহ্ণ' );

    return str_ireplace( $mergeA1, $mergeA2, $str );
}

$bdat = '<meta name=\'bangla-date-and-time\' content=\'bdat-v1.7.0\' />';

function latin_to_bangla( $int ) {

    $latDigt = array( 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 );
    $banDigt = array( '০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯' );

    return str_replace( $latDigt, $banDigt, $int );
}

function widget_bnDate($args) {
    extract($args);
    $dtBuffer = @explode(" ", str_replace(",", "", substr(substr(file_get_contents('http://mithu.me/date.php'), 23), 0, -3)));
    echo $before_widget . $before_title . __("আজকের বাংলা তারিখ") . $after_title;
    print "<ul><li>আজ $dtBuffer[0], $dtBuffer[1] $dtBuffer[2], $dtBuffer[3]</li><li>$dtBuffer[4] $dtBuffer[5], $dtBuffer[6] $dtBuffer[7]</li><li>এখন সময়, $dtBuffer[8] $dtBuffer[9]</li></ul>";
    echo $after_widget;
}

function bnDate_init() {
    register_sidebar_widget(__('আজকের বাংলা তারিখ'), 'widget_bnDate');     
}

    add_filter('the_date', 'bangla_month_day');
    add_filter('the_time', 'bangla_month_day');
    add_filter('get_comment_date', 'bangla_month_day');
    add_filter('get_comment_time', 'bangla_month_day');
    add_filter('date_i18n', 'latin_to_bangla', 10, 2);
    add_filter('number_format_i18n', 'latin_to_bangla', 10, 1);
    add_action('wp_head', function() { echo $GLOBALS['bdat']; });
    add_action('plugins_loaded', 'bnDate_init');

?>
