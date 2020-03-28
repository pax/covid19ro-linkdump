<?php

function getEmbed($info)
{
  $masterObj = new stdClass();
  $masterObj->title = isset($info->title) ? $info->title : null;   //The page title
  $masterObj->description = isset($info->description) ? $info->description : null;   //The page description
  $masterObj->extracted_url = isset($info->url) ? $info->url : null;   //The canonical url
  $masterObj->type = isset($info->type) ? $info->type : null;  //The page type (link, video, image, rich)
  // $masterObj->tags = isset($info->tags) ? $info->tags : null;   //The page keywords (tags) – Array
  if (isset($info->image)) {
    $masterObj->image = new stdClass();
    $masterObj->image->url = $info->image; //The image choosen as main image
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
  $masterObj->date = isset($info->publishedDate) ? date("YmdHi", strtotime($info->publishedDate)) : null;  //The published date of the resource
  return $masterObj;
}