<?php
/**
 * @file form_settings.php
 * 
 * @description This file defines the settings form for the plugin `quizaccess_plugin_prueba`.
 * It provides an interface for administrators to input the API key and secret key required 
 * for the integration with an external system.
 *
 * @author Alex Lopez <email>
 * @date 2024-11-26
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');
/**
 * Class for the settings form of the `quizaccess_plugin_prueba` plugin.
 * This class extends moodleform to create a form that collects the API key and secret key 
 * required by the plugin.
 *
 * @package quizaccess_plugin_prueba
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class ptrzr_settings_form extends moodleform {
    /**
     * @purpose Define the elements and validation rules of the settings form for the plugin.
     * This method adds fields for the API key and secret key, both of which are required 
     * for integration with an external system. It also adds action buttons for submitting 
     * or canceling the form.
     *
     * @param void
     * @returns void
     */
    protected function definition() {
        $mform = $this->_form;
        //key
        $mform->addElement('text', 'plugin_prueba_key', get_string('llave', 'quizaccess_plugin_prueba'));
        $mform->addRule('enginename', get_string('llave_desc', 'quizaccess_proctorizer'),
                'required', null, 'client');
        //secret
        $mform->addElement('text', 'plugin_prueba_secret', get_string('clave', 'quizaccess_plugin_prueba'));
        $mform->addRule('enginename', get_string('clave_desc', 'quizaccess_plugin_prueba'),
                'required', null, 'client');
        $this->add_action_buttons();
    }

}