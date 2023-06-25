<?
namespace Core\Helper;
defined("FKN") or http_response_code(403).die('Forbidden!');

class VALIDATOR
{
    static private array $ERRORS = [];
    static public function ERRORS(?string $fieldName=null): string
    {
        if(0===count(self::$ERRORS)){
            return "";
        }
        
        if($fieldName===null){
            $response="Invalid!";
        }else{
            $response="'$fieldName' invalid!";
        }
        
        foreach(self::$ERRORS as $error){
            if($fieldName===null){
                $response.="\n".ucfirst($error);
            }else{
                $response.="\n'$fieldName' $error";
            }
        }
        return $response;
    }
    
    
    //##########################################################################
    //### NUMERIC ###
    //##########################################################################
    static public function MIN (int $min, mixed $input): bool
    {
        if(!is_numeric($input)){
            return false;
        }
        return (intval($input)>=$min);
    }
    static public function MAX (int $max, mixed $input): bool
    {
        if(!is_numeric($input)){
            return false;
        }
        return (intval($input)<=$max);
    }
    static public function BETWEEN (int $min, int $max, mixed $input): bool
    {
        return (self::MIN($min, $input) && self::MAX($max, $input));
    }
    //##########################################################################
    //### STRING ###
    //##########################################################################
    static public function MIN_LENGTH (int $min, mixed $input): bool
    {
        if(!is_string($input)){
            return false;
        }
        return (strlen($input)>=$min);
    }
    static public function MAX_LENGTH (int $max, mixed $input): bool
    {
        if(!is_string($input)){
            return false;
        }
        return (strlen($input)<=$max);
    }
    //##########################################################################
    //### TEXT ###
    //##########################################################################
    static public function FIELD_NAME (mixed $input): bool
    {
        //Allow lower case;
        //Allow upper case;
        //Allow numbers;
        //Allow '_';
        //Allow '-';
        //Allow start '_';
        //Deny  start '-';
        //Deny  start numbers;
        //Deny  end '-';
        
        //First char after start '_' must be a letter;
        //Must have at least one letter;
        
        if(
            (!is_string($input)) ||
            (1!==preg_match('/^[_]*[a-zA-Z]+[a-zA-Z0-9_\-]*$/', $input)) ||
            (1===preg_match('/\-$/', $input))
        ){
            return false;
        }
        return true;
    }
    static public function DB_NAME (mixed $input): bool
    {
        //Allow lower case;
        //Allow numbers;
        //Allow '_';
        //Allow start '_';
        //Deny  end '_';
        //Deny  start numbers;
        
        //First char after start '_' must be a letter;
        //Must have at least one letter;
        
        if(
            (!is_string($input)) ||
            (1!==preg_match('/^[_]*[a-z]+[a-z0-9_]*$/', $input)) ||
            (1===preg_match('/_$/', $input))
        ){
            return false;
        }
        return true;
    }
    static public function EMAIL (mixed $input): bool
    {
        if(
            (!is_string($input)) ||
            (false===filter_var($input, FILTER_VALIDATE_EMAIL))
        ){
            return false;
        }
        return true;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    static public function RUN(mixed $input, array $validations): bool
    {
        self::$ERRORS = [];
        foreach($validations as $param1 => $param2)
        {
            $result = self::RUN_SIMPLE($input, $param2);
            if($result!==null){
                continue;
            }
            
            $result = self::RUN_COMPLEX($input, $param1, $param2);
            if($result!==null){
                continue;
            }
            
            throw new \Exception("Invalid validation!");
        }
        return (count(self::$ERRORS)===0);
    }
    static protected function RUN_SIMPLE(mixed &$input, mixed &$validation): ?bool
    {
        if(!is_string($validation)){
            return null;
        }
        
        if($validation==="field-name"){
            $result = self::FIELD_NAME($input);
            if($result===false){
                self::$ERRORS[] = "must comply 'field-name' rule!";
            }
            return $result;
        }
        if($validation==="db-name"){
            $result = self::DB_NAME($input);
            if($result===false){
                self::$ERRORS[] = "must comply 'db-name' rule!";
            }
            return $result;
        }
        
        return null;
    }
    static public function RUN_COMPLEX(mixed &$input, mixed &$validation, mixed $parameters): ?bool
    {
        if(!is_string($validation) || (!is_string($parameters) && !is_numeric($parameters))){
            return null;
        }
        
        $parameters = explode(':', strval($parameters));
        
        if($validation==="min"){
            if(!is_numeric($parameters[0])){
                return null;
            }
            $result = self::MIN(intval($parameters[0]), $input);
            if($result===false){
                self::$ERRORS[] = "min value must be '".$parameters[0]."'!";
            }
            return $result;
        }
        if($validation==="max"){
            if(!is_numeric($parameters[0])){
                return null;
            }
            $result = self::MAX(intval($parameters[0]), $input);
            if($result===false){
                self::$ERRORS[] = "max value must be '".$parameters[0]."'!";
            }
            return $result;
        }
        if($validation==="between"){
            if(!is_numeric($parameters[0]) || !isset($parameters[1]) || !is_numeric($parameters[1])){
                return null;
            }
            $result = self::BETWEEN(intval($parameters[0]), intval($parameters[1]), $input);
            if($result===false){
                self::$ERRORS[] = "value must be between '".$parameters[0]."' and '".$parameters[1]."'!";
            }
            return $result;
        }
        if($validation==="min-length"){
            if(!is_numeric($parameters[0])){
                return null;
            }
            $result = self::MIN_LENGTH(intval($parameters[0]), $input);
            if($result===false){
                self::$ERRORS[] = "min length must be '".$parameters[0]."'!";
            }
            return $result;
        }
        if($validation==="max-length"){
            if(!is_numeric($parameters[0])){
                return null;
            }
            $result = self::MAX_LENGTH(intval($parameters[0]), $input);
            if($result===false){
                self::$ERRORS[] = "max length must be '".$parameters[0]."'!";
            }
            return $result;
        }
        
        return null;
    }
}
