<?
namespace Core\Base;
defined("FKN") or http_response_code(403).die('Forbidden!');
use Phalcon\Mvc\Model as PhalconModel;
use DateTimeImmutable;
use Core\Provider\DB;
use Exception;
use eERROR;

class Model extends PhalconModel
{
    static public function TS_DATABASE($modelTimestamp): string
    {
        $date = new DateTimeImmutable($modelTimestamp, new \DateTimeZone('America/Sao_Paulo'));
        return $date->format("Y-m-d H:i:s");
    }
    static public function TS_MODEL(?string $databaseTimestamp): int
    {
        if($databaseTimestamp===null){
            return 0;
        }
        
        $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $databaseTimestamp, new \DateTimeZone('America/Sao_Paulo'));
        if($date===false){
            return -1;
        }
        return $date->getTimestamp();
    }
    static public function TRANSLATE_ERROR_MESSAGE(string $message): string
    {
        //### DUPLICATE ###
        if(stripos($message, "constraint violation") && stripos($message, "duplicate"))
        {
            if(stripos($message, ".UK_NAME'")){
                return "'name' already registered!";
            }
            if(stripos($message, ".UK_EMAIL'")){
                return "'email' already registered!";
            }
            if(stripos($message, ".UK_TITLE'")){
                return "'title' already registered!";
            }
            if(stripos($message, ".UK_COVER_TITLE'")){
                return "'cover_title' already registered!";
            }
            if(stripos($message, ".UK_URL'")){
                return "'url' already registered!";
            }
        }
        
        //### DEFAULT ###
        if(constant('RUN_MODE')!=='PROD'){
            return "$message";
        }
        return "DB Error!";
    }
    
    
    
    public $ts_state;
    public $ts_created;
    public $ts_modified;
    public $ts_deleted;

    public function save(): bool
    {
        try{
            $result = parent::save();
            if($result===false){
                $messages = implode("\n", $this->getMessages());
                throw new Exception ($messages);
            }
            if(isset($this->id)){
                $this->id = intval($this->id);
            }
            return $result;
        }
        catch(Exception $exception){
            $message = $exception->getMessage();
            $message = Model::TRANSLATE_ERROR_MESSAGE($message);
            throw new Exception ($message);
        }
    }
    
    public function delete(): bool
    {
        if(!$this->id){
            throw new Exception("ID not set!", eERROR::INTERNAL);
        }
        //### TS_DELETE ###
        // TS_DELETE is a DB function that soft delete a register and return
        //  the timestamp of the delete moment.
        // Usage:
        //  TS_DELETE(table, id)
        $result = DB::INSTANCE()->query("
            SELECT TS_DELETE('".$this->getSource()."', ".intval($this->id).") AS ts_deleted;
        ");
        $result = $result->fetchArray();
        $this->ts_deleted = $result['ts_deleted'];
        return true;
    }

}
