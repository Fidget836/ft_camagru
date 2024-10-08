<?php

    session_start([
        'cookie_lifetime' => 86400,
        'cookie_secure' => true,
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
    ]);

    $response = [
        'loggedIn' => isset($_SESSION['user_id']),
        'username' => isset($_SESSION['username']) ? $_SESSION['username'] : '',
        'user_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null,
        'notification' => isset($_SESSION['notification'])
    ];

    echo json_encode($response);
    
?>