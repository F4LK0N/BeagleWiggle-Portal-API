<?
namespace Core\Provider;
use Exception;
use eMODULE;
use eSTEP;
use eSERVER_TIER;
use eSERVER_ENVIRONMENT;
use eSERVER_PROVIDER;
use Core\Helper\FILTER;
defined("FKN") or http_response_code(403).die('Forbidden!');

class SERVER
{
    static private string $SCHEME         = "";
    static private string $HOST           = "";
    static private string $DOMAIN         = "";
    static private string $PORT           = "";

    static private string $URI            = "";
    static private string $PATH           = "";

    static private int    $ENVIRONMENT    = -1;
    static private int    $PROVIDER       = -1;
    static private int    $TIER           = -1;




    public function __construct()
    {
        if(self::$HOST!==''){
            return;
        }

        self::INPUT_VERIFY();
        self::INPUT_SANITIZE();

        self::IDENTIFY_ADDRESS();
        self::IDENTIFY_REQUEST();

        self::IDENTIFY_SURROUNDINGS();

        self::REQUEST_VERIFY();
        self::REQUEST_SANITIZE();

        self::SET_PHP_CONFIG();
    }



    static private function INPUT_VERIFY()
    {
        //TODO: Exception if $_SERVER['REQUEST_SCHEME'] != https && http
    }

    static private function REQUEST_VERIFY()
    {
        //TODO: Exception if $['PATH'] has '\\' char;
    }

    static private function INPUT_SANITIZE()
    {
        //TODO: ...
    }

    static private function REQUEST_SANITIZE()
    {
        //TODO: ...
    }



    static private function IDENTIFY_ADDRESS()
    {
        self::$SCHEME = strtolower($_SERVER['REQUEST_SCHEME']??'http');
        self::$HOST   = strtolower($_SERVER['HTTP_HOST']);

        $hostParts    = explode(":", self::$HOST);
        self::$DOMAIN = $hostParts[0];
        self::$PORT   = $hostParts[1]??80;

        self::$HOST = self::$SCHEME .'://'. self::$HOST;
    }

    static private function IDENTIFY_REQUEST()
    {
        self::$URI = $_SERVER['REQUEST_URI'];
        self::$URI = FILTER::REPLACE('//', '/', self::$URI);

        self::$PATH = explode('?', self::$URI)[0];
        self::$PATH = FILTER::REPLACE('\\', '/', self::$PATH);
    }

    static private function IDENTIFY_SURROUNDINGS()
    {
        self::IDENTIFY_TIER();
        self::IDENTIFY_ENVIRONMENT();
        self::IDENTIFY_PROVIDER();
    }

    static private function IDENTIFY_TIER()
    {
        self::$TIER = eSERVER_TIER::DEV;

        if(isset($_ENV['TIER'])){
            if($_ENV['TIER']==="PRODUCTION" || $_ENV['TIER']==="PROD"){
                self::$TIER = eSERVER_TIER::PROD;
            }
            elseif($_ENV['TIER']==="STAGING" || $_ENV['TIER']==="STAG"){
                self::$TIER = eSERVER_TIER::STAG;
            }
        }
    }

    static private function IDENTIFY_ENVIRONMENT()
    {
        if(self::$DOMAIN==="127.0.0.1" || self::$DOMAIN==="api.news" || self::$DOMAIN==="web.news" || self::$DOMAIN==="localhost"){
            self::$ENVIRONMENT = eSERVER_ENVIRONMENT::OFFLINE;
        }else{
            self::$ENVIRONMENT = eSERVER_ENVIRONMENT::ONLINE;
        }
    }

    static private function IDENTIFY_PROVIDER()
    {
        self::$PROVIDER = eSERVER_PROVIDER::UNKNOWN;

        if(self::$ENVIRONMENT===eSERVER_ENVIRONMENT::OFFLINE){
            self::$PROVIDER = eSERVER_PROVIDER::DEVELOPER;
        }

    }


    static private function SET_PHP_CONFIG()
    {
        //### TIMEZONE ###
        if(self::$TIER!==eSERVER_TIER::DEV){
            //DEV - Timezone by Server Config
            date_default_timezone_set("America/Sao_Paulo");
        }


        //### TIMEOUT ###
        if(
            //PROD - Short Time
            self::$TIER!==eSERVER_TIER::PROD &&
            (
                //DEV && STAG - Long Time Option
                defined('RUN_MODE')
            )
        ){
            if(constant('RUN_MODE')==='STRESS'){
                set_time_limit(600);
            }elseif(constant('RUN_MODE')==='UNIT'){
                set_time_limit(300);
            }else{
                set_time_limit(3);
            }
        }else{
            set_time_limit(3);
        }


        //### ERROR REPORTING ###
        if(
            //PROD - Always Hide
            self::$TIER!==eSERVER_TIER::PROD &&
            (
                //DEV - Always Show
                self::$TIER===eSERVER_TIER::DEV ||

                //STAG - Option Show
                (defined('RUN_MODE') && constant('RUN_MODE')==='DEBUG')
            )
        ){
            error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
        }else{
            error_reporting(0);
        }

    }




    //### SURROUNDINGS ###
    static public function SCHEME(): string
    {
        return self::$SCHEME;
    }
    static public function HOST(): string
    {
        return self::$HOST;
    }
    static public function DOMAIN(): string
    {
        return self::$DOMAIN;
    }
    static public function PORT(): string
    {
        return self::$PORT;
    }



    //### REQUEST ###
    static public function URI(): string
    {
        return self::$URI;
    }

    static public function PATH(): string
    {
        return self::$PATH;
    }



    //### ENVIRONMENT ###
    static public function ENVIRONMENT(): int
    {
        return self::$ENVIRONMENT;
    }
    static public function ENVIRONMENT_TOKEN(): string
    {
        if(self::$ENVIRONMENT === eSERVER_ENVIRONMENT::OFFLINE){
            return "OFFLINE";
        }else{
            return "ONLINE";
        }
    }
    static public function ENVIRONMENT_LABEL(): string
    {
        if(self::$ENVIRONMENT === eSERVER_ENVIRONMENT::OFFLINE){
            return "Offline";
        }else{
            return "Online";
        }
    }
    static public function IS_ENVIRONMENT(int $value): bool
    {
        return (self::$ENVIRONMENT === $value);
    }



    //### PROVIDER ###
    static public function PROVIDER(): int
    {
        return self::$PROVIDER;
    }



    //### TIER ###
    static public function TIER(): int
    {
        return self::$TIER;
    }
    static public function TIER_TOKEN (): string
    {
        if(self::$TIER === eSERVER_TIER::PROD)
            return "PROD";
        else if(self::$TIER === eSERVER_TIER::STAG)
            return "STAG";
        else
            return "DEV";
    }
    static public function TIER_LABEL (): string
    {
        if(self::$TIER === eSERVER_TIER::PROD)
            return "Production";
        else if(self::$TIER === eSERVER_TIER::STAG)
            return "Staging";
        else
            return "Development";
    }
    static public function IS_TIER ($value): bool
    {
        return (self::$TIER===$value);
    }








    //TODO:GETTERS

}

(new SERVER());
