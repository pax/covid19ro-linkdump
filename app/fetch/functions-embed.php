<?php


$providerData = [
    'title' => 'printText',
    'description' => 'printText',
    'url' => 'printUrl',
    'type' => 'printText',
    'tags' => 'printArray',
    'imagesUrls' => 'printArray',
    'code' => 'printCode',
    'feeds' => 'printArray',
    'width' => 'printText',
    'height' => 'printText',
    'authorName' => 'printText',
    'authorUrl' => 'printUrl',
    'providerIconsUrls' => 'printArray',
    'providerName' => 'printText',
    'providerUrl' => 'printUrl',
    'publishedTime' => 'printText',
    'license' => 'printUrl',
];

$adapterData = [
    'title' => 'printText',
    'description' => 'printText',
    'url' => 'printUrl',
    'type' => 'printText',
    'tags' => 'printArray',
    'image' => 'printImage',
    'imageWidth' => 'printText',
    'imageHeight' => 'printText',
    'images' => 'printArray',
    'code' => 'printCode',
    'feeds' => 'printArray',
    'width' => 'printText',
    'height' => 'printText',
    'aspectRatio' => 'printText',
    'authorName' => 'printText',
    'authorUrl' => 'printUrl',
    'providerIcon' => 'printImage',
    'providerIcons' => 'printArray',
    'providerName' => 'printText',
    'providerUrl' => 'printUrl',
    'publishedTime' => 'printText',
    'license' => 'printUrl',
];

function getEmbed ($info){
    $masterObj = new stdClass();
    $masterObj->title = isset($info->title) ? $info->title : null;   //The page title
    $masterObj->description = isset($info->description) ? $info->description : null;   //The page description
    $masterObj->extracted_url = isset($info->url) ? $info->url : null;   //The canonical url
    $masterObj->type = isset($info->type) ? $info->type : null;  //The page type (link, video, image, rich)
    // $masterObj->tags = isset($info->tags) ? $info->tags : null;   //The page keywords (tags) – Array
    if (isset($info->image)) {
        $masterObj->image = new stdClass();
        $masterObj->image->url = $info->image ; //The image choosen as main image
        $masterObj->image->width = isset($info->imageWidth) ? $info->imageWidth : null; //The width of the main image
        $masterObj->image->height = isset($info->imageHeight) ? $info->imageHeight : null; //The height of the main image
    }

    //    VIDEO??

    // if ( isset($info->code)){
    //     $masterObj->embd_code = $info->code;
    //     $masterObj->embd_code_width = $info->width;
    //     $masterObj->embd_code_height = $info->height;
    //     $masterObj->embd_code_aspectRatio = $info->aspectRatio;
    // }

    $masterObj->publisher = isset($info->providerName) ? $info->providerName : null; //The provider name of the page (Youtube, Twitter, Instagram, etc)
    // $masterObj->publisher = isset($info->providerUrl) ? $info->providerUrl : null;    //The provider url
    $masterObj->favicon = isset($info->providerIcon) ? $info->providerIcon : null;    //The icon choosen as main icon
    $masterObj->date = isset($info->publishedDate) ? date("YmdHi",strtotime($info->publishedDate)) : null;  //The published date of the resource
    return $masterObj;
}

function getUrl()
{
    if (!isset($_GET['url'])) {
        return '';
    }

    $url = $_GET['url'];

    //fix for unescaped urls
    foreach ($_GET as $name => $value) {
        if ($name === 'url') {
            continue;
        }

        $url .= "&{$name}={$value}";
    }

    return $url;
}

function getEscapedUrl()
{
    return htmlspecialchars(getUrl(), ENT_QUOTES, 'UTF-8');
}

function printAny($text)
{
    if (is_array($text)) {
        printArray($text);
    } else {
        printText($text);
    }
}

function printText($text)
{
    echo htmlspecialchars($text, ENT_IGNORE);
}

function printImage($image)
{
    if ($image) {
        echo <<<EOT
        <img src="{$image}"><br>
EOT;
        printUrl($image);
    }
}

function printUrl($url)
{
    if ($url) {
        echo <<<EOT
        <a href="{$url}" target="_blank">Open (new window)</a> | {$url}
EOT;
    }
}

function printArray($array)
{
    if ($array) {
        echo '<pre>'.htmlspecialchars(print_r($array, true), ENT_IGNORE).'</pre>';
    }
}

function printHeaders($array)
{
    $headers = [];

    foreach ($array as $name => $values) {
        $headers[$name] = implode(', ', $values);
    }

    printArray($headers);
}

function printCode($code, $asHtml = true)
{
    if ($asHtml) {
        echo $code;
    }

    if ($code) {
        echo '<pre>'.htmlspecialchars($code, ENT_IGNORE).'</pre>';
    }
}