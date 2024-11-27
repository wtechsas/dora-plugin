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
 * This file defines the version information, requirements, and metadata for the Moodle plugin. 
 * It is essential for Moodle to properly identify and manage the plugin.
 *
 * @package     quizaccess_plugin_prueba
 * @copyright   2024 Your Name <you@example.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

$plugin->cron = 0; //Specifies the frequency (in seconds) at which Moodle's cron should execute tasks related to this plugin.
                // Value: 0, indicating that this plugin does not require scheduled cron tasks.
$plugin->version   = 2024100902;       // Defines the plugin's version as an integer in the format YYYYMMDDXX. This helps Moodle determine if the plugin needs an update.
$plugin->requires  = 2022041900;       // Specifies the minimum version of Moodle required for the plugin to work properly.
$plugin->component = 'quizaccess_plugin_prueba'; // Defines the full component name, which must be unique in Moodle. The name follows the convention plugin_type_plugin_name.
$plugin->maturity  = MATURITY_STABLE;  // Indicates the plugin's maturity level to inform administrators of its stability.
$plugin->release   = '1.0.0';          // Specifies the user-friendly version of the plugin that users can see.
