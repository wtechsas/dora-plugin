<?php
/**
 * @File events.php
 * 
 * @description This file defines the event observers for the plugin. It listens for specific events 
 *              within Moodle (e.g., quiz attempt started, course module viewed, user logged in) and 
 *              associates them with corresponding callback functions to handle those events.
 *              The observers are registered to ensure that the plugin responds appropriately to these events.
 * 
 * @author  Alex Lopez <your.email@example.com>
 * @date    2024-11-26
 */
defined('MOODLE_INTERNAL') || die();
/**
 * @purpose Registers a set of event observers that will trigger specific functions 
 *          when certain events occur in Moodle. These observers are used to handle events 
 *          related to quiz attempts, course module views, and user logins for the plugin.
 * 
 * @returns array Returns an array of observers, each defined by an event name, callback function, 
 *                priority, and other optional settings (e.g., whether the observer is internal or external).
 */
$observers = [
    [
        'eventname' => '\mod_quiz\event\attempt_started',
        'callback' => 'quizaccess_plugin_prueba_observer::attempt_started',
        'priority' => 9999,
        'internal' => false,
    ],
    [
        'eventname'   => '\mod_quiz\event\course_module_viewed',
        'callback'    => 'quizaccess_plugin_prueba_observer::course_module_viewed',
        // 'includefile' => '/mod/quiz/accessrule/plugin_prueba/classes/observer.php',
        'priority' => 9999,
        'internal' => false,
    ],
    [
        'eventname'   => '\mod_quiz\event\attempt_viewed',
        'callback'    => 'quizaccess_plugin_prueba_observer::attempt_viewed',
        'priority' => 9999,
        'internal' => false,
    ],
    [
        'eventname'   => '\core\event\user_loggedin',
        'callback'    => 'quizaccess_plugin_prueba_observer::plugin_observer_user_loggedin',
        // 'includefile' => 'mod/quiz/accessrule/plugin_prueba/lib.php',
        'internal'    => false,
        'priority'    => 9999,
    ],
];
