<?php
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
    echo '<pre>' . htmlspecialchars(print_r($array, true), ENT_IGNORE) . '</pre>';
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
    echo '<pre>' . htmlspecialchars($code, ENT_IGNORE) . '</pre>';
  }
}