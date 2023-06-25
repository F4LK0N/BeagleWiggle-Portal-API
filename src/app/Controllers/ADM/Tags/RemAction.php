<?
namespace App\Controllers\ADM\Tags;
defined("FKN") or http_response_code(403).die('Forbidden!');
use Core\Base\Action as BaseAction;
use App\Models\Tags;
use Exception;
use eERROR;

class RemAction extends BaseAction
{
    private $tag;
    
    public function input(): void
    {
        try{
            $this->inputs->add([
                'id'   => Tags::$FIELDS['id'],
            ]);
            $this->inputs->run();
        }
        catch(Exception $exception){
            $this->throwException(eERROR::INPUT, "Input error!", $exception);
        }
    }
    
    public function run (): void
    {
        $this->transactionFind();
        $this->transactionDelete();
        $this->output();
    }
    
    private function transactionFind(): void
    {
        try{
            $this->tag = Tags::findFirst([
                'conditions' => '(ts_state = 1) AND (id = :id:)',
                'bind' => [
                    'id' => $this->inputs->get('id'),
                ],
            ]);
        }
        catch(Exception $exception){
            $this->throwException(eERROR::TRANSACTION, "Transaction error!", $exception);
        }
        if($this->tag===null){
            $this->throwException(eERROR::NOT_FOUND, "Not found!");
        }
    }
    
    private function transactionDelete()
    {
        try{
            if(!$this->tag->delete()){
                $this->setError(eERROR::TRANSACTION, "Transaction error!");
                return;
            }
            
        }
        catch(Exception $exception){
            $this->throwException(eERROR::TRANSACTION, "Transaction error!", $exception);
        }
    }
    
    private function output()
    {
        try{
            $this->set('tag', (object)[
                'id'          => intval($this->tag->id),
                'name'        => $this->tag->name,
                'ts_created'  => Tags::TS_MODEL($this->tag->ts_created),
                'ts_modified' => Tags::TS_MODEL($this->tag->ts_modified),
                'ts_deleted'  => Tags::TS_MODEL($this->tag->ts_deleted),
            ]);
        }
        catch(Exception $exception){
            $this->throwException(eERROR::TRANSACTION, "Output error!", $exception);
        }
    }

}
