<?php
session_start();
session_destroy();

header("Location:Responders_mobile/Responder_mobile/api/login_responder.php");
exit();
?>