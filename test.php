<?php 
include 'lib/initialize.php';

$mail = new Mail();
$mail->FromName = 'Jonah Werre';
$mail->From = 'jonahwerre@gmail.com';
$mail->Subject = 'subject';
$mail->Body = 'message';
$mail->isMail();
// $mail->AddAddress('jwerre@gmail.com', 'jwerre');
$mail->add_recipients( array( array('jwerre@gmail.com', 'jwerre'), array('jonahwerre@gmail.com', 'jonahwerre')) );
echo ($mail->Send()) ? 'sent' : 'not sent';

?>
