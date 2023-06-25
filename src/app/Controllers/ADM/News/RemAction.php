<?
namespace App\Controllers\ADM\News;
defined("FKN") or http_response_code(403).die('Forbidden!');
use Core\Base\Action as BaseAction;
use Core\Helper\SECURITY;
use App\Models\News;
use eNEWS_STEP;
use Exception;
use eERROR;

class RemAction extends BaseAction
{
    private $news;
    
    public function input(): void
    {
        try{
            $this->inputs->add([
                'id' => News::$FIELDS['id'],
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
        $this->rulesWorkflow();
        $this->transactionUpdate();
        $this->transactionDelete();
        $this->output();
    }
    private function transactionFind()
    {
        try{
            $this->news = News::findFirst([
                'conditions' => '(ts_state = 1) AND (id = :id:)',
                'bind' => [
                    'id' => $this->inputs->get('id')
                ],
            ]);
        }
        catch(Exception $exception){
            $this->throwException(eERROR::TRANSACTION, "Transaction error!", $exception);
        }
        if($this->news===null){
            $this->throwException(eERROR::NOT_FOUND, "Not found!");
        }
    }
    private function rulesWorkflow()
    {
        if($this->news->step!==eNEWS_STEP::DRAFT){
            $this->setError(eERROR::RULE, "Invalid action for current step!");
        }
        
        if($this->hasError()){
            $this->throwException(eERROR::RULE, "Rule error!");
        }
    }
    private function transactionUpdate()
    {
        try{
            $token=SECURITY::TOKEN();
            $this->news->url         = substr($this->news->url." [DELETED] ".$token, 0, 128);
            $this->news->title       = substr($this->news->title." [DELETED] ".$token, 0, 255);
            $this->news->cover_title = substr($this->news->cover_title." [DELETED] ".$token, 0, 128);
            
            $this->news->save();
        }
        catch(Exception $exception){
            $this->throwException(eERROR::TRANSACTION, "Transaction error!", $exception);
        }
    }
    private function transactionDelete()
    {
        try{
            if(!$this->news->delete()){
                $this->throwException(eERROR::TRANSACTION, "Delete error!");
            }
        }
        catch(Exception $exception){
            $this->throwException(eERROR::TRANSACTION, "Transaction error!", $exception);
        }
    }
    private function output()
    {
        try{
            $this->set('news', (object)[
                'id'                => intval($this->news->id),
                'id_category'       => intval($this->news->id_category),
                
                'published'         => intval($this->news->published),
                'step'              => intval($this->news->step),
                'version'           => intval($this->news->version),
                
                'url'               => $this->news->url,
                
                'cover_title'       => $this->news->cover_title,
                'cover_description' => $this->news->cover_description,
                
                'title'             => $this->news->title,
                'description'       => $this->news->description,
                'content'           => $this->news->content,
                
                'source_name'       => $this->news->source_name,
                'source_url'        => $this->news->source_url,
                
                'ts_created'        => News::TS_MODEL($this->news->ts_created),
                'ts_modified'       => News::TS_MODEL($this->news->ts_modified),
                'ts_published'      => News::TS_MODEL($this->news->ts_published),
                'ts_deleted'        => News::TS_MODEL($this->news->ts_deleted),
            ]);
        }
        //@codeCoverageIgnoreStart
        catch(Exception $exception){
            $this->throwException(eERROR::TRANSACTION, "Output error!", $exception);
        }
        //@codeCoverageIgnoreEnd
    }

}
