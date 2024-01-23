<?php
header("Content-Type: application/json; charset=UTF-8");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './vendor/autoload.php';
require './wp-config.php';
$config['dbname'] = DB_NAME;
$config['host'] = DB_HOST;
$config['dbuser'] = DB_USER;
$config['dbpass'] = DB_PASSWORD;

try {
    $options = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"];
    $db = new PDO("mysql:dbname=" . $config['dbname'] . ";host=" . $config['host'], $config['dbuser'], $config['dbpass'], $options);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Falhou " . $e->getMessage();
    exit;
}

$redirect = ($db->query("SELECT * FROM `wp_options` WHERE option_name = 'siteurl'"))
    ->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: '.$redirect['option_value']);
    exit;
}

$inputJSON = file_get_contents('php://input');
$post = json_decode($inputJSON, true);

if ($post === null && json_last_error() !== JSON_ERROR_NONE) {
    // Trate o erro de decodificação JSON, se necessário
    http_response_code(400); // Código de resposta 400 para indicar um pedido inválido
    echo json_encode(['error' => 'Erro na decodificação JSON']);
    exit;
}

ob_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato</title>
</head>

<body>
    <h1>Contato HUS</h1>
    <p>Cliente: <?=$post['name'] ?? ""; ?></p>
    <p>E-mail: <?=$post['email'] ?? ""; ?></p>
    <p>Telefone: <?=$post['phone'] ?? ""; ?></p>
    <p><b>Mensagem:</b> <?=$post['message'] ?? ""; ?></p>
</body>

</html>


<?php
$html = ob_get_contents();
ob_end_clean();

$mail = new PHPMailer(true);

$mailserver_url = ($db->query("SELECT * FROM `wp_options` WHERE option_name = 'mailserver_url'"))
    ->fetch(PDO::FETCH_ASSOC);
$mailserver_login = ($db->query("SELECT * FROM `wp_options` WHERE option_name = 'mailserver_login'"))
    ->fetch(PDO::FETCH_ASSOC);
$mailserver_pass = ($db->query("SELECT * FROM `wp_options` WHERE option_name = 'mailserver_pass'"))
    ->fetch(PDO::FETCH_ASSOC);
$mailserver_port = ($db->query("SELECT * FROM `wp_options` WHERE option_name = 'mailserver_port'"))
    ->fetch(PDO::FETCH_ASSOC);

try {
    $mail->isSMTP();
    $mail->Host         = $mailserver_url['option_value'];
    $mail->SMTPAuth     = true;
    $mail->Username     = $mailserver_login['option_value'];
    $mail->Password     = $mailserver_pass['option_value'];
    $mail->SMTPSecure   = 'ssl';
    $mail->Port         = $mailserver_port['option_value'];
    $mail->CharSet = 'UTF-8';

    $mail->setFrom($mailserver_login['option_value'], 'Contato HUS');
    $mail->addAddress($mailserver_login['option_value'], 'Contato HUS');
    $mail->isHTML(true);
    $mail->Subject = 'Contato HUS';
    $mail->Body    = $html;

    $mail->send();
    echo 'Mensagem enviada com sucesso!';
} catch (Exception $e) {
    echo "Erro ao enviar mensagem: {$mail->ErrorInfo}";
}
