<?
namespace Core\Base;
defined("FKN") or http_response_code(403).die('Forbidden!');

class Controller
{
    protected int     $access_type  = 0;
    protected int     $access_level = 0;
    protected ?object $action  = null;



    public function setup($action, $params=[])
    {
        $this->action = $action;
    }

    public function before(): bool
    {
        return true;
    }

    public function after(): bool
    {
        return true;
    }

}
