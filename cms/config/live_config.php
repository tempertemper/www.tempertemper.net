<?php

    define('PERCH_LICENSE_KEY', 'P21207-SKF459-BNK198-JDA345-QNL016');

    define("PERCH_DB_USERNAME", 'tempertemper');
    define("PERCH_DB_PASSWORD", '***REMOVED***');
    define("PERCH_DB_SERVER", "localhost");
    define("PERCH_DB_DATABASE", "tempertemper");
    define("PERCH_DB_PREFIX", "perch2_");

    define('PERCH_EMAIL_METHOD', 'smtp');
    define('PERCH_EMAIL_FROM', 'martin@tempertemper.net');
    define('PERCH_EMAIL_FROM_NAME', 'Martin Underhill');
    define('PERCH_EAMIL_AUTH', true);
    define('PERCH_EMAIL_SECURE', 'tls');
    define('PERCH_EMAIL_USERNAME', '***REMOVED***');
    define('PERCH_EMAIL_PASSWORD', '***REMOVED***');

    define('PERCH_LOGINPATH', '/cms');
    define('PERCH_PATH', str_replace(DIRECTORY_SEPARATOR.'config', '', dirname(__FILE__)));
    define('PERCH_CORE', PERCH_PATH.DIRECTORY_SEPARATOR.'core');

    define('PERCH_RESFILEPATH', PERCH_PATH . DIRECTORY_SEPARATOR . 'resources');
    define('PERCH_RESPATH', PERCH_LOGINPATH . '/resources');

    define('PERCH_EDITORIMAGE_MAXWIDTH', 600);
    define('PERCH_EDITORIMAGE_MAXHEIGHT', 600);
    define('PERCH_EDITORIMAGE_CROP', false);

    define('PERCH_HTML5', true);
    define('PERCH_RWD', true);

    define('PERCH_PRODUCTION_MODE', PERCH_PRODUCTION);

?>