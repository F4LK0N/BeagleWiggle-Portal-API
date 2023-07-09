<?
namespace App\Controllers\Categories;
defined("FKN") or http_response_code(403).die('Forbidden!');
use Core\Base\Action as BaseAction;
use Core\Helper\FILTER;
use Core\Provider\DB;
use Exception;
use eERROR;
use App\Models\News;

class ViewAction extends BaseAction
{
    private $menuCategories   = null;
    private $news             = null;
    private $categories       = null;
    
    
    
    public function input(): void
    {
        try{
            $this->inputs->add([
                'url_category' => [
                    'type'=>'string',
                    'required' => true,
                    'filters' => [
                        'url'
                    ],
                    'validations' => [
                        "min-length"=>6,
                        "max-length"=>128,
                    ],
                ],
            ]);
            $this->inputs->run();
        }
        catch(Exception $exception){
            $this->throwException(eERROR::INPUT, "Input error!", $exception);
        }
    }
    
    public function run (): void
    {
        $this->transactionMenuCategoriesSelect();
        $this->transactionCategoryFind();
        $this->transactionFind();
        $this->output();
    }
    private function transactionMenuCategoriesSelect(): void
    {
        $result = DB::INSTANCE()->query("
            SELECT 
                url, name
            FROM
                `categories`
            WHERE
                (ts_state = 1)
            ORDER BY
                name ASC
            LIMIT
                10
        ");
        
        $this->menuCategories=[];
        while($row = $result->fetchArray())
        {
            $this->menuCategories[] = [
                'url'  => $row['url'],
                'name' => $row['name'],
            ];
        }
    }
    private function transactionCategoryFind(): void
    {
        $result = DB::INSTANCE()->query("
            SELECT 
                id, url, name
            FROM
                `categories`
            WHERE
                (url = '".$this->inputs->get('url_category')."')
            LIMIT
                1
        ");
        
        $this->categories=[];
        while($row = $result->fetchArray())
        {
            $this->categories[] = [
                'id'   => intval($row['id']),
                'url'  => $row['url'],
                'name' => $row['name'],
            ];
        }
    }
    private function transactionFind()
    {
        try{
            $result = DB::INSTANCE()->query("
                SELECT 
                id, id_category, url, cover_title, cover_description
                FROM
                    `news`
                WHERE
                    (ts_state = 1)
                    AND
                    (published = 1)
                    AND
                    (id_category = '".$this->categories[0]['id']."')
                ORDER BY
                    ts_published DESC
                LIMIT
                    10
            ");
            
            $this->news=[];
            while($row = $result->fetchArray())
            {
                $this->news[] = [
                    'id'                => intval($row['id']),
                    'id_category'       => intval($row['id_category']),
                    'url'               => $row['url'],
                    'cover_title'       => $row['cover_title'],
                    'cover_description' => $row['cover_description'],
                ];
            }
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
    
    private function output(): void
    {
        $this->set('menuCategories', $this->menuCategories);
        $this->set('news',           $this->news);
        $this->set('categories',     $this->categories);
    }
}
