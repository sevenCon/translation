<?php
require "mysql.func.php";

define('DB_HOST','localhost');
define('DB_USER' ,'root');
define('DB_PWD','');
define('DB_NAME','duanluo');
error_reporting(0);
sys_connect();
sys_select_db();
sys_set_names();  
?>