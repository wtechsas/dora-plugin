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
 * @package    quizaccess_plugin_prueba
 * @subpackage maccessruletemp
 * @copyright  leahfuentes.kd@gmail.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__ . '/../../../../../config.php');
require_once($CFG->libdir . '/filelib.php'); // Cargar la clase curl
defined('MOODLE_INTERNAL') || die();


use mod_quiz\event\attempt_submitted;
use mod_quiz\event\attempt_started;
use mod_quiz\event\attempt_reopened;
use mod_quiz\event\attempt_viewed;
use mod_quiz\event\course_module_viewed;

/**
 * Observer class for the quizaccess_plugin_prueba plugin.
 * This class listens for various events related to quizzes and executes actions
 * such as redirecting users to a Chrome extension or sending data to an API.
 * 
 * @package    quizaccess_plugin_prueba
 * @subpackage maccessruletemp
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quizaccess_plugin_prueba_observer
{
     /**
     * Handles the attempt_started event.
     * When a student starts a quiz attempt, this function checks if the quiz has
     * monitoring enabled and redirects to the Chrome extension if true.
     * 
     * @param \mod_quiz\event\attempt_started $event The event object for quiz attempt started.
     * @return void
     */
    public static function attempt_started(attempt_started $event)
    {
      
        global $DB, $CFG, $USER;
        $attempt = $event->get_record_snapshot('quiz_attempts', $event->objectid);
        $quiz = $DB->get_record('quiz', ['id' => $attempt->quiz], '*', MUST_EXIST);
        $attemptid = $attempt->id;
   
        $email = $USER->email;
        $course = $DB->get_record('course', ['id' => $quiz->course], '*', MUST_EXIST);
      
        $cm = get_coursemodule_from_instance('quiz', $quiz->id, $quiz->course, false, MUST_EXIST);
        $cmid = $cm->id;
        $attempt = $event->get_record_snapshot('quiz_attempts', $event->objectid);
        $quiz = $DB->get_record('quiz', ['id' => $attempt->quiz], '*', MUST_EXIST);
        $page = optional_param('page', 0, PARAM_INT);
        $monitored = $DB->get_field('quizaccess_monitoring_settings', 'plugin_prueba_enable', ['quizid' => $quiz->id]);
        if ($monitored == 1) {
            $quizurl = new moodle_url('/mod/quiz/attempt.php', [
                'cmid' => $cm->id,
                'attempt' => $attemptid,
                'page' => $page,
                ]);
            $redirecturl = new moodle_url('/mod/quiz/accessrule/plugin_prueba/redirect_extension.php', [
                'quizid' => $quiz->id,
                'courseid' => $course->id,
                'useremail' => $email,
                'quizurl' => $quizurl->out(false),
                'attempt' => $attemptid,
                'page' => $page,
            ]);

            // Redirigir a la extensión.
            redirect($redirecturl);
        } else {
            debugging("Quiz sin monitoring: $monitored", DEBUG_DEVELOPER);
        }
    }

     /**
     * Handles the attempt_viewed event.
     * When a student views a quiz attempt, this function checks if monitoring is enabled
     * and, if so, redirects to the Chrome extension.
     * 
     * @param \mod_quiz\event\attempt_viewed $event The event object for quiz attempt viewed.
     * @return void
     */
    public static function attempt_viewed(attempt_viewed $event)
    {
        global $DB, $CFG, $USER;
        $attempt = $event->get_record_snapshot('quiz_attempts', $event->objectid);
        $quiz = $DB->get_record('quiz', ['id' => $attempt->quiz], '*', MUST_EXIST);
        $attemptid = $attempt->id;
    
        $email = $USER->email;
        $course = $DB->get_record('course', ['id' => $quiz->course], '*', MUST_EXIST);
    
        $cm = get_coursemodule_from_instance('quiz', $quiz->id, $quiz->course, false, MUST_EXIST);
        $cmid = $cm->id;
        $attempt = $event->get_record_snapshot('quiz_attempts', $event->objectid);
        $quiz = $DB->get_record('quiz', ['id' => $attempt->quiz], '*', MUST_EXIST);
        $page = optional_param('page', null, PARAM_INT);
        $monitored = $DB->get_field('quizaccess_monitoring_settings', 'plugin_prueba_enable', ['quizid' => $quiz->id]);
        $redirected = optional_param('redirected', 0, PARAM_BOOL);
        if ($monitored == 1) {
            if (!$redirected) {
                $quizurl = new moodle_url('/mod/quiz/attempt.php', [
                    'cmid' => $cm->id,
                    'attempt' => $attemptid,
                    ]);
                    if ($page !== null) {
                        $quizurl->param('page', $page);
                    }
                    debugging("quizurl $quizurl", DEBUG_DEVELOPER);
                $redirecturl = new moodle_url('/mod/quiz/accessrule/plugin_prueba/redirect_extension.php', [
                    'quizid' => $quiz->id,
                    'courseid' => $course->id,
                    'useremail' => $email,
                    'quizurl' => $quizurl->out(false),
                    'attempt' => $attemptid,
                ]); 
                // Redirigir a la extensión.
                redirect($redirecturl);
            }
        } else {
            debugging("Quiz sin monitoring: $monitored", DEBUG_DEVELOPER);
        }
    }
    /**
     * Handles the course_module_viewed event.
     * When a user views the quiz summary page, this function checks if the quiz has
     * monitoring enabled and, if so, creates a report button that redirects to a specified URL.
     * 
     * @param course_module_viewed $event The event object for course module viewed.
     * @return void
     */
    public static function course_module_viewed(course_module_viewed $event) {
        global $DB, $PAGE, $USER, $CFG;
        // debugging("entreo a course", DEBUG_DEVELOPER);
        $id = required_param('id', PARAM_INT); // Obtener el id del módulo.

        // Establecer la URL de la página actual.
        $PAGE->set_url('/mod/quiz/view.php', array('id' => $id));
        $record = $DB->get_record('monitoring_integration', array('id' => 1));
        $moodlekey = $record->plugin_prueba_key;
        // Obtener los datos del usuario y del quiz.
        $userid = $event->userid;
        $cmid = $event->contextinstanceid;
        // Obtener la información del curso y quiz a partir del course module id (cmid).
        $cm = get_coursemodule_from_id('quiz', $cmid, 0, false, MUST_EXIST);
        $quiz = $DB->get_record('quiz', ['id' => $cm->instance], '*', MUST_EXIST);
        $course = $DB->get_record('course', ['id' => $quiz->course], '*', MUST_EXIST);
        $user = $DB->get_record('user', ['id' => $userid], '*', MUST_EXIST);
        // Obtener el valor de plugin_prueba_enable para verificar si el quiz tiene monitoreo activado 
        $monitored = $DB->get_field('quizaccess_monitoring_settings', 'plugin_prueba_enable', ['quizid' => $quiz->id]);
        // print_object($quiz);

        // Verificar si el quiz tiene el monitoreo activo 
        if ($monitored == 1) {
        
            $institutionurl = $CFG->wwwroot; // Obtiene la URL de la instancia de Moodle
            // Obtener el rol del usuario en el contexto del curso.
            $context = context_course::instance($course->id);
            $roles = get_user_roles($context, $user->id, true);
            $userrole = !empty($roles) ? role_get_name(reset($roles)) : 'Sin rol';

            if ($userrole == 'Estudiante') {
                $userrole = 'student';
            } else if ($userrole == 'Profesor') {
                $userrole = 'teacher';
            } else {
                $userrole = 'teacher';
            }
            // Prepara los datos a enviar a la API.
            $postdata = [
                "institutionUrl" => $institutionurl,
                "documentNumber" => $user->id,
                "userName" => fullname($user),
                "userMail" => $user->email,
                "userTypeName" => $userrole,
                "moodleQuizId" => $quiz->id,
            ];

            // Convierte los datos a formato JSON.
            $jsondata = json_encode($postdata);
            // print_object($jsondata);

            // Enviar los datos a la API usando cURL.
            $apiurl = 'http://3.137.61.121:3000/api/plugin/register-user';
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

            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($response === false) {
                $error = curl_error($ch);
                debugging("Error al enviar los datos del estudiante a la API: $error", DEBUG_DEVELOPER);
            } else if ($httpcode == 200) {
                debugging("Estudiante registrado correctamente en la API al visualizar el quiz.", DEBUG_DEVELOPER);
            } else {
                debugging("Error en la respuesta de la API. Código de estado: $httpcode", DEBUG_DEVELOPER);
            }

            curl_close($ch);
     
            // Obtener el contexto y el quiz.
            $quiz = $DB->get_record('quiz', ['id' => $PAGE->cm->instance]);
            $context = context_module::instance($PAGE->cm->id);
            $email = $USER->email;
            $course = $DB->get_record('course', ['id' => $quiz->course], '*', MUST_EXIST);
         
            // Obtener el módulo del curso (course module).
            $cm = get_coursemodule_from_instance('quiz', $quiz->id, $quiz->course, false, MUST_EXIST);
        
            // Crear la URL del quiz usando el ID del course module.
            $quizurl = new moodle_url('/mod/quiz/attempt.php', ['cmid' => $cm->id]);
          
            $quizid = $quiz->id;
            $courseid = $course->id;
            $content = "";

            // Verificar si el usuario tiene permisos para ver el boton de reportes 
            if (has_capability('mod/plugin_prueba:viewreports', $context)) {
                // debugging($monitored, DEBUG_DEVELOPER);
                $reporturl = new moodle_url('http://3.137.61.121:3001', [
                    'quizid' => $quiz->id,
                    'useremail' => $email,
                    'courseid' => $course->id,
                    'quizurl' => $quizurl->out(false),
                ]);
                $redirecturlrep = "http://3.137.61.121:3001/login?quizid={$quizid}&courseid={$courseid}&useremail={$email}&quizurl={$quizurl->out(false)}";
                // debugging($redirecturlrep, DEBUG_DEVELOPER);
                $content = '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    var regionMain = document.getElementById("region-main");
                    if (regionMain) {
                        // Crear el botón "Ver reporte" y agregarlo al inicio
                        var buttonReport = document.createElement("button");
                        buttonReport.className = "btn btn-primary";
                        buttonReport.name = "ptrzr_show_report";
                        buttonReport.textContent = "Ver reporte";
            
                        buttonReport.onclick = function() {
                            window.location.href = "' . $redirecturlrep . '";
                        };
            
                        regionMain.insertBefore(buttonReport, regionMain.firstChild);
                    }
                });
            </script>';
            
            echo $content;

            } else {
                debugging("No es profesor o administrador", DEBUG_DEVELOPER);
                
            }   
            $content2 = '<script>
            document.addEventListener("DOMContentLoaded", function() {
            var regionMain = document.getElementById("region-main");
            if (regionMain) {
                // Crear el botón "Check extension" y agregarlo al final
                var buttonExt = document.createElement("button");
                buttonExt.className = "btn btn-warning float-right";
                buttonExt.textContent = "Revisar Extensión";

                buttonExt.onclick = function() {
                    const monitoringDiv = document.getElementById("monitoring-ui");
                    
                    if (monitoringDiv) {
                        // Si la extensión está instalada, muestra un mensaje.
                        var existingMessage = document.getElementById("extension-installed-message");
                        if (!existingMessage) {
                            var message = document.createElement("p");
                            message.id = "extension-installed-message"; // Asignar un ID único al mensaje
                            message.textContent = "La extensión está instalada.";
                            message.style.color = "green";
                            message.align = "right";
                            regionMain.insertBefore(message, regionMain.firstChild);
                        }
                    } else {
                        // Si no está instalada, redirige al Chrome Web Store.
                        window.location.href = "https://chromewebstore.google.com/detail/dora/ejfpdckhidiokdgakolifmfepmnencpg";
                    }
                };

                regionMain.insertBefore(buttonExt, regionMain.firstChild);                }
            });
            </script>';

            echo $content2;
        } else {
            debugging("quiz sin monitoring: $monitored", DEBUG_DEVELOPER);
        } 
        self::check_integration_status();
    }

    public static function plugin_observer_user_loggedin(\core\event\user_loggedin $event) {
        // Ejecuta la verificación de integración cada vez que un usuario inicia sesión.
        self::check_integration_status();
    }
    /**
     * Checks the integration status with an external system.
     * This function is called when a user logs in to Moodle to ensure that
     * the plugin's integration status is up-to-date.
     * 
     * @return void
     */
    public static function check_integration_status() {
        global $DB, $CFG;
        echo "<script>console.log('login into');</script>";
        // Obtener el URL de Moodle y la clave de integración.
        $moodle_url = $CFG->wwwroot;
        $record = $DB->get_record('monitoring_integration', array('id' => 1));
        if (!$record || empty($record->plugin_prueba_key)) {
            return; // Si no hay clave registrada, no realizar la solicitud.
        }
    
        $plugin_key = $record->plugin_prueba_key;
        $api_url = 'http://3.137.61.121:3000/api/plugin/moodle-state'; // Cambia a la URL real de tu API
    
        // Realizar la solicitud GET
        $curl = new curl();
        $params = [
            'x-moodle-url' => $moodle_url,
            'x-moodle-key' => $plugin_key,
        ];

        // Configurar los encabezados personalizados
        $curl->setHeader('x-moodle-key', $plugin_key); // Cambia por la clave de tu plugin
        $curl->setHeader('x-moodle-url', $CFG->wwwroot); // URL de Moodle

        $response = $curl->get($api_url, $params);
        $result = json_decode($response);
    
        // Actualizar el estado de integración según la respuesta de la API
        if (isset($result->status) && $result->status === 200) {
            $record->plugin_prueba_status = 1;  // Activado
        } else {
            $record->plugin_prueba_status = 0;  // Desactivado
        }
    
        // Guardar el estado actualizado en la base de datos
        $DB->update_record('monitoring_integration', $record);
    }

}
