<?
namespace App\Controllers\ADM\Categories;
defined("FKN") or http_response_code(403).die('Forbidden!');
use Core\Base\Action as BaseAction;
use App\Models\Categories;
use eERROR;
use Exception;

class ViewAction extends BaseAction
{
    private $category;
    
    public function input(): void
    {
        try{
            $this->inputs->add([
                'id'   => Categories::$FIELDS['id'],
            ]);
            $this->inputs->run();
        }
        //@codeCoverageIgnoreStart
        catch(Exception $exception){
            $this->throwException(eERROR::INPUT, "Input error!", $exception);
        }
        //@codeCoverageIgnoreEnd
    }

    public function run (): void
    {
        $this->transactionFind();
        $this->output();
    }
    private function transactionFind(): void
    {
        try{
            $this->category = Categories::findFirst([
                'conditions' => '(ts_state = 1) AND (id = :id:)',
                'bind' => [
                    'id' => $this->inputs->get('id'),
                ],
            ]);
        }
        //@codeCoverageIgnoreStart
        catch(Exception $exception){
            $this->throwException(eERROR::TRANSACTION, "Transaction error!", $exception);
        }
        //@codeCoverageIgnoreEnd
        if($this->category===null){
            $this->throwException(eERROR::NOT_FOUND, "Not found!");
        }
    }
    private function output(): void
    {
        try{
            $this->set('category', (object)[
                'id'          => intval($this->category->id),
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
