<?
namespace App\Models;
defined("FKN") or http_response_code(403).die('Forbidden!');
use Core\Base\Model as ModelBase;

class News extends ModelBase
{
    static public $FIELDS = [
        'id' => [
            'type'=>'int',
            'required' => true,
            'filters' => [
            ],
            'validations' => [
                'min'=>1
            ],
        ],
        'id_category' => [
            'type'=>'int',
            'required' => true,
            'filters' => [
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
                "min-length"=>6,
                "max-length"=>128,
            ],
        ],
        
        
        'cover_title' => [
            'type'=>'string',
            'required' => false,
            'filters' => [
                'name'
            ],
            'validations' => [
                "min-length"=>6,
                "max-length"=>128,
            ],
        ],
        'cover_description' => [
            'type'=>'string',
            'required' => false,
            'filters' => [
                'name'
            ],
            'validations' => [
                "min-length"=>6,
                "max-length"=>255,
            ],
        ],
        
        
        'title' => [
            'type'=>'string',
            'required' => true,
            'filters' => [
                'name'
            ],
            'validations' => [
                "min-length"=>3,
                "max-length"=>255,
            ],
        ],
        'description' => [
            'type'=>'string',
            'required' => false,
            'filters' => [
                'name'
            ],
            'validations' => [
                "min-length"=>6,
                "max-length"=>512,
            ],
        ],
        'content' => [
            'type'=>'string',
            'required' => false,
            'filters' => [
                'text'
            ],
            'validations' => [
                "min-length"=>12,
                "max-length"=>50000
            ],
        ],
        
        
        'source_name' => [
            'type'=>'string',
            'required' => false,
            'filters' => [
                'name'
            ],
            'validations' => [
                "min-length"=>2,
                "max-length"=>64,
            ],
        ],
        'source_url' => [
            'type'=>'string',
            'required' => false,
            'filters' => [
                'url'
            ],
            'validations' => [
                "min-length"=>6,
                "max-length"=>512,
            ],
        ],
    ];
    
    public $id;
    public $id_category;
    
    public $published;
    public $step;
    public $version;
    
    public $url;
    
    public $cover_title;
    public $cover_description;
    
    public $title;
    public $description;
    public $content;
    
    public $source_name;
    public $source_url;
    
    public $ts_state;
    public $ts_created;
    public $ts_modified;
    public $ts_published;
    public $ts_deleted;



    /** @return News|null */
    public static function findFirst($parameters = null)
    {
        $result = parent::findFirst($parameters);
        
        if($result!==null){
            $result->id                = intval($result->id);
            $result->id_category       = intval($result->id_category);
            
            $result->published         = intval($result->published);
            $result->step              = intval($result->step);
            $result->version           = intval($result->version);
            
            $result->ts_state          = intval($result->ts_state);
            
            $result->url               = strval($result->url);
            
            $result->cover_title       = strval($result->cover_title);
            $result->cover_description = strval($result->cover_description);
            
            $result->title             = strval($result->title);
            $result->description       = strval($result->description);
            $result->content           = strval($result->content);
            
            $result->source_name       = strval($result->source_name);
            $result->source_url        = strval($result->source_url);
        }
        
        return $result;
    }

}
