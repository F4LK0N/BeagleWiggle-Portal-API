<?
//##############################################################################
//### ENTRYPOINT ###
//##############################################################################
// Simulates the entrypoint of the application.
// Mimic some server behaviors.
//##############################################################################
define('FKN', true);
define('RUN_MODE', 'SETUP');
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
require "/src/app/App.php";

//##############################################################################
//### TDD RESOURCES ###
//##############################################################################
require "/src/tests/FknTestCase.php";

error_reporting(E_ALL);
set_time_limit(60*5);



echo "<pre>".
"################################################################################\n".
"### DEV - SETUP ################################################################\n".
"################################################################################\n";

function db_create_tables($database)
{
    $db = new \Core\Provider\DB(['db'=>$database]);
    $dirArray = scandir(PATH_APP.'/Models/db/');
    foreach($dirArray as $file){
        if(substr($file, -4)!=='.sql'){
            continue;
        }
        $SQL = file_get_contents(PATH_APP.'/Models/db/'.$file);
        echo "$file\n";
        $db->execute($SQL);
    }
}



echo
"### DB - CREATE TABLES - MAIN ###\n".
"########################################\n";
new Core\Provider\CONFIG([
    'DB' => PATH_APP.'/Config/DB.php',
]);
db_create_tables('main');



echo
"### DB - CREATE TABLES - UNIT ###\n".
"########################################\n";
$_GET['RUN_MODE']="UNIT";
db_create_tables('main_unit');



echo
"### DB - CREATE TABLES - STRESS ###\n".
"########################################\n";
$_GET['RUN_MODE']="STRESS";
db_create_tables('main_stress');



$profiler = new \Core\Profiler(true);
print ($profiler->format());
echo
"################################################################################\n";
"### DEV - FINISHED #############################################################\n";
"################################################################################\n";
