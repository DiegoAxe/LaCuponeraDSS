<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require '../modelo/PHPMailer/Exception.php';
    require '../modelo/PHPMailer/PHPMailer.php';
    require '../modelo/PHPMailer/SMTP.php';
    
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    
    //Server settings
    $mail->SMTPDebug = 0;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'cuponera4dminxmh1@gmail.com';                     //SMTP username
    $mail->Password   = 'cedjjgdqtsenxeke';                               //SMTP password
    $mail->SMTPSecure = 'ssl';            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('cuponera4dminxmh1@gmail.com', 'La Cuponera');
    $mail->addAddress($correo);     //Add a recipient
    //$mail->addReplyTo('cuponera4dminxmh1@gmail.com', 'Information');
    //$mail->addCC('cuponera4dminxmh1@gmail.com');
    //$mail->addBCC('cuponera4dminxmh1@gmail.com');

    //Attachments
    /*
    $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
    */
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $titulo;
    $mail->Body    = $mensaje;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
?>