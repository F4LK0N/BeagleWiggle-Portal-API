<?
use Phalcon\Mvc\Router\Group;
use Core\Provider\ROUTER;
defined("FKN") or http_response_code(403).die('Forbidden!');

$group = new Group([
    'controller' => 'Auth',
]);
$group->setPrefix('/Auth/');


    $group->add('Login', [
        'action' => 'Login',
    ]);

    $group->add('Logout', [
        'action' => 'Logout',
    ]);

    $group->add('Register', [
        'action' => 'Register',
    ]);

    $group->add('Activate', [
        'action' => 'Activate',
    ]);

    $group->add('ForgetPassword', [
        'action' => 'ForgetPassword',
    ]);
    
    $group->add('ResetPassword', [
        'action' => 'ResetPassword',
    ]);


ROUTER::MOUNT($group);
