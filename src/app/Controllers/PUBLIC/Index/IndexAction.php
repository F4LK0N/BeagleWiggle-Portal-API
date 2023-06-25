<?
namespace App\Controllers\Index;
defined("FKN") or http_response_code(403).die('Forbidden!');
use Core\Base\Action as BaseAction;
use Core\Helper\FILTER;
use Core\Provider\DB;

class IndexAction extends BaseAction
{
    private $menuCategories   = null;
    private $newsHighlights   = null;
    private $newsTrending     = null;
    private $news             = null;
    private $categories       = null;
    
    private $newsIdList       = [];
    private $categoriesIdList = [0];
    
    
    
    public function run (): void
    {
        $this->transactionMenuCategoriesSelect();
        $this->transactionNewsHighlightsSelect();
        $this->transactionNewsTrendingSelect();
        $this->transactionNewsSelect();
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
    
    private function transactionNewsHighlightsSelect(): void
    {
        $result = DB::INSTANCE()->query("
            SELECT 
                id, id_category, url, cover_title, cover_description
            FROM
                `news`
            WHERE
                (ts_state = 1 AND published = 1)
            ORDER BY
                ts_published DESC
            LIMIT
                2
        ");
        
        $this->newsHighlights=[];
        while($row = $result->fetchArray())
        {
            $this->newsHighlights[] = [
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
                4
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
    
    private function transactionNewsSelect(): void
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
                12
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
                (id IN (".implode(',', $this->categoriesIdList)."))
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
        $this->set('newsHighlights', $this->newsHighlights);
        $this->set('newsTrending',   $this->newsTrending);
        $this->set('news',           $this->news);
        $this->set('categories',     $this->categories);
    }
}
