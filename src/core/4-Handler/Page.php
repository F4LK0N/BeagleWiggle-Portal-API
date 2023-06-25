<?
namespace Core;
defined("FKN") or http_response_code(403).die('Forbidden!');
use JsonSerializable;
use Exception;
use eERROR;
use Core\Helper\MATH;

class Page implements JsonSerializable
{
    static public $FIELDS = [
        'page' => [
            'type'    => 'int',
            'required'=> false,
            'default' => 1,
            'filters' => [],
            'validations' => [
                'min'=>1,
            ],
        ],
        'pageSize' => [
            'type'    => 'int',
            'required'=> false,
            'default' => 10,
            'filters' => [],
            'validations' => [
                'min'=>2,
                'max'=>100,
            ],
        ],
        //TODO: SEARCH
        'search' => [
            'type'    => 'string',
            'required'=> false,
            'filters' => [
                'name'],
            'validations' => [
                "min-length"=>3,
                "max-length"=>64],
        ],
        //TODO: ORDER
        'order' => [
            'type'    => 'string',
            'required'=> false,
            'filters' => [
                'keyword'],
            'validations' => [
                "min-length"=>3,
                "max-length"=>64],
        ],
    ];
    
    
    
    protected int   $page     = 1;
    protected int   $pageSize = 10;
    protected int   $pages    = 1;
    protected int   $total    = 0;
    public    array $items    = [];
    
    public function setInputs(Inputs &$inputs): void
    {
        $this->page = intval(MATH::MAX(1, intval($inputs->get('page'))));
        $this->pageSize = intval(MATH::BETWEEN(intval($inputs->get('pageSize')), 2, 100));
    }
    public function setTotal(int $total): void
    {
        $this->total = intval(MATH::MAX(0, intval($total)));
        $this->pages = intval(MATH::MAX(1, ceil($this->total / $this->pageSize)));
        $this->page  = intval(MATH::BETWEEN($this->page, 1, $this->pages));
    }
    
    public function getPage(): int
    {
        return $this->page;
    }
    public function getPageSize(): int
    {
        return $this->pageSize;
    }
    public function getPages(): int
    {
        return $this->pages;
    }
    public function getTotal(): int
    {
        return $this->total;
    }
    
    public function getSqlLimit(): int
    {
        return $this->pageSize;
    }
    public function getSqlOffset(): int
    {
        return (($this->page-1)*($this->pageSize));
    }
    
    public function jsonSerialize(): mixed
    {
        return [
            'page'     => $this->page,
            'pageSize' => $this->pageSize,
            'pages'    => $this->pages,
            'total'    => $this->total,
            'items'    => $this->items,
        ];
    }
}
