<?php

require_once(__DIR__ . '/../../../../config.php');

//TODO modificar a rutas absolutas y no relativas

require_once('./form_settings.php');

// require_capability('moodle/site:config', context_system::instance());

$context = context_system::instance();
// Requiere el permiso de administrador para acceder a esta página.
require_login();


// Define el título de la página personalizada.
$pagetitle = get_string('monitoring', 'quizaccess_plugin_prueba');
$activateErrorHtml = '';
$mform = new ptrzr_settings_form('home.php');
if ($mform->is_cancelled()) {
    echo "";
} else if ($data = $mform->get_data()) {
    global $DB, $CFG;
    $moodleurl = $CFG->wwwroot; // Obtiene la URL de la instancia de Moodle
    $monitoringcredentials = new stdClass();
    $monitoringcredentials->plugin_prueba_key = $data->plugin_prueba_key;
    $monitoringcredentials->plugin_prueba_secret = $data->plugin_prueba_secret;

    if ($DB->record_exists('monitoring_integration', array('id' => 1))) {
        $responsedata = validate_monitoring_credentials($moodleurl, $monitoringcredentials->plugin_prueba_key, $monitoringcredentials->plugin_prueba_secret);
        $record = $DB->get_record('monitoring_integration', array('id' => 1));
        echo "<script>console.log('responsedata: " . $responsedata->status . "');</script>";
        if ($responsedata->status == 200) {
            $record->plugin_prueba_key = $monitoringcredentials->plugin_prueba_key;
            $record->plugin_prueba_secret =  "";
            // $recordlugin_pruebarzr_license = $json_result["license_mode"];
            $record->plugin_prueba_status = 1;
            $record->plugin_prueba_secret_keyapi = ""; 
        } else {
            $record->plugin_prueba_key = "NO EXISTE";
            $record->plugin_prueba_license = "NA";
            $record->plugin_prueba_status = 0;
        }
        #guardar estado de variables globales
        if (!isset($GLOBALS['MONI'])) {
            // Si no está definido, inicializarlo como un array vacío
            $GLOBALS['MONI'] = new stdClass();
        }
        $GLOBALS['MONI']->plugin_prueba_status = $record->plugin_prueba_status;
        $DB->update_record('monitoring_integration', $record);
    } else {
        $responsedata = validate_monitoring_credentials($moodleurl,  $monitoringcredentials->plugin_prueba_key, $monitoringcredentials->plugin_prueba_secret);
        $record = $DB->get_record('monitoring_integration', array('id' => 1));
       
        if ($responsedata->status == 200) {
            $record = new stdClass();
            $record->id = 1;
            $record->plugin_prueba_key = $monitoringcredentials->plugin_prueba_key;
            $record->plugin_prueba_secret = "";
            $record->plugin_prueba_status = 1;
            $record->plugin_prueba_secret_keyapi = "";

            $DB->insert_record('monitoring_integration', $record);
        } else {
            $record = new stdClass();
            $record->id = 1;
            $record->plugin_prueba_key = "NO EXISTE";
            $record->plugin_prueba_license = "NA";
            $record->plugin_prueba_status = 0;
            $record->plugin_prueba_secret_keyapi = "";
          
            $DB->insert_record('monitoring_integration', $record);
        }
    }

}

$record2 = $DB->get_record('monitoring_integration', array('id' => 1));
echo "<script>console.log('record2: " . json_encode($record2) . "');</script>";


if (isset($_POST['cambiar_estado'])) {
    
    if ($record2->plugin_prueba_status) {
        $content = '<p>Monitoring se encuentra activo.</p>';

        // Imprime la página personalizada.
        echo $OUTPUT->header();
        echo $OUTPUT->heading($pagetitle);
        echo $content;
        $mform->display();
    
        echo $OUTPUT->footer();

    } else {
        $content = '<p>Monitoring se encuentra inactivo.</p>';

        // Imprime la página personalizada.
        echo $OUTPUT->header();
        echo $OUTPUT->heading($pagetitle);
        echo $content;
        $mform->display();
    
        echo $OUTPUT->footer();
    }
} else {
    $content = '<p>En este pagina puedes administrar tu integración.</p>';
    $textoBoton = "Registrar Credenciales";
    $status_int = "Inactiva";
    $tipo_licencia = "Inactiva";
    $clave_pro = "Sin clave";

    $badged_status = "danger";
    $badged_tipo = "danger";
    $badged_clave = "danger";

    if ($record2->plugin_prueba_status == 1) {
        $textoBoton = "Actualizar Credenciales";
        $tipo_licencia = "Activa";
        $status_int = "Activa";
        $clave_pro = "Activa";

        $badged_status = "success";
        $badged_tipo = "success";
        $badged_clave = "success";
    }

    echo $OUTPUT->header();
    echo $OUTPUT->heading($pagetitle);
    echo $content;

    echo '<div class="col-md-5">
            <ul class="list-group">

            <li class="list-group-item d-flex justify-content-between">
                <span>Estado de la integración</span>
                <span class="badge badge-'.$badged_status.' p-1">'.$status_int.'</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span>Clave del producto</span>
                <span class="badge badge-'.$badged_clave.' p-1">'.$clave_pro.'</span>
            </li>
            </ul>
            <form method="post" class="d-flex justify-content-between">
                <div>
                </div>
                <button class="btn btn-primary mt-3" type="submit" name="cambiar_estado">'.$textoBoton.'</button>
            </form>
        </div>
        ';


    echo $OUTPUT->footer();

}

function validate_monitoring_credentials($moodleUrl, $key, $secret) {
    // Configuración de la URL de la API y los datos a enviar
    $apiurl = 'http://3.137.61.121:3000/api/plugin/register-moodle-hash-institution';
    $data = ['moodleUrl' => $moodleUrl, 'moodleKey' => $key, 'moodlePassword' => $secret];
    echo "<script>console.log('data: " . json_encode($data) . "');</script>";

    // Convertir a JSON.
    $jsondata = json_encode($data);

    // Usar curl para hacer la solicitud POST.
    $ch = curl_init($apiurl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsondata),
    ]);
    
    // Ejecutar la solicitud.
    $response = curl_exec($ch);
    if ($response === false) {
        $error = curl_error($ch);
        echo "<script>console.log('error: " . $error . "');</script>";
    } else {
        echo "<script>console.log('response: " . $response . "');</script>";
    }

    // Cerrar curl.
    curl_close($ch);
    return json_decode($response);
 
}

function save_monitoring_credentials($key, $secret) {
    global $DB;

    // Datos para insertar en la tabla de validación
    $record = new stdClass();
    $record->plugin_prueba_key = $key;
    $record->plugin_prueba_secret = $secret;
    $record->plugin_prueba_status = 1;  // Activado
    $record->plugin_prueba_secret_keyapi = generate_api_key($key, $secret);

    //guardar estado de variables globales
    if (!isset($GLOBALS['PTRZR'])) {
        // Si no está definido, inicializarlo como un array vacío
        $GLOBALS['MONI'] = new stdClass();
    }
    $GLOBALS['MONI']->plugin_prueba_status = $record->plugin_prueba_status;

    // Inserta o actualiza la entrada en la tabla
    $DB->insert_record('monitoring_integration', $record);
}
function get_monitoring_status() {
    global $DB;
    
    // Busca el estado de activación en la tabla
    $record = $DB->get_record('monitoring_integration', []);
    return $record ? $record->plugin_prueba_status : 0;
}
function generate_api_key($key, $secret) {
    return hash('sha256', $key . $secret . time());
}