<? defined("FKN") or http_response_code(403).die('Forbidden!');

return [
    'host'     => 'sql',
    'port'     => '3306',
    
    'username' => 'app',
    'password' => 'pass',
    
    'dbname'   => 'app',
];
