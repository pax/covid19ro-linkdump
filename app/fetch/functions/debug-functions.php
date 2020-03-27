<?php 
// adds pre before function
function xpr ($someVar, $echo = true){
  if ($echo){
    echo "<pre style=\" max-width: 100%; overflow: hidden; font-size: 11px; word-wrap:break-word; word-break: break-all;\">";
    print_r($someVar);
    // var_dump($someVar);
    echo "</pre>";
  }
else  
  return '<pre style=" max-width: 100%; overflow: hidden; font-size: 11px;">'.print_r($someVar, 1).'</pre>';
}

?>