<?php
/**
 * @file redirect_extension.php
 * 
 * @description Este archivo maneja la redirección del usuario a la extensión de Chrome o al quiz de Moodle,
 * dependiendo de la existencia de un div de monitoreo activo. Si el div no está presente, se redirige al usuario
 * a la extensión de Chrome para realizar el monitoreo del quiz. En caso de que la extensión no esté instalada,
 * se redirige al usuario al Chrome Web Store para instalarla.
 * 
 * @author  Alex Lopez <email>
 * @date    2024-11-26
 */
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
$redirecturlext = "chrome-extension://ejfpdckhidiokdgakolifmfepmnencpg/index.html#/diagnostic?quizid={$quizid}&courseid={$courseid}&useremail={$useremail}&quizurl={$quizurl}&attempt={$attempt}";

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Redireccionando...</title>
    <script>
        window.onload = function() {
            /**
             * @purpose Maneja la lógica de redirección en función de la presencia del div de monitoreo en la página.
             * Si el div de monitoreo está presente, redirige al quiz, de lo contrario redirige a la extensión de Chrome
             * o al Chrome Web Store si la extensión no está instalada.
             * 
             * @param none
             * @returns none
             */
            const checkDiv = setInterval(() => {
            const isDiv = document.getElementById("monitoring-ui");

            if (isDiv) {
                // Si el div existe, verificamos si tiene hijos.
                const hasNestedDiv = Array.from(isDiv.children).some(child => child.tagName === 'DIV');
                if (hasNestedDiv) {
                    console.log("Div de monitoreo encontrado. Continuando en el quiz.");
                    window.location.href = '<?php echo $quizurlwithredirect; ?>';
                    clearInterval(checkDiv); // Detenemos el intervalo
                } else {
                    console.log("Div encontrado, pero sin hijos relevantes. Redirigiendo a la extensión.");
                    window.location.href = '<?php echo $redirecturlext; ?>';
                    clearInterval(checkDiv); // Detenemos el intervalo
                }
            } else {
                console.log("Div de monitoreo no encontrado. Redirigiendo al Chrome Web Store.");
                window.location.href = "https://chromewebstore.google.com/detail/dora/ejfpdckhidiokdgakolifmfepmnencpg";
                clearInterval(checkDiv); // Detenemos el intervalo
            }
        }, 500); // Verifica cada medio segundo
        }
    </script>

</head>
<body>
    <p>Redireccionando al contenido...</p>
    <!--enlace de respaldo en caso de que la redirección automática no funcione -->
    <noscript>
        <p>La redirección automática falló. <a href="https://chromewebstore.google.com/detail/dora/ejfpdckhidiokdgakolifmfepmnencpg">Haz clic aquí para continuar.</a></p>
    </noscript>
</body>
</html>
