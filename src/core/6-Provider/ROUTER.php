<?
namespace Core\Provider;
defined("FKN") or http_response_code(403).die('Forbidden!');
use Exception;
use Phalcon\Mvc\Router as PhalconRouter;
use Phalcon\Mvc\Router\GroupInterface;
use eERROR;
use Core\Provider\SERVER;

class ROUTER
{
    static private ?PhalconRouter $ROUTER = null;



    public function __construct(array $routesPaths=[])
    {
        try
        {
            self::LOAD();
            foreach($routesPaths as $file){
                if($file===''){
                    continue;
                }
                if(!file_exists($file)){
                    throw new Exception('File not found!', eERROR::NOT_FOUND);
                }
                require $file;
            }
        }
        catch (Exception $exception) {
            if($exception->getCode()===eERROR::NOT_FOUND){
                throw $exception;
            }
            throw (new Exception('Internal Error!', eERROR::INTERNAL, $exception));
        }
    }

    
    
    //##################################
    //### SETUP ###
    //##################################
    static private function LOAD ()
    {
        if(self::$ROUTER!==null){
            return;
        }
        self::$ROUTER = new PhalconRouter(false);

        //### DEFAULT ###
        self::$ROUTER->setDefaultNamespace('App\Controllers');
        self::$ROUTER->setDefaultController('Index');
        self::$ROUTER->setDefaultAction('Index');
        self::$ROUTER->handle('/');

        //### NOT FOUND ###
        self::$ROUTER->notFound([
            'controller' => 'Index',
            'action'     => 'NotFound',
        ]);

        //### FORBIDDEN ###
        self::$ROUTER->add('/Forbidden/', [
            'controller' => 'Index',
            'action'     => 'Forbidden',
        ]);

        //### EXPIRED ###
        self::$ROUTER->add('/Expired/', [
            'controller' => 'Index',
            'action'     => 'Expired',
        ]);
    }
    static public function MOUNT(GroupInterface $group)
    {
        self::$ROUTER->mount($group);
    }

    

    //##################################
    //### RUN ###
    //##################################
    static public function RUN(?string $requestedUri=null)
    {
        //TODO:
        //try
        //{
            if($requestedUri===null){
                $requestedUri=SERVER::PATH();
            }
            self::$ROUTER->handle($requestedUri);
    }
    
    
    
    //##################################
    //### GETTERS ###
    //##################################
    static public function CONTROLLER()
    {
        return self::$ROUTER->getControllerName();
    }
    static public function ACTION()
    {
        return self::$ROUTER->getActionName();
    }
    static public function PARAMS()
    {
        return self::$ROUTER->getParams();
    }
    
}
