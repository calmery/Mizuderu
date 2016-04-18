<?php
list($user, $pass) = explode(":", trim(file_get_contents(AUTH_DIR . "/db")));
define("DB_MASTER_DESTINATION", "water");
define("DB_DSN", "mysql:host=aa247qpcanfzbf.cjbydkyc8ulh.ap-northeast-1.rds.amazonaws.com;dbname=water;port=3306;charset=utf8");
//define("DB_DSN", "mysql:host=localhost;dbname=water;port=3306;charset=utf8;");
define("DB_USERNAME", $user);
define("DB_PASSWORD", $pass);
