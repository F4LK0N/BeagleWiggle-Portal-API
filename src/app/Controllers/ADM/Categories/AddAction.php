<?
namespace App\Controllers\ADM\Categories;
defined("FKN") or http_response_code(403).die('Forbidden!');
use Core\Base\Action as BaseAction;
use App\Models\Categories;
use Exception;
use eERROR;

class AddAction extends BaseAction
{
    private $category;
    
    public function input(): void
    {
        try{
            $this->inputs->add([
                'url'  => Categories::$FIELDS['url'],
                'name' => Categories::$FIELDS['name'],
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
        $this->transactionInsert();
        $this->transactionRefresh();
        $this->output();
    }
    private function transactionInsert()
    {
        try{
            $this->category       = new Categories();
            $this->category->url  = $this->inputs->get('url');
            $this->category->name = $this->inputs->get('name');
            $this->category->save();
        }
        catch(Exception $exception){
            $this->throwException(eERROR::TRANSACTION, "Transaction error!", $exception);
        }
    }
    private function transactionRefresh()
    {
        try{
            $this->category = Categories::findFirst([
                'conditions' => '(ts_state = 1) AND (id = :id:)',
                'bind' => [
                    'id' => $this->category->id
                ],
            ]);
        }
        //@codeCoverageIgnoreStart
        catch(Exception $exception){
            $this->throwException(eERROR::TRANSACTION, "Transaction error!", $exception);
        }
        if($this->category===null){
            $this->throwException(eERROR::NOT_FOUND, "New register not found!");
        }
        //@codeCoverageIgnoreEnd
    }
    private function output()
    {
        try{
            $this->set('category', (object)[
                'id'          => intval($this->category->id),
                'url'         => $this->category->url,
                'name'        => $this->category->name,
                'ts_created'  => Categories::TS_MODEL($this->category->ts_created),
                'ts_modified' => Categories::TS_MODEL($this->category->ts_modified),
            ]);
        }
        //@codeCoverageIgnoreStart
        catch(Exception $exception){
            $this->throwException(eERROR::TRANSACTION, "Output error!", $exception);
        }
        //@codeCoverageIgnoreEnd
    }

}
