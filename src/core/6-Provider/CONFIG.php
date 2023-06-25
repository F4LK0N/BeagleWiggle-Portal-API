<?
namespace Core\Provider;
defined("FKN") or http_response_code(403).die('Forbidden!');
use Exception;
use eERROR;

class CONFIG
{
    static private array $DATA = [];

    //### SETUP ###
    public function __construct(array $configPaths=[])
    {
        try
        {
            foreach($configPaths as $key => $file){
                if($key==='' || $file===''){
                    continue;
                }
                if(!file_exists($file)){
                    throw new Exception('File not found!', eERROR::NOT_FOUND);
                }
                self::$DATA[$key] = require $file;
            }
        }
        catch (Exception $exception) {
            if($exception->getCode()===eERROR::NOT_FOUND){
                throw $exception;
            }
            throw (new Exception('Internal Error!', eERROR::INTERNAL, $exception));
        }
    }

    //### RUN ###
    static public function GET(string $key): array
    {
        //TODO: Throw Exception for key not found
        return self::$DATA[$key];
    }

}
