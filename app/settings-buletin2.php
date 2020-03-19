<?php

// TODO: nicer const names

/* fetch data 
- - - - - - - - - - - - - - - - - - - - -  */

$GLOBALS['docID'] = '2PACX-1vTpJvjP0NhF_6Ma2qOe-1Uy0FcD_qzNOhGAKSUMZNd6phzsgOw5Tm1bizZbAhUdtsoA2xkbdjzFbJDy';

// TODO: automate this
$GLOBALS['sheets'] = array(
  'Inițiative civice' => 0,
  'Inițiative personale' => 1738822528,
  'Inițiative companii' => 159026053,
  'Educație Cursuri' => 1058734499,
  'Cultură' => 1420865732,
  'Donații prin ONG-uri' => 1732932632,
  'Donații companii' => 1148538094
);

$GLOBALS['zeFile']      = '../data/google-sheets.json';

/* render html & fetch icons
- - - - - - - - - - - - - - - - - - - - -  */
$GLOBALS['sourceJson']  = '../data/google-sheets.json';
$GLOBALS['targetfile']  = '../../index.html';
$GLOBALS['iconsDIR']    = '../data/icons/';
$GLOBALS['iconsDIRrel'] = 'app/data/icons/';

/*  
  TODO:
  nav pre / post items
  $GLOBALS['nav_pre'] 
  $GLOBALS['nav_post'] 
  $GLOBALS['widgets'] = [] 
  $GLOBALS['footer'] 
  $GLOBALS['header'] 
  $GLOBALS['head'] 
  $GLOBALS['og_image'] 
*/