<?php
/**
 * Plugin Name: Bangla Date and Time
 * Plugin URI: https://github.com/mhmithu/bangla-date-and-time
 * Description: Bangla Date and Time simply converts all date and time into Bangla.
 * Version: 2.1
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

require plugin_dir_path(__FILE__).'general-funcs.php';

class Bangla_Date_Time extends Bangla_Date {

    public $merged;

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
        add_filter('wp_list_categories', array($this, 'filter_content'));
        add_filter('get_archives_link',  array($this, 'filter_content'));
        add_filter('date_i18n',          array($this, 'set_month_day'), 10, 3);
        add_filter('date_i18n',          array($this, 'bangla_number'), 10, 2);
        add_filter('number_format_i18n', array($this, 'bangla_number'), 10, 1);
        add_filter('get_calendar',       array($this, 'filter_calendar'));
        add_action('plugins_loaded',     array($this, 'register_widget'));        
    }

    /**
     * Replacing default month, weekday and time strings with Bangla
     * @access public
     * @param  string $args
     * @return string
     */
    public function set_month_day($args) {
        $ml = (array) $this->data->months->en->long;
        $ms = (array) $this->data->months->en->short;
        $dl = (array) $this->data->weekday->long;
        $ds = (array) $this->data->weekday->short;

        $this->merged = array_merge($ml, $ms, $dl, $ds);
        $this->merged['am'] = $this->data->timespan->am;
        $this->merged['pm'] = $this->data->timespan->pm;

        return str_ireplace(array_keys($this->merged), array_values($this->merged), $args);
    }

    /**
     * Refiltering contents for valid markup and URI
     * @access public
     * @param  string $content
     * @return string
     */
    public function filter_content($content) {
        $num  = explode(',', $this->data->number);
        $str  = $this->merged;
        $data = array_merge($num, $str);
        unset($data['am'], $data['pm']);

        $filtered = str_replace(array_keys($data), array_values($data), $content);

        preg_match_all('/(https?):\/\/[a-z\d.-]+(\/[^\'"]*)?/i', $filtered, $uris);
        preg_match_all('/[\'"]([\dp]{1,})[\'"]/u', $filtered, $qots);

        $uri = str_ireplace(array_values($data), array_keys($data), $uris[0]);
        $qot = str_ireplace(array_values($data), array_keys($data), $qots[0]);

        return str_ireplace(array_merge($uris[0], $qots[0]), array_merge($uri, $qot), $filtered);
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
     * Filtering calendars
     * @access public
     * @return string
     */
    public function filter_calendar() {
        return $this->filter_content(filter_get_calendar());
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
