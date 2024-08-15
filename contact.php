<?php
// Load the .env file
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = parse_ini_file(__DIR__ . '/.env');
    $secretKey = $dotenv['RECAPTCHA_SECRET_KEY'];
} else {
    $secretKey = getenv('RECAPTCHA_SECRET_KEY');  // As a fallback
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $captcha = $_POST['g-recaptcha-response'];

    // Verify the CAPTCHA
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha");
    $responseKeys = json_decode($response, true);

    if (intval($responseKeys["success"]) !== 1) {
        echo "Por favor, complete o CAPTCHA corretamente.";
    } else {
        $to = "foxd3livery@gmail.com";  // Your email address
        $subject = "Mensagem de Contato do Parque Privado de Motas";
        $body = "Nome: $name\nEmail: $email\n\nMensagem:\n$message";

        $headers = "From: $email";

        if (mail($to, $subject, $body, $headers)) {
            echo "Mensagem enviada com sucesso!";
        } else {
            echo "Houve um erro ao enviar a mensagem. Por favor, tente novamente.";
        }
    }
} else {
    echo "Método de requisição inválido.";
}
?>
