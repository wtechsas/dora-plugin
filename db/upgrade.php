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
 * @File upgrade.php
 * 
 * @description This file defines the upgrade steps for the plugin. It contains the logic 
 *              to handle changes to the database schema when the plugin is upgraded. 
 *              The upgrade function checks the version of the plugin and performs necessary database 
 *              modifications to ensure compatibility with the new version.
 * 
 * @author  Alex Lopez <your.email@example.com>
 * @date    2024-11-26
 */
defined('MOODLE_INTERNAL') || die();

/**
 * @purpose Executes the upgrade steps for the plugin. It checks the old version of the plugin 
 *          and applies any necessary changes to the database schema to bring it up to the latest version.
 *          This function ensures that the plugin's database tables are properly updated when the plugin is upgraded.
 * 
 * @param int $oldversion The version of the plugin before the upgrade. It is used to determine 
 *                        whether the upgrade steps need to be applied.
 * 
 * @returns bool Returns `true` if the upgrade was successful, indicating that the plugin is now 
 *               at the latest version. If there are no changes to be made, it still returns `true`.
 */
function xmldb_quizaccess_plugin_prueba_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager(); // Loads the database manager and setup.

    if ($oldversion < 2024090901) {


        $table = new xmldb_table('quizaccess_plugin_prueba_add');

        // Add needed columns.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('quizid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, false, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, false, null);
        $table->add_field('attempt', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, false, null);
        $table->add_field('answer_plain', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('answer_encrypted', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, false, null);

        // Adding keys to table lti_usage.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('userid', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);
        $table->add_key('quizid', XMLDB_KEY_FOREIGN, ['quizid'], 'quiz', ['id']);

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        upgrade_plugin_savepoint(true, 2024090901, 'quizaccess', 'plugin_prueba');
    }
    
    return true;
}
