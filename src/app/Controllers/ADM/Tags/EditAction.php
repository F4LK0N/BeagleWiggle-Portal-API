<?
namespace App\Controllers\ADM\Tags;
defined("FKN") or http_response_code(403).die('Forbidden!');
use Core\Base\Action as BaseAction;
use App\Models\Tags;
use Exception;
use eERROR;

class EditAction extends BaseAction
{
    private $tag;
    
    public function input(): void
    {
        try{
            $this->inputs->add([
                'id'   => Tags::$FIELDS['id'],
                'url'  => Tags::$FIELDS['url'],
                'name' => Tags::$FIELDS['name'],
            ]);
            $this->inputsAdjust();
            $this->inputs->run();
        }
        catch(Exception $exception){
            $this->throwException(eERROR::INPUT, "Input error!", $exception);
        }
    }
    private function inputsAdjust()
    {
        if(($_POST['url']??'') === ''){
            $_POST['url'] = ($_POST['name']??'');
        }
    }

    public function run (): void
    {
        $this->transactionFind();
        $this->transactionUpdate();
        $this->transactionRefresh();
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
    private function transactionUpdate(): void
    {
        try{
            $this->tag->url  = $this->inputs->get('url');
            $this->tag->name = $this->inputs->get('name');
            $this->tag->save();
        }
        catch(Exception $exception){
            $this->throwException(eERROR::TRANSACTION, "Transaction error!", $exception);
        } 
    }
    private function transactionRefresh()
    {
        try{
            $this->tag = Tags::findFirst([
                'conditions' => '(ts_state = 1) AND (id = :id:)',
                'bind' => [
                    'id' => $this->tag->id
                ],
            ]);
        }
        catch(Exception $exception){
            $this->throwException(eERROR::TRANSACTION, "Transaction error!", $exception);
        }
        if($this->tag===null){
            $this->throwException(eERROR::NOT_FOUND, "Refresh register not found!");
        }
    }
    private function output()
    {
        try{
            $this->set('tag', (object)[
                'id'          => intval($this->tag->id),
                'url'         => $this->tag->url,
                'name'        => $this->tag->name,
                'ts_created'  => Tags::TS_MODEL($this->tag->ts_created),
                'ts_modified' => Tags::TS_MODEL($this->tag->ts_modified),
            ]);
        }
        catch(Exception $exception){
            $this->throwException(eERROR::TRANSACTION, "Output error!", $exception);
        }
    }

}
