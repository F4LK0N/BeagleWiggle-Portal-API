<?
namespace Core\Provider;
defined("FKN") or http_response_code(403).die('Forbidden!');
use Exception;
use eERROR;
use Core\Helper\FILTER;
use Core\Provider\ROUTER;
use App\App;

class DISPATCHER
{
    static private ?object $MODULE     = null;
    static private ?object $CONTROLLER = null;
    static private ?object $ACTION     = null;
    static private ?array  $PARAMETERS = null;



    static public function RUN (string $module = null,
                                string $controller = null,
                                string $action = null,
                                array  $parameters=null
    ): void
    {
        self::SETUP($module, $controller, $action, $parameters);
        self::VERIFY();
        self::SETUP_CONTROLLER();
        self::SETUP_ACTION();
        self::SETUP_PARAMETERS();
        self::TRIGGER_EVENTS();
    }
    
    
    
    static private function SETUP ( string $module = null,
                                    string $controller = null,
                                    string $action = null,
                                    array  $parameters=null
    ){
        try
        {
            if($module===null || $controller===null || $action===null){
                $module     = ROUTER::MODULE();
                $controller = ROUTER::CONTROLLER();
                $action     = ROUTER::ACTION();
                $parameters = ROUTER::PARAMS();
            }
            
            self::$MODULE = (object)[
                'target'     => $module,
                'path'       => '',
                'namespace'  => 'App\Controllers',
                'class'      => '',
                'instance'   => null,
            ];

            self::$CONTROLLER = (object)[
                'target'     => $controller,
                'path'       => '',
                'namespace'  => 'App\Controllers',
                'class'      => '',
                'instance'   => null,
            ];
            
            self::$ACTION = (object)[
                'target'     => $action,
                'path'       => '',
                'namespace'  => 'App\Controllers',
                'class'      => '',
                'instance'   => null,
            ];

            self::$PARAMETERS = $parameters;
            if(!is_array( self::$PARAMETERS)){
                self::$PARAMETERS=[];
            }
        }
        catch (Exception $exception) {
            //TODO EXCEPTION CHAIN
            throw (new Exception('Internal Error!', eERROR::INTERNAL));
        }
    }



    static private function VERIFY()
    {
        self::VERIFY_MODULE();
        self::VERIFY_CONTROLLER();
        self::VERIFY_ACTION();
    }
    static private function VERIFY_MODULE()
    {
        if(
            self::$MODULE->target !== 'PUBLIC' && 
            self::$MODULE->target !== 'ADM' && 
            self::$MODULE->target !== 'AUTH'
        ){
            throw (new Exception('MODULE not found!!', eERROR::NOT_FOUND));
        }
    }
    static private function VERIFY_CONTROLLER()
    {
        //### PATH ###
        self::$CONTROLLER->path = PATH_APP.'/Controllers/'.self::$MODULE->target."/".self::$CONTROLLER->target.'/_Controller.php';
        if(!is_file(self::$CONTROLLER->path)){
            throw (new Exception('CONTROLLER not found!', eERROR::NOT_FOUND));
        }

        //### NAMESPACE ###
        self::$CONTROLLER->namespace = FILTER::REPLACE('/', '\\', self::$CONTROLLER->namespace);
        if(self::$MODULE->target!=="PUBLIC"){
            self::$CONTROLLER->namespace.= "\\".self::$MODULE->target;
        }

        //### CLASS ##
        self::$CONTROLLER->class = self::$CONTROLLER->target;
        if(($pos=strpos(self::$CONTROLLER->class, '/'))!==false){
            self::$CONTROLLER->namespace .= '/' .substr(self::$CONTROLLER->class, 0, $pos);
            self::$CONTROLLER->namespace = FILTER::REPLACE('/', '\\', self::$CONTROLLER->namespace);
            self::$CONTROLLER->class = substr(self::$CONTROLLER->class, $pos+1);
        }
        self::$CONTROLLER->class .= 'Controller';
    }
    static private function VERIFY_ACTION()
    {
        self::$ACTION->path  = PATH_APP.'/Controllers/'.self::$MODULE->target."/".self::$CONTROLLER->target.'/'.self::$ACTION->target.'Action.php';
        if(!is_file(self::$ACTION->path)){
            throw (new Exception('ACTION not found!', eERROR::NOT_FOUND));
        }
        
        if(self::$MODULE->target!=="PUBLIC"){
            self::$ACTION->namespace.= "\\".self::$MODULE->target;
        }
        self::$ACTION->namespace .= '\\' . self::$CONTROLLER->target;
        self::$ACTION->namespace = FILTER::REPLACE('/', '\\', self::$ACTION->namespace);
        
        self::$ACTION->class = self::$ACTION->target . 'Action';
    }



    static private function SETUP_CONTROLLER()
    {
        require_once self::$CONTROLLER->path;

        $qualifiedName = self::$CONTROLLER->namespace.'\\'.self::$CONTROLLER->class;
        self::$CONTROLLER->instance = new $qualifiedName();
    }
    static private function SETUP_ACTION()
    {
        require_once self::$ACTION->path;

        $qualifiedName = self::$ACTION->namespace.'\\'.self::$ACTION->class;
        self::$ACTION->instance = new $qualifiedName();

        self::$CONTROLLER->instance->setup(self::$ACTION);
        self::$ACTION->instance->setup(self::$CONTROLLER);
    }
    static private function SETUP_PARAMETERS()
    {
        $_POST = array_merge($_POST, self::$PARAMETERS);
    }



    static private function TRIGGER_EVENTS()
    {
        try
        {
            self::$CONTROLLER->instance->before();
            self::$ACTION->instance->input();
            self::$ACTION->instance->run();
            self::$CONTROLLER->instance->after();
            App::RESULT(self::$ACTION->instance);
        }
        catch(Exception $exception) {
            App::EXIT($exception);
        }
    }

}
