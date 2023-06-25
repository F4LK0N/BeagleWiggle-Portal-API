<?
namespace App\Models;
defined("FKN") or http_response_code(403).die('Forbidden!');
use Core\Base\Model as ModelBase;

class Categories extends ModelBase
{
    static public $FIELDS = [
        'id' => [
            'type'     => 'int',
            'required' => true,
            'filters'  => [
                
            ],
            'validations' => [
                'min'=>1
            ],
        ],
        'url' => [
            'type'=>'string',
            'required' => false,
            'filters' => [
                'url'
            ],
            'validations' => [
                "min-length"=>3,
                "max-length"=>128,
            ],
        ],
        'name' => [
            'type'     => 'string',
            'required' => true,
            'filters'  => [
                'name'
            ],
            'validations' => [
                "min-length"=>3,
                "max-length"=>128,
            ],
        ],
    ];
    
    public $id;
    public $url;
    public $name;

    public $ts_state;
    public $ts_created;
    public $ts_modified;
    public $ts_deleted;



    /** @return Categories|null */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
