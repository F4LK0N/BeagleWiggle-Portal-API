<?
namespace Core;
defined("FKN") or http_response_code(403).die('Forbidden!');
use Exception;
use stdClass;
use \JsonSerializable;
use eERROR;
use eSTATE;
use Core\Profiler;
use Core\Error;

class Result extends Error implements JsonSerializable
{
    protected ?Profiler $profiler = null;
    protected ?object   $data     = null;



    public function __construct(mixed $keyOrObject=null, mixed $value=null)
    {
        if(constant('RUN_MODE')==='DEBUG'){
            $this->profiler = new Profiler();
        }
        $this->data = new stdClass();
        
        if($keyOrObject!==null){
            $this->set($keyOrObject, $value);
        }
    }



    public function state(): int
    {
        return ($this->hasError()===true)?(eSTATE::ERROR):(eSTATE::SUCCESS);
    }

    public function set(mixed $keyOrObject=null, mixed $value=null): Result
    {
        if(is_string($keyOrObject)){
            $this->data->$keyOrObject = $value;
            return $this;
        }
        
        if(is_object($keyOrObject)){
            $this->data = $keyOrObject;
            return $this;
        }
        
        if(is_array($keyOrObject)){
            $this->data = (object)$keyOrObject;
            return $this;
        }
        
        throw new Exception("Invalid parameter for result!");
    }

    public function get(string $key=null): mixed
    {
        if($key===null){
            return $this->data;
        }
        if(!isset($this->data->$key)){
            throw new Exception("Get key '$key' not found in result!");
        }
        return $this->data->$key;
    }

    public function jsonSerialize(): mixed
    {
        return array_merge([
            'state' => $this->state(),
            'error' => array_merge([
                    'code' => $this->code(),
                    'message' => $this->message(),
                ], (count($this->stack)<2)?[]:[
                    'stack' => $this->stack,
                ]),
            'data'     => $this->data,
        ], constant('RUN_MODE')!=='DEBUG'?[]:[
            'profiler' => $this->profiler,
        ]);
    }

}
