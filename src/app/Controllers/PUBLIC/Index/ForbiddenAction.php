<?
namespace App\Controllers\Index;
use Core\Base\Action as BaseAction;
use eERROR;

defined("FKN") or http_response_code(403).die('Forbidden!');

class ForbiddenAction extends BaseAction
{
    public function run ()
    {
        http_response_code(403);
        $this->result->setError(
            eERROR::NOT_FOUND,
            "Forbidden resource! Please check your credentials!"
        );
    }

}
