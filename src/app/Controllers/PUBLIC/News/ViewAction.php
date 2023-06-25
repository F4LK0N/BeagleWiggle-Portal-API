<?
namespace App\Controllers\News;
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
    private $newsRelated      = null;
    private $newsTrending     = null;
    private $categories       = null;
    
    private $newsIdList       = [];
    private $categoriesIdList = [];
    
    
    
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
                'url' => [
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
        $this->transactionFind();
        $this->transactionNewsRelatedSelect();
        $this->transactionNewsTrendingSelect();
        $this->transactionCategoriesSelect();
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
    private function newsIdListWhereNotIn(): string
    {
        if(count($this->newsIdList)===0){
            return '';
        }
        
        return ' AND id NOT IN ('. implode(',', $this->newsIdList) .') ';
    }
    private function transactionFind()
    {
        try{
            $result = DB::INSTANCE()->query("
                SELECT 
                    id, id_category, url, title, description, content, source_name, source_url, ts_published
                FROM
                    `news`
                WHERE
                    (ts_state = 1) AND (published = 1) AND (url = '".$this->inputs->get('url')."')
                LIMIT
                    1
            ");
            
            while($row = $result->fetchArray())
            {
                $this->news = [
                    'id'                => intval($row['id']),
                    'id_category'       => intval($row['id_category']),
                    'url'               => $row['url'],
                    'url_category'      => $this->inputs->get('url_category'),
                    
                    'title'             => $row['title'],
                    'description'       => $row['description'],
                    'content'           => $row['content'],
                    
                    'source_name'       => $row['source_name'],
                    'source_url'        => $row['source_url'],
                    
                    'ts_published'      => $row['ts_published'],
                ];
                $this->newsIdList[] = $row['id'];
                $this->categoriesIdList[] = $row['id_category'];
                break;
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
    private function transactionNewsRelatedSelect(): void
    {
        $result = DB::INSTANCE()->query("
            SELECT 
                id, id_category, url, cover_title, cover_description
            FROM
                `news`
            WHERE
                (
                    (ts_state = 1)
                    AND
                    (published = 1) 
                    AND 
                    (id_category = ".$this->news['id_category'].")
                    
                    ".$this->newsIdListWhereNotIn()."
                )
            ORDER BY
                ts_published DESC
            LIMIT
                4
        ");
        
        $this->newsRelated=[];
        while($row = $result->fetchArray())
        {
            $this->newsRelated[] = [
                'id'                => intval($row['id']),
                'id_category'       => intval($row['id_category']),
                'url'               => $row['url'],
                'cover_title'       => $row['cover_title'],
                'cover_description' => $row['cover_description'],
            ];
            $this->newsIdList[] = $row['id'];
            $this->categoriesIdList[] = $row['id_category'];
        }
    }
    private function transactionNewsTrendingSelect(): void
    {
        $result = DB::INSTANCE()->query("
            SELECT 
                id, id_category, url, cover_title, cover_description
            FROM
                `news`
            WHERE
                (ts_state = 1 AND published = 1 ".$this->newsIdListWhereNotIn().")
            ORDER BY
                ts_published DESC
            LIMIT
                6
        ");
        
        $this->newsTrending=[];
        while($row = $result->fetchArray())
        {
            $this->newsTrending[] = [
                'id'                => intval($row['id']),
                'id_category'       => intval($row['id_category']),
                'url'               => $row['url'],
                'cover_title'       => $row['cover_title'],
                'cover_description' => $row['cover_description'],
            ];
            $this->newsIdList[] = $row['id'];
            $this->categoriesIdList[] = $row['id_category'];
        }
    }
    private function transactionCategoriesSelect(): void
    {
        $result = DB::INSTANCE()->query("
            SELECT 
                id, url, name
            FROM
                `categories`
            WHERE
                (id IN (0,".implode(',', $this->categoriesIdList)."))
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
    private function output(): void
    {
        $this->set('menuCategories', $this->menuCategories);
        $this->set('news',           $this->news);
        $this->set('newsRelated',    $this->newsRelated);
        $this->set('newsTrending',   $this->newsTrending);
        $this->set('categories',     $this->categories);
    }
}
