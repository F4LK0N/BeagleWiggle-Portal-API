<?
namespace Core\Helper;
defined("FKN") or http_response_code(403).die('Forbidden!');

class STORAGE 
{
    static private $UNITS = [
        "B",
        "KB",
        "MB",
    ];

    static public function FORMAT(int $value)
    {
        $returnString  = "";
        foreach(self::$UNITS as $unity){
            $current = intval($value)%1024;
            if($current>0)
                $returnString = str_pad($current, 4, "_", STR_PAD_LEFT).$unity." ".$returnString;
            else
                $returnString = "___0".$unity." ".$returnString;
            $value = intval($value)/1024;
        }

        return substr($returnString, 0, -1);
    }

}
