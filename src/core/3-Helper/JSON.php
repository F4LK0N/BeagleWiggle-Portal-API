<?
namespace Core\Helper;
defined("FKN") or http_response_code(403).die('Forbidden!');
use JsonException;
use Exception;

class JSON
{
    static private int $MAX_DEPTH = 16;
    
    static public function ENCODE (mixed $input): string
    {
        //JSON_HEX_TAG  - All < and > are converted to \u003C and \u003E.
        //JSON_HEX_AMP  - All & are converted to \u0026.
        //JSON_HEX_APOS - All ' are converted to \u0027.
        //JSON_HEX_QUOT - All " are converted to \u0022.
        //JSON_THROW_ON_ERROR - Throws JsonException if an error occurs instead of setting the global error state that is retrieved with json_last_error() and json_last_error_msg().
        try{
            return json_encode(
                $input,
                    JSON_HEX_TAG  | 
                    JSON_HEX_AMP  | 
                    JSON_HEX_APOS | 
                    JSON_HEX_QUOT | 
                    JSON_THROW_ON_ERROR
                ,
                self::$MAX_DEPTH
            );
        }
        catch(JsonException $exception){
            throw new Exception("JSON Encode error!\n".$exception->getMessage().'!');
        }
    }
    static public function DECODE (string $input): mixed
    {
        //JSON_THROW_ON_ERROR - Throws JsonException if an error occurs instead of setting the global error state that is retrieved with json_last_error() and json_last_error_msg().
        try{
            return json_decode(
                $input,
                false,
                self::$MAX_DEPTH,
                JSON_THROW_ON_ERROR
            );
        }
        catch(JsonException $exception){
            throw new Exception("JSON Decode error!\n".$exception->getMessage().'!');
        }
    }

}
