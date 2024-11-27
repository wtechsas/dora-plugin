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
 * @File rule.php
 * 
 * @description This file defines the access rules for a quiz in Moodle, allowing
 *              custom restrictions and conditions based on plugin configuration.
 *              It integrates with Moodle's quiz module to apply specific rules 
 *              during the attempt phase.
 * 
 * @author  Alex Lopez <your.email@example.com>
 * @date    2024-11-26
 */


defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/lib.php');

use mod_quiz\local\access_rule_base;
use mod_quiz\quiz_settings;


/**
 * The access rule class implementation for the quizaccess_plugin_prueba plugin.
 * A rule that hijacks the standard attempt.php page, and replaces it with
 * different script which loads all the questions at once and then allows the
 * student to keep working, even if the network connection is lost. However,
 * if the network is working, responses are saved back to the server.
 *
 * @copyright 2014 The Open University
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quizaccess_plugin_prueba extends quiz_access_rule_base {
    /**
     * @purpose Creates an instance of the access rule for the quiz, applying the necessary 
     *          restrictions and validation based on the plugin settings.
     * 
     * @param quiz $quizobj The quiz object that contains information about the quiz being accessed.
     * @param int $timenow The current timestamp used for time-based restrictions.
     * @param bool $canignoretimelimits Flag indicating if the user can ignore time limits.
     * 
     * @returns quizaccess_plugin_prueba An instance of the class that defines the access rule for the quiz.
     */
    public static function make(quiz $quizobj, $timenow, $canignoretimelimits) {
 
        return new self($quizobj, $timenow);
    }
    /**
     * @purpose Adds configuration fields to the quiz settings form in the Moodle administration interface. 
     *          This allows administrators to adjust the plugin's access restrictions for each quiz.
     * 
     * @param mod_quiz_mod_form $quizform The form object for the quiz settings page.
     * @param MoodleQuickForm $mform The form object that fields are added to.
     * 
     * @returns void This function does not return any value.
     */
    public static function add_settings_form_fields(mod_quiz_mod_form $quizform, MoodleQuickForm $mform) {
        // Añadir una nueva configuración al formulario de configuración del cuestionario.
        global $DB;
        $quizinstance = $quizform->get_instance();
   
        $monitoring = new plugin_prueba_functions();
        $monitoring_status = $monitoring->plugin_prueba_getGlobalStatus();
        if ($monitoring_status) {
            $config = $DB->get_record('quizaccess_monitoring_settings', ['quizid' => $quizinstance]);

            $mform->addElement('header', 'plugin_prueba_setting', get_string('plugin_prueba_setting', 'quizaccess_plugin_prueba'));
            $mform->addElement('selectyesno', 'plugin_prueba_enable', get_string('plugin_prueba_setting', 'quizaccess_plugin_prueba'));
            $mform->addHelpButton('plugin_prueba_enable', 'plugin_prueba_setting', 'quizaccess_plugin_prueba');
            $mform->setDefault('plugin_prueba_enable', $config ? $config->plugin_prueba_enable : 0);
            $mform->setType('plugin_prueba_setting', PARAM_BOOL);

            $mform->addElement('checkbox', 'restriction_fullscreen', get_string('restriction_fullscreen', 'quizaccess_plugin_prueba'));
            $mform->addElement('checkbox', 'restriction_print', get_string('restriction_print', 'quizaccess_plugin_prueba'));
            $mform->addElement('checkbox', 'restriction_paste', get_string('restriction_paste', 'quizaccess_plugin_prueba'));
            $mform->addElement('checkbox', 'restriction_rightclick', get_string('restriction_rightclick', 'quizaccess_plugin_prueba'));
            $mform->addElement('checkbox', 'restriction_copy', get_string('restriction_copy', 'quizaccess_plugin_prueba'));
            $mform->addElement('checkbox', 'restriction_traslate', get_string('restriction_traslate', 'quizaccess_plugin_prueba'));
            $mform->addElement('checkbox', 'restriction_detectalt', get_string('restriction_detectalt', 'quizaccess_plugin_prueba'));
            $mform->addElement('checkbox', 'restriction_selecttext', get_string('restriction_selecttext', 'quizaccess_plugin_prueba'));
            $mform->addElement('checkbox', 'restriction_resize', get_string('restriction_resize', 'quizaccess_plugin_prueba'));
            $mform->addElement('checkbox', 'restriction_download', get_string('restriction_download', 'quizaccess_plugin_prueba'));
            $mform->addElement('checkbox', 'restriction_onlinerecognition', get_string('restriction_onlinerecognition', 'quizaccess_plugin_prueba'));
            $mform->addElement('checkbox', 'single_monitor', get_string('single_monitor', 'quizaccess_plugin_prueba'));
            $mform->addElement('checkbox', 'window_change', get_string('window_change', 'quizaccess_plugin_prueba'));
            
            $mform->setDefault('restriction_fullscreen', $config ? $config->restriction_fullscreen : 0);
            $mform->setDefault('restriction_print', $config ? $config->restriction_print : 0);
            $mform->setDefault('restriction_paste', $config ? $config->restriction_paste : 0);
            $mform->setDefault('restriction_rightclick', $config ? $config->restriction_rightclick : 0);
            $mform->setDefault('restriction_copy', $config ? $config->restriction_copy : 0);
            $mform->setDefault('restriction_traslate', $config ? $config->restriction_traslate : 0);
            $mform->setDefault('restriction_detectalt', $config ? $config->restriction_detectalt : 0);
            $mform->setDefault('restriction_selecttext', $config ? $config->restriction_selecttext : 0);
            $mform->setDefault('restriction_resize', $config ? $config->restriction_resize : 0);
            $mform->setDefault('restriction_download', $config ? $config->restriction_download : 0);
            $mform->setDefault('restriction_onlinerecognition', $config ? $config->restriction_onlinerecognition : 0);
            $mform->setDefault('single_monitor', $config ? $config->single_monitor : 0);
            $mform->setDefault('window_change', $config ? $config->window_change : 0);

            $mform->disabledIf('restriction_fullscreen', 'plugin_prueba_enable', 'eq', 0);
            $mform->disabledIf('restriction_print', 'plugin_prueba_enable', 'eq', 0);
            $mform->disabledIf('restriction_paste', 'plugin_prueba_enable', 'eq', 0);
            $mform->disabledIf('restriction_rightclick', 'plugin_prueba_enable', 'eq', 0);
            $mform->disabledIf('restriction_copy', 'plugin_prueba_enable', 'eq', 0);
            $mform->disabledIf('restriction_traslate', 'plugin_prueba_enable', 'eq', 0);
            $mform->disabledIf('restriction_detectalt', 'plugin_prueba_enable', 'eq', 0);
            $mform->disabledIf('restriction_selecttext', 'plugin_prueba_enable', 'eq', 0);
            $mform->disabledIf('restriction_resize', 'plugin_prueba_enable', 'eq', 0);
            $mform->disabledIf('restriction_download', 'plugin_prueba_enable', 'eq', 0);
            $mform->disabledIf('restriction_onlinerecognition', 'plugin_prueba_enable', 'eq', 0);
            $mform->disabledIf('single_monitor', 'plugin_prueba_enable', 'eq', 0);
            $mform->disabledIf('window_change', 'plugin_prueba_enable', 'eq', 0);
        }
        
    }
    /**
     * @purpose Saves the plugin's settings for a specific quiz to the database, allowing the 
     *          restrictions to be applied whenever the quiz is attempted.
     * 
     * @param object $quiz The quiz object containing the settings to be saved.
     * 
     * @returns void This function does not return any value.
     */
    public static function save_settings($quiz) {
        // debugging("Entro_Save", DEBUG_DEVELOPER); // Usar debugging() en lugar de error_log().
        global $DB;

        if (empty($quiz->plugin_prueba_enable)) {
            // El quiz no sera monitoreado
            // debugging("no sera monitoreado", DEBUG_DEVELOPER);
            $DB->delete_records('quizaccess_monitoring_settings', ['quizid' => $quiz->id]);
            // print_object($quiz);
        } else {

            $record = new stdClass();
            if (!$DB->record_exists('quizaccess_monitoring_settings', ['quizid' => $quiz->id])) {
                $record->quizid = $quiz->id;
            } else {
                $record = $DB->get_record('quizaccess_monitoring_settings', ['quizid' => $quiz->id]);
                self::send_to_api($quiz);
            }
            if (empty($quiz->restriction_fullscreen) || !$quiz->restriction_fullscreen) {
                $quiz->restriction_fullscreen = 0;
            }
            if (empty($quiz->restriction_print) || !$quiz->restriction_print) {
                $quiz->restriction_print = 0;
            }
            if (empty($quiz->restriction_paste) || !$quiz->restriction_paste) {
                $quiz->restriction_paste = 0;
            }
            if (empty($quiz->restriction_rightclick) || !$quiz->restriction_rightclick) {
                $quiz->restriction_rightclick = 0;
            }
            if (empty($quiz->restriction_copy) || !$quiz->restriction_copy) {
                $quiz->restriction_copy = 0;
            }
            if (empty($quiz->restriction_traslate) || !$quiz->restriction_traslate) {
                $quiz->restriction_traslate = 0;
            }
            if (empty($quiz->restriction_detectalt) || !$quiz->restriction_detectalt) {
                $quiz->restriction_detectalt = 0;
            }
            if (empty($quiz->restriction_selecttext) || !$quiz->restriction_selecttext) {
                $quiz->restriction_selecttext = 0;
            }
            if (empty($quiz->restriction_resize) || !$quiz->restriction_resize) {
                $quiz->restriction_resize = 0;
            }
            if (empty($quiz->restriction_download) || !$quiz->restriction_download) {
                $quiz->restriction_download = 0;
            }
            if (empty($quiz->restriction_onlinerecognition) || !$quiz->restriction_onlinerecognition) {
                $quiz->restriction_onlinerecognition = 0;
            }
            if (empty($quiz->single_monitor) || !$quiz->single_monitor) {
                $quiz->single_monitor = 0;
            }
            if (empty($quiz->window_change) || !$quiz->window_change) {
                $quiz->window_change = 0;
            }
        
            $record->quizid = $quiz->id;
            $record->plugin_prueba_enable = 1;
            $record->restriction_fullscreen = $quiz->restriction_fullscreen;
            $record->restriction_print = $quiz->restriction_print;
            $record->restriction_paste = $quiz->restriction_paste;
            $record->restriction_rightclick = $quiz->restriction_rightclick;
            $record->restriction_copy = $quiz->restriction_copy;
            $record->restriction_traslate = $quiz->restriction_traslate;
            $record->restriction_detectalt = $quiz->restriction_detectalt;
            $record->restriction_selecttext = $quiz->restriction_selecttext;
            $record->restriction_resize = $quiz->restriction_resize;
            $record->restriction_download = $quiz->restriction_download;
            $record->restriction_onlinerecognition = $quiz->restriction_onlinerecognition;
            $record->single_monitor = $quiz->single_monitor;
            $record->window_change = $quiz->window_change;
            $record->isquizstarted = 0;

            // $DB->insert_record('quizaccess_monitoring_settings', $record);
            if (!$DB->record_exists('quizaccess_monitoring_settings', ['quizid' => $quiz->id])) {
                $DB->insert_record('quizaccess_monitoring_settings', $record);
                self::send_to_api($quiz);
            } else {
                $DB->update_record('quizaccess_monitoring_settings', $record);
                self::send_to_api($quiz);
            }
        }
    }

    /**
     * @purpose Sends the quiz configuration data to an external API for validation or storage.
     * 
     * @param object $quiz The quiz object containing configuration data to be sent to the API.
     * 
     * @returns void This function does not return any value.
     */
    public static function send_to_api($quiz) {
        global $DB, $CFG;

        $record = $DB->get_record('monitoring_integration', array('id' => 1));
        $moodlekey = $record->plugin_prueba_key;
        // print_object($quiz);
        // debugging("Entro_send_to_api", DEBUG_DEVELOPER);

        $apiurl = 'http://3.137.61.121:3000/api/plugin/register-quiz'; // URL de la API.
        // Prepara los datos a enviar a la API.

         // Obtener el curso del quiz usando el courseid.
        $course = $DB->get_record('course', ['id' => $quiz->course], '*', MUST_EXIST);
        $coursename = $course->fullname;

        // $institutionurl = "https://wtech-moodle.net/";
        $institutionurl = $CFG->wwwroot; // Obtiene la URL de la instancia de Moodle

        // Obtener el ID del curso (este lo podemos obtener directamente del objeto quiz).
        $courseid = $quiz->course;

        // Generar la URL del quiz.
        $urlquiz = new moodle_url('/mod/quiz/attempt.php', ['cmid' => $quiz->coursemodule]);
        // Inicializa un arreglo vacío para las restricciones.
        $restrictions = [];

        // Solo añade los nombres de las restricciones seleccionadas.
        if (!empty($quiz->restriction_fullscreen)) {
            $restrictions[] = 'fullScreen';
        }
        if (!empty($quiz->restriction_print)) {
            $restrictions[] = 'print';
        }
        if (!empty($quiz->restriction_paste)) {
            $restrictions[] = 'paste';
        }
        if (!empty($quiz->restriction_rightclick)) {
            $restrictions[] = 'rightClick';
        }
        if (!empty($quiz->restriction_copy)) {
            $restrictions[] = 'copy';
        }
        if (!empty($quiz->restriction_traslate)) {
            $restrictions[] = 'translate';
        }
        if (!empty($quiz->restriction_detectalt)) {
            $restrictions[] = 'detectAlt';
        }
        if (!empty($quiz->restriction_selecttext)) {
            $restrictions[] = 'selectText';
        }
        if (!empty($quiz->restriction_resize)) {
            $restrictions[] = 'resize';
        }
        if (!empty($quiz->restriction_download)) {
            $restrictions[] = 'download';
        }
        if (!empty($quiz->restriction_onlinerecognition)) {
            $restrictions[] = 'onlineRecognition';
        }
        if (!empty($quiz->single_monitor)) {
            $restrictions[] = 'oneMonitor';
        }
        if (!empty($quiz->window_change)) {
            $restrictions[] = 'windowChange';
        }

        // Solo envía restricciones si hay alguna seleccionada.
       
        $postdata = [
            "institutionUrl" => $institutionurl,
            "moodleCourseName" => $coursename,
            "moodleCourseId" => $courseid,
            "nameQuiz" => $quiz->name,
            "moodleQuizId" => (string) $quiz->id,
            "urlQuiz" => $urlquiz->out(false),
            "restrictions" => $restrictions, // Enviar solo los nombres de las restricciones seleccionadas.
        ];
        // Convierte los datos a formato JSON.
        $jsondata = json_encode($postdata);
        // print_object($jsondata);
        // Usar curl para hacer la solicitud POST a la API.
        $ch = curl_init($apiurl);

        // Configurar curl.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsondata),
            'x-moodle-key: ' . $moodlekey,
            'x-moodle-url: ' . $institutionurl,
        ]);

        // Ejecutar la solicitud.
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Obtener el código de respuesta HTTP.
        if ($response === false) {
            $error = curl_error($ch);
            debugging("Error al enviar los datos a la API: $error", DEBUG_DEVELOPER); 
        } else {
            debugging("Respuesta de la API: $response", DEBUG_DEVELOPER); // Mostrar la respuesta para propósitos de depuración.
            // Verificar si la respuesta es 200 OK.
            if ($httpcode == 200) {
                global $USER, $DB;

                if (!empty($USER->id)) {
                    $quizcreatorid = $USER->id;  // ID del usuario creador
                    $quizcreator = $DB->get_record('user', ['id' => $quizcreatorid], '*', MUST_EXIST);

                    // Llama a la función para registrar al usuario creador y enviar a la API.
                    self::send_to_api_user($quizcreator, $quiz);
                } else {
                    debugging("No se pudo obtener el usuario actual que creó el quiz.", DEBUG_DEVELOPER);
                }
            } else {
                debugging("La API respondió con un estado diferente a 200: $httpcode", DEBUG_DEVELOPER);
            }
        }

        // Cerrar la sesión curl.
        curl_close($ch);
    }
    /**
     * Enviar la informacion del usuario a una API externa.
     *
     * @param object $user Datos del usuario.
     * @param object $quiz Datos del quiz.
     */
    public static function send_to_api_user($user, $quiz) {
        global $DB, $CFG;

        $record = $DB->get_record('monitoring_integration', array('id' => 1));
        $moodlekey = $record->plugin_prueba_key;

        $institutionurl = $CFG->wwwroot; // Obtiene la URL de la instancia de Moodle
        // Obtener el curso al que pertenece el quiz.
        $course = $DB->get_record('course', ['id' => $quiz->course], '*', MUST_EXIST);

        // Obtener el contexto del curso.
        $context = context_course::instance($course->id);

        // Obtener el rol del usuario.
        $roles = get_user_roles($context, $user->id, true);
        $userrole = !empty($roles) ? role_get_name(reset($roles)) : 'Sin rol';

        // Normalizar el rol.
        if ($userrole == 'Estudiante') {
            $userrole = 'student';
        } else if ($userrole == 'Profesor') {
            $userrole = 'teacher';
        } else {
            $userrole = 'teacher';
        }

        // Preparar los datos a enviar.
        $postdata = [
            "institutionUrl" => $institutionurl,  // URL de la institución.
            "documentNumber" => $user->id,  // ID del usuario (documento).
            "userName" => fullname($user),  // Nombre completo del usuario.
            "userMail" => $user->email,  // Correo electrónico del usuario.
            "userTypeName" => $userrole,  // Rol del usuario.
            "moodleQuizId" => (string) $quiz->id,  // ID del quiz.
        ];

        // Convertir a JSON.
        $jsondata = json_encode($postdata);
        print_object($jsondata);
        // URL de la API para registrar al usuario.
        $apiurl = 'http://3.137.61.121:3000/api/plugin/register-user';

        // Usar curl para hacer la solicitud POST.
        $ch = curl_init($apiurl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsondata),
            'x-moodle-key: ' . $moodlekey,
            'x-moodle-url: ' . $institutionurl,
        ]);

        // Ejecutar la solicitud.
        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);
            debugging("Error al registrar el usuario: $error", DEBUG_DEVELOPER);
        } else {
            debugging("Usuario registrado en la API: $response", DEBUG_DEVELOPER);
        }

        // Cerrar curl.
        curl_close($ch);
    }
}

