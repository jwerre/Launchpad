
<?php 
include 'lib/initialize.php';


$mail = new Mail();
$sent = $mail->email("sunject", "message", NULL, NULL, "jonah" , "jonahwerre@gmail.com");
echo ($sent) ? "SENT" : "NOT SENT";
echo '<pre>'; print_r($mail); echo '</pre>'; exit;
?>

