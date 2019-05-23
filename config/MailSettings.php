<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    function SendMail($email_to, $email_to_name = null, $subject, $body) {
        
        if (file_exists($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php")) {
            $path = $_SERVER['DOCUMENT_ROOT'];
            require_once $_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php";
        }else{
            $path = substr(__DIR__, 0, -7);
            require_once $path."/vendor/autoload.php";
        }

        $dotenv = new Dotenv\Dotenv($path);
        $dotenv->load();

        set_time_limit(0);

        $mail = new PHPMailer;

        $log_file = sprintf(__DIR__ . '/mail-log.txt');
        $mail->SMTPDebug   = 4;

        // log rror if there are any
        $mail->Debugoutput = function($message, $level) use($log_file) {
                    error_log(sprintf("(%s) %s\r\n", $level, $message), 3, $log_file);
                    return ;
        };

        $mail->isSMTP();

        // smtp hostname
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->SMTPKeepAlive = true;
        
        // smtp auth username and password
        $mail->Username = $_ENV['MAIL_USER'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];

        $mail->smtpConnect(
            array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                    "allow_self_signed" => true
                )
            )
        );
        $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];
        $mail->Port = $_ENV['MAIL_PORT'];

        $mail->From = $_ENV['MAIL_FROM'];
        $mail->FromName = $_ENV['MAIL_FROM_NAME'];

        $mail->addAddress($email_to, $email_to_name);

        $mail->isHTML(true);

        $mail->Subject = $subject;
        $mail->Body = $body;

       if(!$mail->send())
        {
            return $mail->ErrorInfo;
        }
        else
        {
            return true;
        }

    }

?>