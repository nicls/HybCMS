<?php
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/hybCMSLoader.php');

header('Location: ' . HYB_PROTOCOL . HYB_HOST_NAME . '/login.html');
?>