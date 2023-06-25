<?
namespace App\Controllers\ADM\News;
defined("FKN") or http_response_code(403).die('Forbidden!');
use Core\Base\Action as BaseAction;
use Core\Helper\FILTER;
use App\Models\News;
use eNEWS_STEP;
use Exception;
use eERROR;

class EditAction extends BaseAction
{
    private $news;
    
    public function input(): void
    {
        try{
            $this->inputs->add([
                'id'                => News::$FIELDS['id'],
                'id_category'       => News::$FIELDS['id_category'],
                
                'url'               => News::$FIELDS['url'],
                
                'cover_title'       => News::$FIELDS['cover_title'],
                'cover_description' => News::$FIELDS['cover_description'],
                
                'title'             => News::$FIELDS['title'],
                'description'       => News::$FIELDS['description'],
                'content'           => News::$FIELDS['content'],
                
                'source_name'       => News::$FIELDS['source_name'],
                'source_url'        => News::$FIELDS['source_url'],
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
        if(($_POST['source_url']??'') !== ''){
            $config = News::$FIELDS['source_name'];
            $config['required']=true;
            $this->inputs->add(['source_name' => $config]);
        }
        
        if(($_POST['url']??'') === ''){
            $_POST['url'] = ($_POST['title']??'');
        }
        
        if(($_POST['cover_title']??'') === ''){
            $_POST['cover_title'] = ($_POST['title']??'');
        }
        
        if(($_POST['cover_description']??'') === ''){
            $_POST['cover_description'] = ($_POST['description']??'');
        }
    }
    public function run (): void
    {
        $this->transactionFind();
        $this->rulesWorkflow();
        $this->transactionFindRelated();
        $this->transactionUpdate();
        $this->transactionFindUpdated();
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
        //@codeCoverageIgnoreStart
        catch(Exception $exception){
            $this->throwException(eERROR::TRANSACTION, "Transaction error!", $exception);
        }
        //@codeCoverageIgnoreEnd
        if($this->news===null){
            $this->throwException(eERROR::NOT_FOUND, "Not found!");
        }
    }
    private function rulesWorkflow()
    {
        if($this->news->step!==eNEWS_STEP::DRAFT){
            $this->setError(eERROR::RULE, "'DRAFT' step is required!");
        }
        
        $this->news->version = (intval($this->news->version)+1);
        
        if($this->hasError()){
            $this->throwException(eERROR::RULE, "Rule error!");
        }
    }
    private function transactionFindRelated()
    {
        //TODO: Search Category and Tags.
    }
    private function transactionUpdate()
    {
        try{
            $this->news->id_category =        $this->inputs->get('id_category');
            
            $this->news->url =                $this->inputs->get('url');
            
            $this->news->cover_title =        $this->inputs->get('cover_title');
            $this->news->cover_description =  $this->inputs->get('cover_description');
            
            $this->news->title =              $this->inputs->get('title');
            $this->news->description =        $this->inputs->get('description');
            $this->news->content =            $this->inputs->get('content');
            
            $this->news->source_name =        $this->inputs->get('source_name');
            $this->news->source_url =         $this->inputs->get('source_url');
            
            $this->news->save();
        }
        catch(Exception $exception){
            $this->throwException(eERROR::TRANSACTION, "Transaction error!", $exception);
        }
    }
    private function transactionFindUpdated()
    {
        try{
            $this->news = News::findFirst([
                'conditions' => '(ts_state = 1) AND (id = :id:)',
                'bind' => [
                    'id' => $this->news->id
                ],
            ]);
            //@codeCoverageIgnoreStart
            if($this->news===null){
                $this->throwException(eERROR::NOT_FOUND, "Retrieve data error!");
            }
            //@codeCoverageIgnoreEnd
        }
        //@codeCoverageIgnoreStart
        catch(Exception $exception){
            $this->throwException(eERROR::TRANSACTION, "Transaction error!", $exception);
        }
        //@codeCoverageIgnoreEnd
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
