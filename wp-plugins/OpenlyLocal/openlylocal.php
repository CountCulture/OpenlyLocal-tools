<?php
/*
Plugin Name: OpenlyLocal
Plugin URI: http://philipjohn.co.uk/category/plugins/openlylocal/
Description: Provides tools for bloggers based on <a href="http://openlylocal.com">OpenlyLocal</a> data
Author: Philip John
Version: 0.2b
Author URI: http://philipjohn.co.uk
*/
/*  Copyright 2009  Philip John Ltd  (email : talkto@philipjohn.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the Affero General Public License as published
    by the Affero Inc; either version 2 of the License, or (at your
    option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the Affero General Public License
    along with this program; if not, see http://www.affero.org/oagpl.html
*/

// grab the code for the upcoming meetings widget
include('olum_widget.php');

// Dashboard widgets init function 
function ol_add_dashboard_widgets(){
	wp_add_dashboard_widget('ol-upcoming-council-meetings-widget', 'Upcoming Council Meetings', 'ol_upcoming_meetings_dbwidget');
}

// Initialising function
function openlylocal_init(){
	register_sidebar_widget(__('Upcoming council meetings'), 'ol_upcoming_meetings_widget');
    register_widget_control(   'Upcoming council meetings', 'ol_upcoming_meetings_widget_options');
    
    // Check for OLUM widget settings, if none create defaults
    if (!get_option('olum_widget')){
        $olum_defaults = array(
            'title' => __('Upcoming council meetings'),
            'pre_text' => '',
            'authority' => 1
        );
        add_option('olum_widget', $olum_defaults);
    }
}

add_action("plugins_loaded", "openlylocal_init");
add_action('wp_dashboard_setup', 'ol_add_dashboard_widgets');

?>