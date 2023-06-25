<?
use Phalcon\Mvc\Router\Group;
use Core\Provider\ROUTER;
defined("FKN") or http_response_code(403).die('Forbidden!');

$group = new Group([
    'controller' => 'News',
]);
$group->setPrefix('/News/');


    $group->add('', [
        'action' => 'List',
    ]);

    $group->add('View/', [
        'action' => 'View',
    ]);


ROUTER::MOUNT($group);
