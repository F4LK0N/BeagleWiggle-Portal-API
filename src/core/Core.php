<?//@codeCoverageIgnoreStart
namespace Core;
defined("FKN") or http_response_code(403).die('Forbidden!');

//##############################################################################
//### PROFILER ###
//##############################################################################
// Special piece of code defined outside of the project structure to benchmark
// using the values on the very beginning of the script execution.
//##############################################################################
define("PROFILER_TIME",        microtime(true));         //(msec)  Timestamp
define("PROFILER_MEMORY",      memory_get_usage(false)); //(Bytes) Memory Used
define("PROFILER_MEMORY_REAL", memory_get_usage(true));  //(Bytes) Memory Alloc.
//##############################################################################
//### BASIC ###
//##############################################################################
// Content that must by loaded before anything else.
// Some conditions exit the execution to preserve recurses.
//##############################################################################
require "0-Basic/HEADERS.php";
require "0-Basic/PATHS.php";
//##############################################################################
//### ENUMERATIONS  ###
//##############################################################################
require "1-Enum/eSTATE.php";             //STATE
require "1-Enum/eERROR.php";             //STATE
require "1-Enum/eSERVER_ENVIRONMENT.php";//SERVER
require "1-Enum/eSERVER_PROVIDER.php";   //SERVER
require "1-Enum/eSERVER_TIER.php";       //SERVER
require "1-Enum/eCLIENT_PLATFORM.php";   //CLIENT
require "1-Enum/eUSER_TYPE.php";         //USER
require "1-Enum/eNEWS_STEP.php";         //NEWS
//##############################################################################
//### HELPERS ###
//##############################################################################
// Classes that provide static methods to handle data.
// This classes don't throw errors or exception, only in exceptional cases.
// They always try to treat the errors and exceptions in some way an always try 
// to provide a output or partial output.
//##############################################################################
require "3-Helper/DEBUG.php";
require "3-Helper/FILTER.php";
require "3-Helper/VALIDATOR.php";
require "3-Helper/JSON.php";
require "3-Helper/SECURITY.php"; 
require "3-Helper/MATH.php";
require "3-Helper/STORAGE.php";
require "3-Helper/TIME.php";
//##############################################################################
//### HANDLERS ###
//##############################################################################
// Classes that are instantiate to handle data.
// This classes throw errors
//##############################################################################
require "4-Handler/Profiler.php";
require "4-Handler/Error.php";
require "4-Handler/Result.php";
require "4-Handler/Inputs.php";
require "4-Handler/Page.php";
//##############################################################################
//### PROVIDERS ###
//##############################################################################
// Similar to helpers, but a essential function in the application flow.
//##############################################################################
require "6-Provider/SERVER.php";
require "6-Provider/CONFIG.php";
require "6-Provider/ROUTER.php";
require "6-Provider/DISPATCHER.php";
require "6-Provider/DB.php";
//##############################################################################
//### BASE CLASSES ###
//##############################################################################
require "7-Base/Model.php";
require "7-Base/Action.php";
require "7-Base/Controller.php";
//##############################################################################

//@codeCoverageIgnoreEnd
