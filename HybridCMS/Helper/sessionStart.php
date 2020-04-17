<?php
//start Session with lifetime of 10 minutes
session_set_cookie_params(60*10, '/', HYB_HOST_NAME, false, true);
session_cache_limiter('nocache');
session_start();
?>