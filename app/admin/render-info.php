<?php
require('../debug-functions.php');
$sourceJson='../data/db.json';
$posts = json_decode(file_get_contents($sourceJson, true));
$out='';


foreach ($posts as $ii => $post) {

  $out.='<div class="post"><sup>'.$ii.'</sup>';
  foreach ($post as $key => $value) {

    // some vodoo to get rid of "Catchable fatal error: Object of class stdClass could not be converted to strin" but it is string"
    if (isset($value) && ($value != null)){
      if (($key=='image') && isset($value->url) && ($value->url != null ) ){
        $out.='<img src="'.$value->url.'"/>';
      }
      if (is_string($value)) {
        $out.= '<br><div class="'.$key.'"><small>x'.$key.' </small><span>'.$value.'</span></div>';
      }
      else {
        $out.= '<br><div class="'.$key.'"><small>x'.$key.' </small><br><span><small><pre>'.print_r($value, 1).'</pre></small></span></div>'; 
      }
    }
  }
  $out.='</div>';
}
?>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>&middot;/&middot; data viewer</title>

<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<style>
  body {
    /*padding: 2em;*/
  }
  #wrapper {
    font-size: 13px;
    margin: 1em;
  }
  small {
    opacity: .6;
  }
  div.post {
    margin: 1ex .5ex;
    line-height: 1.2;
    padding: 1ex;
    background-color: hsl(115, 0%, 98%);
    border: 1px solid hsl(115, 0%, 95%);
 }

  /* 
  @media screen and (max-width: 800px) {

  }
  @media screen and (min-width: 800px) {
    div.post {
      max-width: 48%; display: inline-block; vertical-align: top; padding: 1em;
    }
  }
  @media screen and (min-width: 1200px) {
    div.post {
      max-width: 32%; display: inline-block; vertical-align: top; padding: 1em;
    }
    #wrapper {
      margin: 0 1vw;
    }
  }
  @media screen and (min-width: 1400px) {
    div.post {
      max-width: 24%; display: inline-block; vertical-align: top; padding: 1em;
    }
    #wrapper {
      margin: 0 2vw;
    }
  }
  */
  .post div {
    overflow: hidden;
    display: inline-block;
    padding: 0 .5ex 0 1ex;
    word-wrap:break-word;
    word-break: break-all;
  }
  .post span {
    display: inline-block;
    margin-bottom: 3px;
    font-size: 12px;
  }
  .post > sup {
    position: relative;
    left: -0.5em;
    top: -0.25em;
    text-align: right;
    opacity: .8;
    color: RoyalBlue;
  }
 /* .post > sup:before {
    content: '\27E8';
  }
  .post > sup:after {
    content: '\27E9';
  }
*/  .post .url {
    color: RoyalBlue;
    background-color: WhiteSmoke;
  }
  .post .title {
    font-weight: bold;
    background-color: AntiqueWhite;
    border-top: 1px solid White;
  }
  .post img {
    width: 110px;
    height: 120px;
    object-fit: cover;
    float: right;
    margin-left: 1ex;
    border:1px solid rgba(0,0,99,.1);
    box-shadow: 1px 1px 5px 1px rgba(0,0,0,.1);
  }
  .post img:hover {
    transform: scale(4) translate(-33%);
    /*position: absolute;*/
    /*float: none;*/
    object-fit: contain;
    background-color: rgba(0,0,0,.2);
    border-color: transparent;
  }
  .post .comment span {
    font-weight: bold;
    background-color: #FFC;
   }
   .post > div.video small {
    color: Yellow;
    text-transform: uppercase;
    background-color: Red;
   }
   .post > div.video small:before {
    content:'\25B6';
  }
  nav {
    font-size: 14px;
     background-color: WhiteSmoke; display: inline-block; padding: .5ex 1.1ex; opacity: .8; 
    position: fixed; z-index: 4; right: 1em; top: 1em; 
    /*box-shadow:1px 1px 0 #EEE; */
    box-shadow: 0 0 1ex  rgba(0,0,0,.15); border: 1px solid LightYellow;
  }
  .post .image {max-width: 66%;  }
  .post pre {
    overflow: hidden;
margin-bottom: 1ex;
  }

@media (min-width: 1024px) {
  .columns4, .columns3 {
    column-count: 2;
  }
  .columns2 {
    column-count: 1;
    column-gap: 0;
  }
  .columns3, .columns4 {
    column-gap: 0;
  }
}

@media (min-width: 1260px) {
  .columns2 {
    column-count: 2;
  }
  .columns3, .columns4 {
    column-count: 3;
  }
  .columns2, .columns3, .columns4 {
    column-gap: 0;
  }
}

@media (min-width: 1600px) {
  .columns4 {
    column-count: 4;
  }
  .columns2, .columns3, .columns4 {
    column-gap: 0;
  }
}

.columns2 *, .columns3 *, .columns4 * {
  -webkit-column-break-inside: avoid;
  -moz-column-break-inside: avoid;
  -moz-page-break-inside: avoid;
  page-break-inside: avoid;
  break-inside: avoid-column;
}


</style>
</head>
<body>
  <nav>&larr; <a href="../admin">ADMIN</a> | <a href="../../">homepage</a> &rarr;</nav>
  <p style="text-align: center; margin: 1ex 0 2em 0;"><br/><mark style="padding: 1ex 1.5ex;"><b><?=$ii?></b> posts</mark></p>
<div id="wrapper" class="columns3">
<?php
echo $out;
?>

 </div></body>
</html>
