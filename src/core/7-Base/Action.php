<?
namespace Core\Base;
defined("FKN") or http_response_code(403).die('Forbidden!');
use Core\Inputs;
use Core\Result;

class Action extends Result
{
    protected int     $access_type  = 0;
    protected int     $access_level = 0;

    protected ?object $controller   = null;
    protected ?Inputs $inputs       = null;


    public function __construct()
    {
        parent::__construct();
        $this->inputs = new Inputs();
    }
    
    public function setup($controller, $params=[]): void
    {
        $this->controller = $controller;
    }

    public function input(): void
    {
        
    }

    public function run(): void
    {
        //transactions
        //rules
        //processes
    }
    
}
