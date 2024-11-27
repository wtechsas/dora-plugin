<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * @File        settings.php
 * 
 * @description This file defines administrative pages and settings for the plugin. 
 *              It integrates the plugin’s settings into Moodle's administration interface.
 *
 * @author      Alex Lopez <email>
 * @date        2024-11-26
 * 
 * @package     quizaccess_plugin_prueba
 * @category    admin
 * @copyright   2024 Your Name <you@example.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

GLOBAL $PAGE;

if ($ADMIN->fulltree) {
     /**
     * @purpose Adds an external page to the Moodle admin settings menu.
     * 
     * @param    string $name      The unique identifier for the settings page.
     * @param    string $title     The localized title of the page.
     * @param    string $url       The URL to which the page redirects.
     * 
     * @returns  void
     */
    $settings = new admin_externalpage(
        'quizaccess_monitoring_settings', 
        get_string('pluginname', 'quizaccess_plugin_prueba'), 
        $CFG->wwwroot . '/mod/quiz/accessrule/plugin_prueba/home.php'
    );

    $ADMIN->add('admin', $settings);
    /**
     * @purpose Redirects users to a custom admin page if the current page matches a specific section.
     * 
     * @param    string $currentURL The URL of the current page.
     * @param    string $targetURL  The URL to redirect the user to.
     * 
     * @returns  void
     */
    if (strpos($PAGE->url, "section=modsettingsquizcatplugin_prueba") !== false) {
        redirect($CFG->wwwroot . '/mod/quiz/accessrule/plugin_prueba/home.php');
    }
}

// Integration with Moodle Admin UI: Adds the plugin’s settings page to the Moodle administration interface.
// Redirect Behavior: Automatically redirects users to a custom page when accessing certain sections of the plugin's settings.