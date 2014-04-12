<?php
/**
 * Plugin Name: Bangla Date and Time
 * Plugin URI: https://github.com/mhmithu/bangla-date-and-time
 * Description: Bangla Date and Time simply converts all date and time into Bangla.
 * Version: 2.0
 * Author: Mirazul Hossain Mithu
 * Author URI: http://mithu.me/
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * ----------------------------------------------------------------------
 * Copyright (C) 2014  Mirazul Hossain Mithu  (email: mail@mithu.me)
 * ----------------------------------------------------------------------
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * ----------------------------------------------------------------------
 */

class Bangla_Date_Time {

    public $lib;    # Bangla_Date object library

    /**
     * Class constructor method
     * @access public
     * @return void
     */
    public function __construct() {
        // Including Bangla Date class
        require plugin_dir_path(__FILE__).'class.Bangla_Date.php';

        // Instantiation of Date class
        $tz_string = get_option('timezone_string');
        $timezone  = !empty($tz_string) ? get_option('timezone_string') : date_default_timezone_get();
        $this->lib = new Bangla_Date(time(), new DateTimeZone($timezone));

        // Passing hooks into constructor method
        $this->_hooks();
    }

    /**
     * Plugin hooks
     * @access private
     * @return void
     */
    private function _hooks() {
        add_filter('date_i18n',          array($this, 'set_month_day'));
        add_filter('date_i18n',          array($this, 'bangla_number'), 10, 2);
        add_filter('number_format_i18n', array($this, 'bangla_number'), 10, 1);
        add_action('plugins_loaded',     array($this, 'register_widget'));        
    }

    /**
     * Replacing default month, weekday and time strings with Bangla
     * @access public
     * @param  string $args
     * @return string
     */
    public function set_month_day($args) {
        // Parsing data
        $enml = (array) $this->lib->data->xpath('//long/en/month');
        $enms = (array) $this->lib->data->xpath('//short/en/month');
        $bnml = (array) $this->lib->data->xpath('//long/bn/month');
        $bnms = (array) $this->lib->data->xpath('//short/bn/month');
        $endl = (array) $this->lib->data->xpath('//en/long/day');
        $ends = (array) $this->lib->data->xpath('//en/short/day');
        $bndl = (array) $this->lib->data->xpath('//bn/long/day');
        $bnds = (array) $this->lib->data->xpath('//bn/short/day');

        $en_month = array_merge($enml, $enms);
        $bn_month = array_merge($bnml, $bnms);
        $en_days  = array_merge($endl, $ends);
        $bn_days  = array_merge($bndl, $bnds);
        $en_array = array_merge($en_month, $en_days);
        $bn_array = array_merge($bn_month, $bn_days);

        array_push($en_array, 'am', 'pm');
        array_push($bn_array, $this->lib->data->timespan->am, $this->lib->data->timespan->pm);

        return str_ireplace($en_array, $bn_array, $args);
    }

    /**
     * Alias method for bangla_digit
     * @access public
     * @param  integer $int
     * @return string
     */
    public function bangla_number($int) {
        return $this->lib->bangla_digit($int);
    }

    /**
     * Sidebar date widget
     * @access public
     * @param  array $args
     * @return string
     */
    public function bangla_date_widget($args) {
        extract($args);
        $widget  = $before_widget . $before_title . __('আজকের বাংলা তারিখ') . $after_title;
        $widget .= '<ul>';
        $widget .= '<li>আজ ';
        $widget .= $this->lib->get_date()->ts['weekday'].', ';
        $widget .= $this->lib->get_date()->en['date'] . $this->lib->get_date()->en['suffix'].' ';
        $widget .= $this->lib->get_date()->en['month'].', ';
        $widget .= $this->lib->get_date()->en['year'];
        $widget .= '</li>';
        $widget .= '<li>';
        $widget .= $this->lib->get_date()->bn['date'] . $this->lib->get_date()->bn['suffix'].' ';
        $widget .= $this->lib->get_date()->bn['month'].', ';
        $widget .= $this->lib->get_date()->bn['year'];
        $widget .= ' বঙ্গাব্দ (' .$this->lib->get_date()->bn['season']. ')';
        $widget .= '</li>';
        $widget .= '<li>';
        $widget .= $this->lib->get_date()->ar['date'] . $this->lib->get_date()->ar['suffix'].' ';
        $widget .= $this->lib->get_date()->ar['month'].', ';
        $widget .= $this->lib->get_date()->ar['year'];
        $widget .= ' হিজরী';
        $widget .= '</li>';
        $widget .= '<li>এখন সময়, ';
        $widget .= $this->lib->get_date()->ts['prefix'].' '.$this->lib->get_date()->ts['time'];
        $widget .= '</li>';
        $widget .= '</ul>';
        $widget .= $after_widget;

        echo $widget;

    }

    /**
     * Registering sidebar widget
     * @access public
     * @return void
     */
    public function register_widget() {
        register_sidebar_widget(__('আজকের বাংলা তারিখ'), array($this, 'bangla_date_widget'));     
    }


}

new Bangla_Date_Time;
