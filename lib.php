<?php
/**
 * @File        lib.php
 * 
 * @description Defines utility functions and classes for the Plugin Prueba. 
 *              Provides functionality for determining the global status of the plugin.
 *
 * @author      Alex Lopez <email>
 * @date        2024-11-26
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');
require_once($CFG->dirroot . '/mod/quiz/locallib.php');

class plugin_prueba_functions {
    /**
     * @purpose Checks whether the Plugin Prueba is globally enabled or disabled.
     *          The status is retrieved from a global object or the database.
     * 
     * @param    void
     * 
     * @returns  int The global status of the plugin (1 for enabled, 0 for disabled).
     */
    public function plugin_prueba_getGlobalStatus()
    {
        global $DB;
        if (!isset($GLOBALS['MONI'])) {
            // Si no está definido, inicializarlo como un objeto stdClass
            $GLOBALS['MONI'] = new stdClass();
        }
        if (!property_exists($GLOBALS['MONI'], 'plugin_prueba_status')) {
            // Verificar si la propiedad ptrzr_global no está definida
            $record = $DB->get_record('monitoring_integration', array('id' => 1));
            if ($record) {
                $GLOBALS['MONI']->plugin_prueba_status = $record->plugin_prueba_status;
            } else {
                // Manejar el caso en que no se encuentra el registro en la base de datos
                $GLOBALS['MONI']->plugin_prueba_status = 0;
            }
        }
        return $GLOBALS['MONI']->plugin_prueba_status;
    }
}



