<?
namespace App;
defined("FKN") or http_response_code(403).die('Forbidden!');
use Exception;
use eERROR;
use Core\Error;
use eCONTENT_TYPE;
use HEADERS;
use Core\Helper\JSON;
use \Core\Result;
use Core\Provider\CONFIG;
use Core\Provider\ROUTER;
use Core\Provider\DB;
use Core\Provider\DISPATCHER;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;



class App
{
    static private ?FactoryDefault $di          = null;
    static private ?Application    $application = null;
    static private ?Result         $result      = null;
    
    
    
    public function __construct()
    {
        if(self::$application!==null){
            return;
        }
        self::SETUP();
        
        if(
            constant('RUN_MODE')!=="PROD" &&
            constant('RUN_MODE')!=="DEBUG"
        ){
            return;
        }
        self::RUN();
        self::SEND();
    }
    
    
    
    //##################################
    //### SETUP ###
    //##################################
    static private function SETUP()
    {
        try{
            self::LOAD_APPLICATION();
            self::LOAD_CONFIG();
            self::LOAD_ROUTER();
            self::LOAD_DB();
        }
        catch(Exception $e){
            self::EXIT(eERROR::INTERNAL, "Server setup error!", $e);
        }
    }
    static private function LOAD_APPLICATION()
    {
        self::$di = new FactoryDefault();
        self::$application = new Application(self::$di);
        self::$result = (new Result())->setError(eERROR::INTERNAL, 'Result not set!');
    }
    static private function LOAD_CONFIG()
    {
        new CONFIG([
            'APP'    => PATH_APP.'/Config/APP.php',
            'DB'     => PATH_APP.'/Config/DB.php',
            'REDIS'  => PATH_APP.'/Config/REDIS.php',
            'BUCKET' => PATH_APP.'/Config/BUCKET.php',
        ]);
    }
    static private function LOAD_ROUTER()
    {
        new ROUTER([
            //### PUBLIC ###
            PATH_APP . '/Routes/PUBLIC/Index.php',
            PATH_APP . '/Routes/PUBLIC/News.php',
            PATH_APP . '/Routes/PUBLIC/Tags.php',
            PATH_APP . '/Routes/PUBLIC/Categories.php',
            //### AUTH ###
            PATH_APP . '/Routes/PUBLIC/Auth.php',
            //### ADM ###
            PATH_APP . '/Routes/ADM/Index.php',
            PATH_APP . '/Routes/ADM/Tags.php',
            PATH_APP . '/Routes/ADM/Categories.php',
            PATH_APP . '/Routes/ADM/News.php',
        ]);
    }
    static private function LOAD_DB()
    {
        self::$di->setShared(
            'db',
            function () {
                return DB::INSTANCE();
            }
        );
    }
    
    
    
    //##################################
    //### RUN ###
    //##################################
    static public function RUN(?string $requestedUri=null)
    {
        //TODO
        //try{
        ROUTER::RUN($requestedUri);
        DISPATCHER::RUN();
    }
    
    
    
    //##################################
    //### SEND ###
    //##################################
    static public function RESULT(?Result $result=null){
        if($result!==null){
            self::$result = $result;
        }
        return self::$result;
    }
    
    static public function EXIT(
        mixed  $codeOrObject = null, 
        string $message      = '', 
        object $object       = null
    ) {
        $result = (new Result())->setError($codeOrObject, $message, $object);
        self::$result = $result;
    }
    
    static public function SEND() {
        HEADERS::CONTENT_TYPE(eCONTENT_TYPE::JSON);
        print JSON::ENCODE(self::$result);
        exit(0);
    }
}

(new App());
