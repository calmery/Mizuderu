<?php
define("DB_MASTER_DESTINATION", getenv('DB_DATABASE'));
define("DB_DSN", "mysql:host=" .  getenv('DB_HOST') . ";dbname=" .  getenv('DB_DATABASE') . ";port=" .  getenv('DB_PORT') . ";charset=utf8");
define("DB_USERNAME",  getenv('DB_USERNAME'));
define("DB_PASSWORD",  getenv('DB_PASSWORD'));
