<?
use Phalcon\Mvc\Router\Group;
use Core\Provider\ROUTER;
defined("FKN") or http_response_code(403).die('Forbidden!');

$group = new Group([
    'module' => 'ADM',
    'controller' => 'Index',
]);
$group->setPrefix('/ADM/');


    $group->add('', [
        'action' => 'List',
    ]);


ROUTER::MOUNT($group);
