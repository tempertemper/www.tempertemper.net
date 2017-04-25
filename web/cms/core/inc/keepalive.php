<?php
    include('pre_config.php');
    include('../../config/config.php');
    include('./loader.php');
    PerchSession::keep_alive();
    echo 'OK'.PHP_EOL;