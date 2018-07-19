<?php

require 'phpQuery/phpQuery.php';

$localHost = 'http://www.qipai.dev/';
$remoteHost = 'http://apt.thebigboss.org/onepackage.php?bundleid=com.fantasticskybaby.xin';
//for ($i = 1; $i <= 10; $i++) {
    $url = $remoteHost;
    phpQuery::newDocumentFile($url);
    $content = pq('#container')->document->textContent;echo(str_replace(strstr($content,'Maintainer'),'',strstr($content,'Version')));die;
    foreach ($content as $row) {
        $goto_url = pq($row)->find('a:first')->attr('href');
        $img = pq($row)->find('a:first')->find('img');
        $title = pq($img)->attr('title');
        $header = pq($img)->attr('src');

        $city = pq($row)->find('.msg')->text();
        $city = pq($row)->find('.msg span:first')->text();
        $city = str_replace('地区：', '', $city);
        $focus = pq($row)->find('.msg span:last')->text();
        $focus = str_replace('人关注', '', $focus);


        phpQuery::newDocumentFile($goto_url);
        $typeName = pq('.number p:first')->find('.td_2')->text();
        if (!in_array($typeName, ['微信群', '个人号'])) {
            continue;
        }
        $header = curlPost($localHost . 'collect/save-header', ['url' => $header]);
        $header = $header['basename'];
        $qrcode = pq('.dtlB_left')->find('img')->attr('src');
        $qrcode = curlPost($localHost . 'collect/save-header', ['url' => $qrcode]);
        $qrcode = $qrcode['basename'];

        $cateName = pq('.class .td_2')->text();
        $createdAt = strtotime(pq('.properts:eq(2)')->find('.td_2')->text());
        $wxAccount = pq('.base_msg dd')->find('span:eq(5)')->text();
        $wxAccount = str_replace('群主微信号：', '', $wxAccount);
        $content = pq('.reduceCnt')->html();
        $imgs = pq('.reduceCnt')->find('img');
        foreach ($imgs as $img) {
            $imgUrl = pq($img)->attr('src');
            if (check_url($imgUrl)) {
                $tmp = curlPost($localHost . 'collect/save-content', ['url' => $imgUrl]);
                $content = str_replace($imgUrl, $tmp['url'], $content);
            }
        }

        $data = [
            'title' => $title,
            'header' => $header,
            'city' => $city,
            'focus' => $focus,
            'qr_code' => $qrcode,
            'cate_name' => $cateName,
            'created_at' => $createdAt,
            'wx_account' => $wxAccount,
            'content' => $content
        ];

        if ($typeName == '微信群') {
            $result = curlPost($localHost . 'collect/save-group', $data);
        } else {
            $result = curlPost($localHost . 'collect/save-person', $data);
        }
        file_put_contents('result.log', print_r($result, true) . PHP_EOL, FILE_APPEND);
//    }
}

function curlPost($uri, $data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $uri);//地址
    curl_setopt($ch, CURLOPT_POST, 1);//请求方式为post
    curl_setopt($ch, CURLOPT_HEADER, 0);//不打印header信息
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//返回结果转成字符串
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//post传输的数据。
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result, true);
}

function check_url($url)
{
    if (!preg_match('/http:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$url)) {
        return false;
    }

    return true;
}