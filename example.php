<?php
require_once('badcodescan.php');            //  include class

$ex_code_scan = new BadCodeScan;            // create new object of BadCodeScan class

#Sample #1
$ex_code_scan->searchBadCode();             // search bad code by default mask and parameters ($path = '.', $mask = 'eval(base64_decode', $ext = '*.php')
$ex_code_scan->showResultTable();           // show result table (optional)


#Sample #2
$ex_code_scan = new BadCodeScan;            // create new object of BadCodeScan class (optional: you can collect multiple results by other masks)
$ex_code_scan->searchBadCode( '.',  'system(',  '*.txt' );  // search bad code by custom mask and parameters.
$ex_code_scan->showResultTable();           // show result table (optional)

#Sample #3
$ex_code_scan = new BadCodeScan;
$ex_code_scan->searchBadCode( '.', ',""); /\*'); 
$ex_code_scan->showResultTable(); 