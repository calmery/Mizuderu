<?php
// Set TimeZone
date_default_timezone_set('asia/tokyo');

// dir define
define("APP_ROOT", dirname(__DIR__));
define("CONFIG_DIR", APP_ROOT . "/config");
define("VENDOR_DIR", APP_ROOT . "/vendor");
define("VIEW_DIR", APP_ROOT . "/view");
define("AUTH_DIR", APP_ROOT . "/auth");
define("WEB_ROOT", APP_ROOT . "/webroot");

// required
require_once CONFIG_DIR . "/db.php";


