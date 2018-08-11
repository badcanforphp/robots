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

    /**基础聊天测试**/
    'http://www.' . DOMAIN . '/im' => '/instant/index',
    /**基础聊天测试**/

    /**微信接口认证**/
    'http://www.' . DOMAIN . '/wei' => '/wei/index',
    /**微信接口认证**/


    'http://xin.fantasticskybaby.cn/' => '/default',

    'http://xin.fantasticskybaby.cn/check' => '/inter/check-ver',

    'http://xin.fantasticskybaby.cn/check-u' => '/inter/check-u',

    'http://xin.fantasticskybaby.cn/rua' => '/inter/rua',

    /**微信接口认证**/
    'http://xin.fantasticskybaby.cn/wei' => '/wei/index',
    /**微信接口认证**/


];

//return array_merge($route, $newmobileRoute);
return $route;