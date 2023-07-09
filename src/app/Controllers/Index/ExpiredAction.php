<?
namespace App\Controllers\Index;
use Core\Base\Action as BaseAction;
use eERROR;

defined("FKN") or http_response_code(403).die('Forbidden!');

class ExpiredAction extends BaseAction
{
    public function run ()
    {
        http_response_code(498);
        $this->result->setError(
            eERROR::NOT_FOUND,
            "Expired resource! Please check your credentials!"
        );
    }

}
