<?
namespace Core;
defined("FKN") or http_response_code(403).die('Forbidden!');
use JsonSerializable;
use Exception;
use eERROR;
use Core\Helper\MATH;

/*
 * This class represent one single error, or a stack of errors.
 * 
 * An method can create an Error() on the fly and return it to the caller.
 * As it can also receive a Error as a response and add it to its own Error 
 * utilizing its stack.
 * 
 * The methods code() and message() are used to treat a error like a single error.
 * They will get always the first values from the stack.
 * When the stack is empty it will response as it don't have error with the 
 * value eERROR:NONE
 */
class Error implements JsonSerializable
{
    protected array $stack = [];
    
    /*
     * An method can create an Error() on the fly and return it to the caller.
     * As it can also receive a Error as a response and add it to its own Error 
     * utilizing its stack.
     */
    public function __construct(mixed $codeOrObject=null, string $message='', object $object=null)
    {
        $this->setError($codeOrObject, $message, $object);
    }
    
    public function setError(mixed $codeOrObject=null, string $message='', object $object=null): Error
    {
        if($codeOrObject===null){
            return $this;
        }
        
        if(is_int($codeOrObject)){
            $this->setByObject($object);
            $this->setByParameters($codeOrObject, $message);
        }else{
            $this->setByObject($codeOrObject);
        }
        return $this;
    }
    private function setByParameters(int $code, string $message)
    {
        array_unshift(
            $this->stack,
            [
                "code"    => MATH::MAX(eERROR::GENERIC, $code),
                "message" => $message,
            ],
        );
    }
    private function setByObject(?object $object)
    {
        if($object===null){
            return;
        }
        
        if(is_a($object, 'Error') || is_a($object, 'Core\Error')){
            $this->setByErrorObject($object);
        }
        else if(is_a($object, 'Exception')){
            $this->setByException($object);
        }
    }
    private function setByErrorObject(Error $error)
    {
        $this->stack = array_merge(
            $error->stack(),
            $this->stack,
        );
    }
    private function setByException(Exception $exception)
    {
        array_unshift(
            $this->stack,
            [
                "code"=> MATH::MAX(eERROR::GENERIC, $exception->getCode()),
                "message"=> $exception->getMessage(),
                "exception"=>$exception,
            ]
        );
    }
    
    public function hasError(): bool
    {
        return ($this->code()!==eERROR::NONE);
    }
    public function code(): int
    {
        if(count($this->stack)===0){
            return eERROR::NONE;
        }
        return $this->stack[0]['code'];
    }
    public function message(): string
    {
        if(count($this->stack)===0){
            return '';
        }
        return $this->stack[0]['message'];
    }
    
    public function fullMessage(): string
    {
        $fullMessage="";
        foreach($this->stack as $error){
            $fullMessage.="\n".$error['message'];
        }
        return substr($fullMessage, 1);
    }
    
    public function details(): string
    {
        if(count($this->stack)<2){
            return '';
        }
        $details="";
        for($i=1; $i<count($this->stack); $i++){
            $details.="\n".$this->stack[$i]['message'];
        }
        
        return substr($details, 1);
    }
    
    
    public function stack(): array
    {
        return $this->stack;
    }
    
    public function jsonSerialize(): mixed
    {
        return array_merge([
            'code' => $this->code(),
            'message' => $this->message(),
        ], (count($this->stack)<2)?[]:[
            'stack' => $this->stack,
        ]);
    }
    
    public function throwException(mixed $codeOrObject=null, string $message='', object $object=null): void
    {
        $this->setError($codeOrObject, $message, $object);
        throw new Exception ($this->fullMessage(), $this->code());
    }
    
}
