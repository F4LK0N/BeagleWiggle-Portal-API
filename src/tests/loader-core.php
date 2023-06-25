<?
//##############################################################################
//### ENTRYPOINT ###
//##############################################################################
// Simulates the entrypoint of the application.
// Mimic some server behaviors.
//##############################################################################
define('FKN', true);
define('RUN_MODE', 'UNIT');
$_SERVER = [
    'REQUEST_URI' => '/',
    'REQUEST_METHOD' => 'POST',
    'HTTP_HOST' => '127.0.0.1',
];

//##############################################################################
//### LOADER ###
//##############################################################################
// When running PHP Unit this libraries must be loaded first because they
//   are used to create the test suits.
//##############################################################################
require "/src/vendor/autoload.php";
require "/src/core/Core.php";

//##############################################################################
//### TDD RESOURCES ###
//##############################################################################
require "/src/tests/FknTestCase.php";

error_reporting(E_ALL);
set_time_limit(60*3);
