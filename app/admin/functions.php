<?php
/*

FUNCTIONS INDEX       DESCRIPTION
  txtFileToArr        : reads txt file, load each row to array element
  linkify()           : convert urls in text to links
  extract_urls()      : extract urls from a string
  UrlGetContentsCurl  : php implementation of cURL [OBSOLETE]
  myCurl:             : php implementation of cURL
  _format_json        : pretty print JSON string
  fetchEmbedData      : fetches structured link data from 3rd party service API
  refactorApiJson     : remap/refactor 3rd part API response to standard format
  writeFile           : Writes $data (string) to $file
  fetchGoogleSheets   : reads google sheets, returns data array
*/


// reads txt file, load each row to array element

function txtFileToArr($data_source)
{
    $sourcesArr= array();
    $handle = fopen($data_source, "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $sourcesArr[]=$line;
        }
        fclose($handle);
        return $sourcesArr;
    } else {
        echo '<mark>ERR:</mark> cant read: <code>'.$data_source.'</code>';
    }
}

// https://css-tricks.com/snippets/php/find-urls-in-text-make-links/

function linkify($text)
{
   // The Regular Expression filter
    $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

   // Check if there is a url in the text
    if (preg_match($reg_exUrl, $text, $url)) {
          // make the urls hyper links
          // $zz= preg_replace($reg_exUrl, "<a href="{$url[0]}">{$url[0]}</a> ", $text);
           $zz= preg_replace($reg_exUrl, '<a href="'.$url[0].'" rel="nofollow">'.$url[0].'</a>', $text);

          return $zz;
    } else {
          // if no urls in the text just return the text
          return '<span class="plain">'.$text.'</span>';
    }
}



// https://stackoverflow.com/questions/36564293/extract-urls-from-a-string-using-php

function extract_urls($string)
{
    preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $string, $match);
    return $match[0];
}



function myCurl($optArray)
{
  // init curl object
    $ch = curl_init();

  // apply those options
    curl_setopt_array($ch, $optArray);

  // execute request and get response
    return curl_exec($ch);

}

// // Pretty-print a JSON string in PHP.
// // https://gist.github.com/drazisil/eda9065698dd0beedede

// /**
//    * Formats a JSON string for pretty printing
//    *
//    * @param string $json The JSON to make pretty
//    * @param bool $html Insert nonbreaking spaces and <br />s for tabs and linebreaks
//    * @return string The prettified output
//    * @author Jay Roberts
//    */
//   function _format_json($json, $html = false) {
//     $tabcount = 0;
//     $result = '';
//     $inquote = false;
//     $ignorenext = false;
//     if ($html) {
//         $tab = "&nbsp;&nbsp;&nbsp;";
//         $newline = "<br/>";
//     } else {
//         $tab = "\t";
//         $newline = "\n";
//     }
//     for($i = 0; $i < strlen($json); $i++) {
//         $char = $json[$i];
//         if ($ignorenext) {
//             $result .= $char;
//             $ignorenext = false;
//         } else {
//             switch($char) {
//                 case '{':
//                     $tabcount++;
//                     $result .= $char . $newline . str_repeat($tab, $tabcount);
//                     break;
//                 case '}':
//                     $tabcount--;
//                     $result = trim($result) . $newline . str_repeat($tab, $tabcount) . $char;
//                     break;
//                 case ',':
//                     $result .= $char . $newline . str_repeat($tab, $tabcount);
//                     break;
//                 case '"':
//                     $inquote = !$inquote;
//                     $result .= $char;
//                     break;
//                 case '\\':
//                     if ($inquote) $ignorenext = true;
//                     $result .= $char;
//                     break;
//                 default:
//                     $result .= $char;
//             }
//         }
//     }
//     return $result;
//   }


function fetchEmbedData($url, $service, $APIkey)
{

  /* --> EMBED.ROCKS  */

    if ($service=='embed.rocks') {
      // require('embed.rocks-options.php');
        $url='https://api.embed.rocks/api?url='.$url.'&skip=article,oembed,imextra,html';
        $header = array(
        'x-api-key: '.$APIkey
        );
        $optArray = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $header
        );
    } /* --> MICROLINK.IO  */

    elseif ($service=='microlink.io') {
        $url='https://api.microlink.io?url='.$url;
        $optArray = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true
        );
    }

  // $pageDocument = @file_get_contents($url,false, $context);
  // $pageDocument = UrlGetContentsCurl($url,false, $context);

    $pageDocument = myCurl($optArray);

  // LOG TO GILR
    file_put_contents('../data/_log.txt', "\n".date("Y-m-d H:i", time())."\t". $pageDocument .PHP_EOL, FILE_APPEND | LOCK_EX);

    return refactorApiJson($pageDocument, $service);
}



function refactorApiJson($responseJSON, $service)
{
    $responseJSON;

    $masterObj = new stdClass();
    if (!isset($responseJSON) || ($responseJSON=='')) {
        echo 'no JSON data given';
        exit;
    }

  /*
  -->     MICROLINK.IO
  */

    if ($service=='microlink.io') {
        $ztmp=json_decode($responseJSON);
        if (!isset($ztmp->data)) {
            echo 'ERR smth wrong';
        } else {
            $data = $ztmp->data;
            $masterObj->status = (isset($responseJSON->status) && ($responseJSON->status != null)) ? $responseJSON->status : null;
            $masterObj->publisher = (isset($data->publisher) && ($data->publisher != null)) ? $data->publisher : null;


            if (isset($data->images) && ($data->images != null)) {
                $masterObj->image = new stdClass();

              // sometimes images are not arrays
                if (is_string($data->images)) {
                    $masterObj->image->url = $data->images;
                } else {
                  // image is an array
                    $masterObj->image->url    = isset($data->image->url) && ($data->image->url != null)    ? $data->image->url : null;
                    $masterObj->image->width  = isset($data->image->width) && ($data->image->width != null)  ? $data->image->width : null;
                    $masterObj->image->height = isset($data->image->height) && ($data->image->height != null) ? $data->image->height : null;
                }
            }

            $masterObj->video = (isset($data->video) && ($data->video != null)) ? $data->video->url : null;
            $masterObj->favicon = (isset($data->logo) && ($data->logo != null)) ? $data->logo->url : null;
            $masterObj->date = (isset($data->date) && ($data->date != null)) ? $data->date : null;
        }
    } /*
  -->     EMBED.ROCK
  */

    elseif ($service=='embed.rocks') {
        $data = json_decode($responseJSON);
        $masterObj->publisher = isset($data->site) ? $data->site : null;

        if (isset($data->images) && ($data->images != null)) {
            $masterObj->image = new stdClass();
            $masterObj->image->url    = isset($data->images[0]->url) && ($data->images[0]->url != null)    ? $data->images[0]->url : null;
            $masterObj->image->width  = isset($data->images[0]->width) && ($data->images[0]->width != null)  ? $data->images[0]->width : null;
            $masterObj->image->height = isset($data->images[0]->height) && ($data->images[0]->height != null) ? $data->images[0]->height : null;
        }

        $masterObj->video = (isset($data->videos) && ($data->videos != null)) ? $data->videos[0]->url : null;
        $masterObj->favicon = (isset($data->favicon) && ($data->favicon != null)) ? $data->favicon->url : null;

      // sometimes dates are objects
      // 'sometimes' date is 'published_date' ??
        $masterObj->date = (isset($data->published_date) && ($data->published_date != null)) ? $data->published_date : null;
        if (is_string($data->date)) {
            $masterObj->date = $data->date;
        } else {
          // image is an array
            $masterObj->image->date    = isset($data->date->orig) && ($data->date->orig != null)    ? date("YmdHi", strtotime($data->date->orig)) : null;
        }
    }


    $masterObj->url=(isset($data->url) && ($data->url != null)) ? $data->url  : null;
    $masterObj->title=(isset($data->title) && ($data->title != null)) ? $data->title  : null;
    $masterObj->description=(isset($data->description) && ($data->description != null)) ? $data->description  : null;

    $json=json_encode($masterObj);
    return($json);
}


/*
  Writes $data (string) to $file
 */

function writeFile($file, $data, $echo = true)
{
    $ret = file_put_contents($file, $data, LOCK_EX);
    if ($ret === false) {
        die('There was an error writing this file');
    } else {
        if ($echo) {
            echo "<br><b>".round($ret/1000, 2)." Kb</b> &rarr; <code><a href=\"".$file."\">".$file."</a></code>";
        }
    }
}



function fetchGoogleSheets($spreadsheet_url)
{
    if (!ini_set('default_socket_timeout', 40)) {
        $msg.= "<p>unable to change socket timeout</p>";
    }
    $firstPass=false;
    $columnNames='';
    if (($handle = fopen($spreadsheet_url, "r")) !== false) {
        while (($data = fgetcsv($handle, 0, ",")) !== false) {
          // echo '<hr><pre>'; print_r($data).'</pre>';
            if (!$firstPass) {
                $columnNames=$data;
                $firstPass=true;
            } else {
                $row=array();
                foreach ($data as $i => $cellValue) {
                    $row[$columnNames[$i]]=$cellValue;
                }
                $spreadsheet_data[] = $row;
            }
        }
        fclose($handle);
    } else {
        die("Problem reading csv");
    }
    return $spreadsheet_data; //json
}


//  slightly nicer print_r
function pr($array, $is_shy = 0, $style = 'array')
{
    $shy='';
    if ($is_shy) {
        $shy='shy';
    }
    echo '<pre class="print_r '.$shy.'" onclick="this.classList.toggle(\'shy\')"><mark>'.print_var_name($array).'</mark><div>';
    switch ($style) {
        case 'dump':
            var_dump($array);
        case 'export':
            var_export($array);
        default:
            print_r($array);
    }
    echo '</div></pre>';
}


function print_var_name($var)
{
    foreach ($GLOBALS as $var_name => $value) {
        if ($value === $var) {
            return $var_name;
        }
    }

    return false;
}
