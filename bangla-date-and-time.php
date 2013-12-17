<?php
/*
* Plugin Name: Bangla Date and Time
* Plugin URI: http://mithu.me/
* Description: Bangla Date and Time simply converts date, time and all latin numbers into bangla number.
* Version: 1.8.1
* Author: M.H.Mithu
* Author URI: http://mithu.me/
* License: GNU General Public License v2.0 (or newer)
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/*  Copyright (C) 2013 M.H.Mithu (Email: mail@mithu.me)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


class Bangla_Date_Time {


    public function __construct() {

        add_filter('the_date', array($this, 'bangla_month_day'));
        add_filter('the_time', array($this, 'bangla_month_day'));
        add_filter('get_comment_date', array($this, 'bangla_month_day'));
        add_filter('get_comment_time', array($this, 'bangla_month_day'));
        add_filter('date_i18n', array($this, 'latin_to_bangla'), 10, 2);
        add_filter('number_format_i18n', array($this, 'latin_to_bangla'), 10, 1);
        add_action('plugins_loaded', array($this, 'bnDate_init'));

    }


    public function bangla_month_day($args) {

        $enMonth = array ('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
        $enDays  = array ('Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri');
        $bnMonth = array ('জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর', 'জানু', 'ফেব্রু', 'মার্চ', 'এপ্রি', 'মে', 'জুন', 'জুলা', 'আগ', 'সেপ্টে', 'অক্টো', 'নভে', 'ডিসে');
        $bnDays  = array ('শনিবার', 'রবিবার', 'সোমবার', 'মঙ্গলবার', 'বুধবার', 'বৃহস্পতিবার', 'শুক্রবার', 'শনি', 'রবি', 'সোম', 'মঙ্গল', 'বুধ', 'বৃহঃ', 'শুক্র');

        $enConv = array_merge($enMonth, $enDays);
        $bnConv = array_merge($bnMonth, $bnDays);

        array_push($enConv, 'am', 'pm');
        array_push($bnConv, 'পূর্বাহ্ণ', 'অপরাহ্ণ');

        return str_ireplace($enConv, $bnConv, $args);

    }


    public function latin_to_bangla($int) {

        $latDigit = array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
        $banDigit = array ('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');

        return str_replace($latDigit, $banDigit, $int);

    }


    private function curl_file_get_contents($url) {

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);

        $contents = curl_exec($curl);

        curl_close($curl);

        if($contents)
            return $contents;

        else
            return false;

    }


    public function widget_bnDate($args) {

        extract($args);

        $date = explode(' ', str_replace(',', '', substr(substr($this->curl_file_get_contents('http://sabujswapno.com/date.php') ? @$this->curl_file_get_contents('http://sabujswapno.com/date.php') : @file_get_contents('http://sabujswapno.com/date.php'), 23), 0, -3)));

        echo $before_widget . $before_title . __('আজকের বাংলা তারিখ') . $after_title;

        if($date[key($date)] <> NULL) {
            echo "<ul><li>আজ $date[0], $date[1] $date[2], $date[3]</li><li>$date[4] $date[5], $date[6] $date[7]</li><li>এখন সময়, $date[8] $date[9]</li></ul>";
        }
        else {
            echo "<ul><li>তারিখ প্রদর্শিত হচ্ছে না! অনুগ্রহ করে পেজটি আবার লোড করুন।</li></ul>";
        }

        echo $after_widget;

    }


    public function bnDate_init() {
        register_sidebar_widget(__('আজকের বাংলা তারিখ'), array($this, 'widget_bnDate'));     
    }


}

new Bangla_Date_Time;

?>
