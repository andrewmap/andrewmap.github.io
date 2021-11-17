<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'phpmailer/Exception.php';
    require 'phpmailer/PHPMailer.php';
    require 'phpmailer/SMTP.php';

    $mail = new PHPMailer(true);
    try
    {
        $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'star@stkcapital.com.br';
        $mail->Password = 'senha';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom("star@stkcapital.com.br", "STAR STK");
        $mail->addAddress("victor.hugo.vergueiro@outlook.com", "Victor Hugo");
        $mail->addAddress("graziela.segard@stkcapital.com.br", "Graziela");
        $mail->addReplyTo("contato@stkcapital.com.br", "STAR STK");
        
        $mail->isHTML(true);
        $mail->Subject = "Teste de email SMTP";
        $mail->Body = "<b>Teste</b>";
        
        $mail->send();
        echo("Message sended");
    }
    catch (Exception $e)
    {
        echo("Error: ".$mail->ErrorInfo);
    }
?>