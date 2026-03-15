<?php


if (session_status() === PHP_SESSION_NONE) {
    $secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', $secure ? 1 : 0);
    ini_set('session.cookie_samesite', 'Lax');

    session_start();
}