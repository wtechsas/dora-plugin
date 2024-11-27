<?php
// Archivo de configuracion
require_once(__DIR__ . '/../../../../config.php');

// vferificar si ha iniciado sesion 
require_login();

// Recibir los parámetros pasados por URL.
$quizid = required_param('quizid', PARAM_INT);
$useremail = required_param('useremail', PARAM_EMAIL);
$quizurl = required_param('quizurl', PARAM_URL);
$courseid = required_param('courseid', PARAM_INT);
$attempt = required_param('attempt', PARAM_INT);
$page = optional_param('page', null, PARAM_INT); // Recibe el parámetro 'page' de manera opcional.

$quizurlwithredirect = $quizurl . (strpos($quizurl, '?') === false ? '?' : '&') . 'redirected=1';
debugging("quizurlredirected: $quizurlwithredirect", DEBUG_DEVELOPER);
$redirecturlext = "chrome-extension://bgocacjodkikkknopnokndnbeeibnehp/index.html#/diagnostic?quizid={$quizid}&courseid={$courseid}&useremail={$useremail}&quizurl={$quizurl}&attempt={$attempt}";

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Redireccionando...</title>
    <script>
        window.onload = function() {
            // Espera para verificar la existencia del div "monitoring-active"
            const checkDiv = setInterval(() => {
                const monitoringDiv = document.getElementById("myDiv");
                const isDiv = document.getElementById("monitoring-ui");
                const hasNestedDiv = Array.from(isDiv.children).some(child => child.tagName === 'DIV');

                if (hasNestedDiv) {
                    console.log("Div de monitoreo encontrado. Continuando en el quiz.");
                    
                    window.location.href = '<?php echo $quizurlwithredirect; ?>';
                    // clearInterval(checkDiv);
                } else {
                    console.log("Div de monitoreo no encontrado. Redirigiendo a la extensión.");
                    if (isDiv) {

                        console.log("Div encontrado: extension instalada", isDiv);
                    
                            window.location.href = '<?php echo $redirecturlext; ?>';                      
                        // clearInterval(checkDiv);
                    } else {
                        console.log("Div aún no encontrado extension no instalada");
                        window.location.href = "https://chrome.google.com/webstore/detail/blellocfflgdjpijjdpccbonhicdhmma";
                        // clearInterval(checkDiv);
                    }
                }
            }, 500); // Verifica cada medio segundo
        }
    </script>

</head>
<body>
    <p>Redireccionando al contenido...</p>
    <!-- Puedes agregar un enlace de respaldo en caso de que la redirección automática no funcione -->
    <noscript>
        <p>La redirección automática falló. <a href="https://chromewebstore.google.com/detail/traductor-de-google/aapbdbdomjkkjkaonfhkkikfgjllcleb?hl=es">Haz clic aquí para continuar.</a></p>
    </noscript>
</body>
</html>
