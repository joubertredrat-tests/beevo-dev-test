<?php

require __DIR__ . '/../bootstrap.php';


// instantiate session handler
\App\Session\SessionHandler::register('files');

$_SESSION['foo'] = 'bar'; // should write on file

var_dump($_SESSION['foo']); // should return 'bar' from written file
