<?
namespace App\Controllers\ADM\Tags;
defined("FKN") or http_response_code(403).die('Forbidden!');
use Core\Base\Action as BaseAction;
use Core\Helper\MATH;
use Core\Provider\DB;
use Core\Page;
use Exception;
use eERROR;

class ListAction extends BaseAction
{
    private $page = null;
    private $tags = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->page = new Page();
    }

    public function input(): void
    {
        try{
            $this->inputs->add([
                'page'     => Page::$FIELDS['page'],
                'pageSize' => Page::$FIELDS['pageSize'],
                'search'   => Page::$FIELDS['search'],
                'order'    => Page::$FIELDS['order'],
            ]);
            $this->inputs->run();
            $this->page->setInputs($this->inputs);
        }
        catch(Exception $exception){
            $this->throwException(eERROR::INPUT, "Input error!", $exception);
        }
    }

    public function run (): void
    {
        $this->transactionCount();
        $this->transactionSelect();
        $this->output();
    }
    
    private function transactionCount(): void
    {
        $result = DB::INSTANCE()->query("
            SELECT 
                COUNT(1) as total
            FROM
                `tags`
            WHERE
                (ts_state = 1)
        ");
        $result = $result->fetchArray();
        $this->page->setTotal($result['total']);
    }

    private function transactionSelect(): void
    {
        $limit  = $this->page->getSqlLimit();
        $offset = $this->page->getSqlOffset();
        
        $result = DB::INSTANCE()->query("
            SELECT 
                id, name
            FROM
                `tags`
            WHERE
                (ts_state = 1)
            ORDER BY
                name ASC
            LIMIT
                $limit
            OFFSET
                $offset
        ");
        
        while($row = $result->fetchArray())
        {
            $this->page->items[] = [
                'id'   => intval($row['id']),
                'name' => $row['name'],
            ];
        }
    }
    
    private function output(): void
    {
        $this->set('tags', $this->page);
    }

}
