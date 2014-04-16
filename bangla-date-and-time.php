<?php
/**
 * Plugin Name: Bangla Date and Time
 * Plugin URI: https://github.com/mhmithu/bangla-date-and-time
 * Description: Bangla Date and Time simply converts all date and time into Bangla.
 * Version: 2.0.1
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

require plugin_dir_path(__FILE__).'class.Bangla_Date.php';

class Bangla_Date_Time extends Bangla_Date {

    /**
     * Class constructor method
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct(current_time('timestamp'));
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
        $enml = (array) $this->data->xpath('//long/en/month');
        $enms = (array) $this->data->xpath('//short/en/month');
        $bnml = (array) $this->data->xpath('//long/bn/month');
        $bnms = (array) $this->data->xpath('//short/bn/month');
        $endl = (array) $this->data->xpath('//en/long/day');
        $ends = (array) $this->data->xpath('//en/short/day');
        $bndl = (array) $this->data->xpath('//bn/long/day');
        $bnds = (array) $this->data->xpath('//bn/short/day');

        $en_month = array_merge($enml, $enms);
        $bn_month = array_merge($bnml, $bnms);
        $en_days  = array_merge($endl, $ends);
        $bn_days  = array_merge($bndl, $bnds);
        $en_array = array_merge($en_month, $en_days);
        $bn_array = array_merge($bn_month, $bn_days);

        array_push($en_array, 'am', 'pm');
        array_push($bn_array, $this->data->timespan->am, $this->data->timespan->pm);

        return str_ireplace($en_array, $bn_array, $args);
    }

    /**
     * Alias method for bangla_digit
     * @access public
     * @param  integer $int
     * @return string
     */
    public function bangla_number($int) {
        return $this->bangla_digit($int);
    }

    /**
     * Sidebar date widget
     * @access public
     * @param  array $args
     * @return string
     */
    public function bangla_date_widget($args) {
        extract($args);
        $widget  = $before_widget . $before_title . 'আজকের বাংলা তারিখ' . $after_title;
        $widget .= '<ul>';
        $widget .= '<li>আজ ';
        $widget .= $this->get_date()->ts['weekday'].', ';
        $widget .= $this->get_date()->en['date'] . $this->get_date()->en['suffix'].' ';
        $widget .= $this->get_date()->en['month'].', ';
        $widget .= $this->get_date()->en['year'];
        $widget .= '</li>';
        $widget .= '<li>';
        $widget .= $this->get_date()->bn['date'] . $this->get_date()->bn['suffix'].' ';
        $widget .= $this->get_date()->bn['month'].', ';
        $widget .= $this->get_date()->bn['year'];
        $widget .= ' বঙ্গাব্দ (' .$this->get_date()->bn['season']. ')';
        $widget .= '</li>';
        $widget .= '<li>';
        $widget .= $this->get_date()->ar['date'] . $this->get_date()->ar['suffix'].' ';
        $widget .= $this->get_date()->ar['month'].', ';
        $widget .= $this->get_date()->ar['year'];
        $widget .= ' হিজরী';
        $widget .= '</li>';
        $widget .= '<li>এখন সময়, ';
        $widget .= $this->get_date()->ts['prefix'].' '.$this->get_date()->ts['time'];
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
        register_sidebar_widget('আজকের বাংলা তারিখ', array($this, 'bangla_date_widget'));     
    }


}

new Bangla_Date_Time;
