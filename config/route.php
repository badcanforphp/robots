<?php
/**
 * Created by PhpStorm.
 * User: chenyi
 * Date: 2017/2/16
 * Time: 下午4:16
 */

//$mobileRoute = require(__DIR__.'/mobile.route.php');
//$newmobileRoute = require(__DIR__.'/newmobile.route.php');

$route =  [
    'http://www.' . DOMAIN . '/' => '/default/',

    'http://www.' . DOMAIN . '/check' => '/inter/check-ver',

    'http://www.' . DOMAIN . '/check-u' => '/inter/check-u',

    'http://www.' . DOMAIN . '/rua' => '/inter/rua',



    'http://xin.fantasticskybaby.cn/' => '/default',

    'http://xin.fantasticskybaby.cn/check' => '/inter/check-ver',

    'http://xin.fantasticskybaby.cn/check-u' => '/inter/check-u',

    'http://xin.fantasticskybaby.cn/rua' => '/inter/rua',


];

//return array_merge($route, $newmobileRoute);
return $route;