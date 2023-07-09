<?
use Phalcon\Mvc\Router\Group;
use Core\Provider\ROUTER;
defined("FKN") or http_response_code(403).die('Forbidden!');

/** @var Group $group */
$group = new Group([
    'controller' => 'Index',
]);
$group->setPrefix('/');


    $group->add('', [
        'action' => 'Index',
    ]);

    $group->add('NotFound', [
        'action' => 'NotFound',
    ]);

    $group->add('Forbidden', [
        'action' => 'Index',
    ]);


ROUTER::MOUNT($group);
