<?php

if (!defined('SUITMEDIACS_BASE_DIR')) {
    define('SUITMEDIACS_BASE_DIR', dirname(dirname(__DIR__)));
}

if (!defined('SUITMEDIACS_STANDARD_DIR')) {
    define('SUITMEDIACS_STANDARD_DIR', dirname(__DIR__));
}

if (!defined('PHP_CODESNIFFER_VERBOSITY')) {
    define('PHP_CODESNIFFER_VERBOSITY', 0);
}

require_once SUITMEDIACS_BASE_DIR . '/vendor/autoload.php';
