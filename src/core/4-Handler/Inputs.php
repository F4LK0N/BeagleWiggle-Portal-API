<?
namespace Core;
defined("FKN") or http_response_code(403).die('Forbidden!');
use Exception;
use \eERROR;
use Core\Error;
use Core\Helper\VALIDATOR;
use Core\Helper\FILTER;

class Inputs extends Error
{
    protected array $fields = [];
    protected array $data   = [];

    //##########################################################################
    //### CONFIG ###
    //##########################################################################
    public function add(array $fields): void
    {
        foreach($fields as $name => &$config)
        {
            $name = $this->parseName($name);
            if($name===null){
                continue;
            }
            
            $config = $this->parseConfig($name, $config);
            if($config===null){
                continue;
            }
            
            $this->fields["$name"]=$config;
        }
        
        if($this->hasError()){
            $this->throwException();
        }
    }
    protected function parseName(mixed &$name): ?string
    {
        if(!VALIDATOR::FIELD_NAME($name)){
            $this->setError(eERROR::NOT_ACCEPTABLE, 'Invalid field name!');
            return null;
        }
        return $name;
    }
    protected function parseConfig(string $name, mixed &$config): ?array
    {
        if(!is_array($config)){
            $this->setError(eERROR::NOT_ACCEPTABLE, "Invalid '$name' config!");
            return null;
        }
        
        $parsed = [
            'type'        => 'string',
            'required'    => true,
            'default'     => '',
            'filters'     => [],
            'validations' => [],
        ];
        
        try{
            if(isset($config['type'])){
                $parsed['type']=$this->parseConfigType($config['type']);
            }
            if(isset($config['required'])){
                $parsed['required']=$this->parseConfigRequired($config['required']);
            }
            if(isset($config['default'])){
                $parsed['default']=$this->parseConfigDefault($config['default']);
            }
            if(isset($config['filters'])){
                $parsed['filters']=$this->parseConfigFilters($config['filters']);
            }
            if(isset($config['validations'])){
                $parsed['validations']=$this->parseConfigValidations($config['validations']);
            }
        }
        catch(Exception $exception){
            $this->setError(eERROR::NOT_ACCEPTABLE, "Invalid '$name' config!");
            return null;
        }
        
        return $parsed;
    }
    protected function parseConfigType(mixed $type): string
    {
        if(!is_string($type)){
            $this->throwException(eERROR::NOT_ACCEPTABLE, "Invalid 'type' value!");
        }
        $type = strtolower($type);
        if(
            $type!=="string" &&
            $type!=="int" &&
            $type!=="bool"
        ){
            $this->throwException(eERROR::NOT_ACCEPTABLE, "Invalid 'type' value!");
        }
        return $type;
    }
    protected function parseConfigRequired(mixed $required): bool
    {
        if(!is_bool($required) && !is_numeric($required) && !is_string($required)){
            $this->throwException(eERROR::NOT_ACCEPTABLE, "Invalid 'required' value!");
        }
        $required = strval($required);
        return boolval(FILTER::BOOL($required));
    }
    protected function parseConfigDefault(mixed $default): mixed
    {
        if(!is_bool($default) && !is_numeric($default) && !is_string($default)){
            $this->throwException(eERROR::NOT_ACCEPTABLE, "Invalid 'default' value!");
        }
        if($default===false){
            return '0';
        }
        return strval($default);
    }
    protected function parseConfigFilters(mixed $filters): array
    {
        if(!is_array($filters)){
            $this->throwException(eERROR::NOT_ACCEPTABLE, "Invalid 'filters' value!");
        }
        $parsed=[];
        foreach($filters as $filter){
            if(!is_string($filter)){
                $this->throwException(eERROR::NOT_ACCEPTABLE, "Invalid 'filters' value!");
            }
            $parsed[]=strtolower($filter);
        }
        return $parsed;
    }
    protected function parseConfigValidations(mixed $validations): array
    {
        if(!is_array($validations)){
            $this->throwException(eERROR::NOT_ACCEPTABLE, "Invalid 'validations' value!");
        }
        $parsed=[];
        foreach($validations as $param1 => $param2){
            if(
                (is_int($param1) && !is_string($param2)) ||
                (is_string($param1) && (!is_string($param2) && !is_numeric($param2)))
            ){
                $this->throwException(eERROR::NOT_ACCEPTABLE, "Invalid 'validations' value!");
            }
            $parsed[strtolower($param1)]=strtolower($param2);
        }
        return $parsed;
    }
    //##########################################################################
    //### RUN ###
    //##########################################################################
    public function run()
    {
        if($this->hasError()){
            $this->throwException();
        }
        $this->runRetrieve();
        $this->runTypeCast();
        $this->runFilters();
        $this->runValidations();
    }
    protected function runRetrieve ()
    {
        $this->data = [];
        foreach($this->fields as $name => &$config)
        {
            $this->data["$name"] = '';
            
            if(isset($_POST["$name"])){
                $this->data["$name"] = strval($_POST[$name]);
            }
            elseif(isset($_GET["$name"])){
                $this->data["$name"] = strval($_GET[$name]);
            }
            
            if($this->data["$name"]!==''){
                continue;
            }
            
            if($config['default']!==''){
                $this->data["$name"] = $config['default'];
                continue;
            }
            
            if($config['required']===true){
                $this->setError(eERROR::MANDATORY, "'$name' is required!");
                continue;
            }
        }
        
        if($this->hasError()){
            $this->throwException();
        }
    }
    protected function runTypeCast()
    {
        foreach($this->fields as $name => &$config)
        {
            if($this->data["$name"]===''){
                continue;
            }
            
            if($config['type']==="bool"){
                $this->data["$name"] = FILTER::BOOL($this->data["$name"]);
            }
            elseif($config['type']==="int"){
                $this->data["$name"] = FILTER::INT($this->data["$name"]);
            }
            else{
                $this->data["$name"] = FILTER::TEXT($this->data["$name"]);
            }
        }
    }
    protected function runFilters ()
    {
        foreach($this->fields as $name => &$config)
        {
            if($this->data["$name"]===''){
                continue;
            }
            
            $this->data["$name"] = FILTER::RUN($this->data["$name"], $config['filters']);
        }
    }
    protected function runValidations ()
    {
        foreach($this->fields as $name => &$config)
        {
            if($this->data["$name"]===''){
                if($config['required']===true){
                    $this->setError(eERROR::MANDATORY, "'$name' is required!");
                }
                continue;
            }
            if(false===VALIDATOR::RUN($this->data["$name"], $config['validations'])){
                $this->setError(eERROR::NOT_ACCEPTABLE, VALIDATOR::ERRORS($name));
            }
        }
        if($this->hasError()){
            $this->throwException();
        }
    }
    //##########################################################################
    //### DATA ###
    //##########################################################################
    public function get(string $name): string
    {
        if(!isset($this->data[$name])){
            throw new Exception("'$name' input not found!");
        }
        return $this->data[$name];
    }
    
    public function set(string $name, string $value)
    {
        $this->data[$name] = $value;
    }

}
