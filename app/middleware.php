<?php

$index = function($request, $response, $next) {
    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        return $response->withStatus(302)->withHeader('Location', '/login');
    } else if ($_SESSION['id'] == 'admin') {
        return $response->withStatus(302)->withHeader('Location', '/pedidos');
    } else if (isset($_SESSION['id']) || !empty($_SESSION['id'])) {
        return $response->withStatus(302)->withHeader('Location', '/menu');        
    }
};

$debeLoggearse = function($request, $response, $next) {
    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        return $response->withStatus(302)->withHeader('Location', '/login');
    }

    return $next($request, $response);
};

$noDebeLoggearse = function($request, $response, $next) {
    if (isset($_SESSION['id']) || !empty($_SESSION['id'])) {
        return $response->withStatus(302)->withHeader('Location', '/');
    }

    return $next($request, $response);
};

$debeSerAdmin = function($request, $response, $next) {
    if ($_SESSION['id'] != 'admin') {
        return $response->withStatus(302)->withHeader('Location', '/');
    }

    return $next($request, $response);
};


?>