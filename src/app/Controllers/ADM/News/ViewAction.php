<?
namespace App\Controllers\ADM\News;
defined("FKN") or http_response_code(403).die('Forbidden!');
use Core\Base\Action as BaseAction;
use App\Models\News;
use eNEWS_STEP;
use eERROR;
use Exception;

class ViewAction extends BaseAction
{
    private $news;
    
    public function input(): void
    {
        try{
            $this->inputs->add([
                'id'   => News::$FIELDS['id'],
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
        $this->transactionFindRelated();
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
    private function transactionFindRelated()
    {
        //TODO: Search Category and Tags.
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
            ]);
        }
        //@codeCoverageIgnoreStart
        catch(Exception $exception){
            $this->throwException(eERROR::TRANSACTION, "Output error!", $exception);
        }
        //@codeCoverageIgnoreEnd
    }

}
