<?
use Phalcon\Mvc\Router\Group;
use Core\Provider\ROUTER;
defined("FKN") or http_response_code(403).die('Forbidden!');

$group = new Group([
    'module' => 'ADM',
    'controller' => 'Categories',
]);
$group->setPrefix('/ADM/Categories/');


    $group->add('', [
        'action' => 'List',
    ]);

    $group->add('View/:int', [
        'action' => 'View',
        'id' => 1,
    ]);

    $group->add('Add/', [
        'action' => 'Add',
    ]);

    $group->add('Edit/:int', [
        'action' => 'Edit',
        'id' => 1,
    ]);

    $group->add('Rem/:int', [
        'action' => 'Rem',
        'id' => 1,
    ]);


ROUTER::MOUNT($group);
