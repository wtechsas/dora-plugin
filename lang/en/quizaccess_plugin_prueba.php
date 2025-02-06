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
 * This file contains language strings for the plugin. These strings are used in 
 * the user interface (UI) to display readable text in a specific language, in this case, English.
 *
 * @package     quizaccess_plugin_prueba
 * @category    string
 * @copyright   2024 Your Name <you@example.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'DORA';
$string['monitoring'] = 'Configuración de Monitoreo';
$string['monitoring_desc'] = 'Seleccione la restricción de monitoreo para los cuestionarios';
$string['monitoring_none'] = 'Ninguna';
$string['monitoring_time'] = 'Restricción por tiempo';
$string['monitoring_attempts'] = 'Restricción por número de intentos';
$string['monitoring_ip'] = 'Restricción por dirección IP';

$string['view_report'] = 'Ver reportes';


$string['configname'] = 'Nombre de la Configuración';
$string['configname_desc'] = 'Descripción de la configuración.';

$string['plugin_prueba_enabled'] = 'Enable Monitoring';
$string['monitoring_restrictions'] = 'Select Monitoring Restrictions';
$string['restriction1'] = 'Restriction 1';
$string['restriction2'] = 'Restriction 2';

$string['plugin_prueba_setting'] = 'Mi configuración personalizada';
$string['plugin_prueba_setting_desc'] = 'Descripción de la configuración personalizada';
$string['plugin_prueba_setting_help'] = 'This setting allows you to enable or disable the 
Plugin Prueba for quizzes. When enabled, additional restrictions will be applied';

$string['plugin_prueba_setting'] = 'Configuración DORA';
$string['plugin_prueba_enable'] = 'Enable Plugin Prueba';
$string['plugin_prueba_restrictions'] = 'Select Restrictions';
$string['restriction_timelimit'] = 'Time limit restriction';
$string['restriction_network'] = 'Network-based restriction';
$string['restriction_attempts'] = 'Attempt-based restriction';

// Configuración antigua de DORA
// $string['restriction_fullscreen'] = 'Pantalla completa';
// $string['restriction_paste'] = 'Pegar';
// $string['restriction_print'] = 'Imprimir';
// $string['restriction_copy'] = 'Copiar';
// $string['restriction_rightclick'] = 'Click derecho';
// $string['restriction_traslate'] = 'Traductor';
// $string['restriction_detectalt'] = 'Detectar tecla ALT';
// $string['restriction_selecttext'] = 'Seleccionar texto desabilitado';
// $string['restriction_resize'] = 'Cambio de tamano de pantalla';
// $string['restriction_download'] = 'Descargar';
// $string['restriction_onlinerecognition'] = 'Reconocimiento facial';
// $string['single_monitor'] = 'Solo un monitor';
// $string['window_change'] = 'Cambio de ventana';
// $string['deterrent_mode'] = 'Modo disuasorio';
// $string['focus_exam'] = 'Focus en el examen';

//Configuración nueva de DORA
$string['plugin_prueba_setting_help'] = 'Esta configuración permite habilitar o deshabilitar las restricciones de DORA.';
$string['restriction_fullscreen'] = 'Forzar pantalla completa al iniciar el examen.';
$string['restriction_fullscreen_help'] = 'Obliga a los usuarios a presentar el examen en modo de pantalla completa.';
$string['restriction_print'] = 'Bloquear impresión.';
$string['restriction_print_help'] = 'Evita que los usuarios impriman el contenido del examen.';
$string['restriction_paste'] = 'Bloquear pegar.';
$string['restriction_paste_help'] = 'Impide que los usuarios puedan pegar texto dentro del examen.';
$string['restriction_rightclick'] = 'Bloquear clic derecho.';
$string['restriction_rightclick_help'] = 'Deshabilita el clic derecho para prevenir accesos contextuales.';
$string['restriction_copy'] = 'Bloquear copiar.';
$string['restriction_copy_help'] = 'Evita que los usuarios copien contenido del examen.';
$string['restriction_traslate'] = 'Bloquear traducciones.';
$string['restriction_traslate_help'] = 'Deshabilita servicios de traducción automática dentro del examen.';
$string['restriction_detectalt'] = 'Detectar tecla ALT.';
$string['restriction_detectalt_help'] = 'Monitorea el uso de la tecla ALT para prevenir accesos no autorizados.';
$string['restriction_selecttext'] = 'Deshabilitar selección de texto.';
$string['restriction_selecttext_help'] = 'Evita que los usuarios seleccionen texto dentro del examen.';
$string['restriction_resize'] = 'Bloquear redimensionamiento.';
$string['restriction_resize_help'] = 'Impide cambiar el tamaño de la ventana del navegador.';
$string['restriction_download'] = 'Bloquear descargas.';
$string['restriction_download_help'] = 'Evita que los usuarios descarguen contenido del examen.';
$string['restriction_onlinerecognition'] = 'Habilitar reconocimiento facial.';
$string['restriction_onlinerecognition_help'] = 'Activa la detección facial para verificar la identidad del usuario durante el examen.';
$string['single_monitor'] = 'Permitir solo un monitor.';
$string['single_monitor_help'] = 'Limita el uso a un solo monitor conectado.';
$string['window_change'] = 'Bloquear cambio de ventana.';
$string['window_change_help'] = 'Detecta y previene cambios de ventana durante el examen.';
$string['deterrent_mode'] = 'Activar modo disuasorio.';
$string['deterrent_mode_help'] = 'Activa el complemento de DORA para el estudiante pero no hace el monitoreo del examen por lo tanto no consume licencias.';
$string['focus_exam'] = 'Requiere atención constante.';
$string['focus_exam_help'] = 'Monitorea que los usuarios mantengan el enfoque constante en la ventana del examen.';

$string['llave'] = 'Llave';
$string['llave_desc'] = 'Introduce la llave.';
$string['clave'] = 'Clave';
$string['clave_desc'] = 'Introduce la clave.';

$string['select_all_restrictions'] = 'Seleccionar todas las restricciones';
