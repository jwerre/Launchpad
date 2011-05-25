<?php
    include '../../lib/initialize.php';

    global $cookie;

    echo '<pre>'; print_r($_GET); echo '</pre>'; 
    if(isset($_GET)){
        foreach ($_GET as $key => $value) {
            $cookie->$key = $value;
        }

        echo $cookie->$key;
    }
?>
