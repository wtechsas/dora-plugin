<?php
/**
 * @File access.php
 * 
 * @description This file defines the necessary capabilities for viewing reports within the plugin.
 *              It sets up the permissions for various roles such as teachers, editing teachers, and managers,
 *              specifying who can view the reports associated with the plugin.
 * 
 * @author  Alex Lopez <your.email@example.com>
 * @date    2024-11-26
 */

defined('MOODLE_INTERNAL') || die();
/**
 * @purpose Defines the capabilities required for viewing reports in the plugin. 
 *          This includes the context level and the roles that are allowed to view the reports.
 * 
 * @param array $capabilities An associative array defining the capabilities for viewing reports.
 *                            The key is the capability name, and the value is an array defining 
 *                            the specific parameters such as context level, allowed roles, and the 
 *                            type of capability.
 * 
 * @returns array The capabilities array with all necessary configurations for access control.
 */
$capabilities = array(
    'mod/plugin_prueba:viewreports' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
        ),
    ),
);


