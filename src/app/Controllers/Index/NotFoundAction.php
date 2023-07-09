<?
namespace App\Controllers\Index;
use Core\Base\Action as BaseAction;
use eERROR;

defined("FKN") or http_response_code(403).die('Forbidden!');

class NotFoundAction extends BaseAction
{
    public function run (): void
    {
        http_response_code(404);
        $this->set("API", "1.0");
        $this->setError(
            eERROR::NOT_FOUND,
            "The requested URL was not found on this server."
        );
    }
}
