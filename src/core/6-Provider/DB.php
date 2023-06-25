<?
namespace Core\Provider;
defined("FKN") or http_response_code(403).die('Forbidden!');
use Phalcon\Db\Adapter\Pdo\Mysql;

class DB extends Mysql
{
    static private ?DB $INSTANCE = null;
    
    static public function INSTANCE (string $database=''): DB
    {
        if(self::$INSTANCE===null || $database!==''){
            self::INSTANTIATE($database);
        }
        
        return self::$INSTANCE;
    }
    
    static private function INSTANTIATE (string $database): void
    {
        $config = CONFIG::GET('DB');
        
        if($database==='unit' || $database==='stress'){
            $config['dbname'] .= '_'.$database;
            $config['username']= 'tdd';
        }
        
        if(constant('RUN_MODE')==="SETUP"){
            $config['username']="dev";
        }
        
        self::$INSTANCE = new DB($config);
    }
}
