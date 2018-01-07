<?php

if (!function_exists('auth_link_hourly')) {
    function auth_link_hourly($link, $user) {
        return FastAuthLink::hourly($link, $user);
    }
}

if (!function_exists('auth_link_daily')) {
    function auth_link_daily($link, $user) {
        return FastAuthLink::daily($link, $user);
    }
}

if (!function_exists('auth_link_monthly')) {
    function auth_link_monthly($link, $user) {
        return FastAuthLink::monthly($link, $user);
    }
}