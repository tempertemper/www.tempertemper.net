<?php

  define('PERCH_LICENSE_KEY', 'P21207-SKF459-BNK198-JDA345-QNL016');

  $http_host = getenv('HTTP_HOST');
  switch($http_host)
  {

  case('tempertemper.local') :
    define("PERCH_DB_USERNAME", 'admin');
    define("PERCH_DB_PASSWORD", 'Carmen28');
    define("PERCH_DB_DATABASE", "tempertemper");
    define('PERCH_PRODUCTION_MODE', PERCH_DEVELOPMENT);
   break;

  default :
    define("PERCH_DB_USERNAME", 'tempertemper');
    define("PERCH_DB_PASSWORD", 'GPPJZkJRqtPju@}jG3GKr8YwtyAnxu');
    define("PERCH_DB_DATABASE", "tempertemper");
    define('PERCH_PRODUCTION_MODE', PERCH_PRODUCTION);
    break;
  }

  define("PERCH_DB_SERVER", "localhost");
  define("PERCH_DB_PREFIX", "perch2_");

  define('PERCH_TZ', 'UTC');

  define('PERCH_EMAIL_METHOD', 'smtp');
  define('PERCH_EMAIL_FROM', 'hello@tempertemper.net');
  define('PERCH_EMAIL_FROM_NAME', 'tempertemper');
  define('PERCH_EMAIL_AUTH', true);
  define('PERCH_EMAIL_SECURE', 'tls');
  define("PERCH_EMAIL_HOST", "smtp.postmarkapp.com");
  define('PERCH_EMAIL_USERNAME', '44c111e5-4fc8-4649-bbba-692d221c549a');
  define('PERCH_EMAIL_PASSWORD', '44c111e5-4fc8-4649-bbba-692d221c549a');

  define('PERCH_LOGINPATH', '/cms');
  define('PERCH_PATH', str_replace(DIRECTORY_SEPARATOR.'config', '', dirname(__FILE__)));
  define('PERCH_CORE', PERCH_PATH.DIRECTORY_SEPARATOR.'core');

  define('PERCH_RESFILEPATH', PERCH_PATH . DIRECTORY_SEPARATOR . 'resources');
  define('PERCH_RESPATH', PERCH_LOGINPATH . '/resources');

  define('PERCH_MAP_JS', '/cms/addons/plugins/maps/custom_maps.js');

  define('PERCH_HTML5', true);
  define('PERCH_RWD', true);

?>