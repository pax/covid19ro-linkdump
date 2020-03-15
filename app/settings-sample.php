<?php

/* fetch data 
- - - - - - - - - - - - - - - - - - - - -  */

$GLOBALS['zeFile'] = '../data/google-sheets.json';
$GLOBALS['docID'] = '2PACX-1vTERxGzP9c65waSCL3Wskg2JDFi4GkIfC62uPIKo9Drxy5L46K1JvPFudehEEFd_gzIuIam74PDbwAs';

$GLOBALS['sheets'] = array(
  'Info surse oficiale' => 1584676943,
  'Solidaritate' => 0,
  'Utile' => 333501499,
  'Edutainment' => 1148678370
);


/* render html 
- - - - - - - - - - - - - - - - - - - - -  */

$GLOBALS['sourceJson'] = '../data/google-sheets.json';
$GLOBALS['targetfile'] = '../../index.html';
$GLOBALS['iconsDIR'] = '../data/icons/';
$GLOBALS['iconsDIRrel'] = 'app/data/icons/';

 