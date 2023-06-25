<? defined("FKN") or http_response_code(403).die('Forbidden!');

use Phalcon\Mvc\Model;
use Phalcon\Db\Column;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Mvc\Model\Resultset;

class Auth extends _ModelBase
{
    public $email;
    public $pass;

}
