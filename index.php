<?php

 include('Config\config.php');
 include('Core\Core.php');

 $core = \Core\Core::getInstance();
 $core -> init();
 $core -> run();
 $core -> done();

?>