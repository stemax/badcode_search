<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once('badcodescan.php');

$ex_code_scan = new BadCodeScan;
$ex_code_scan->searchBadCode( '.',  'eval(base64_decode',  '*.*' );
$ex_code_scan->showResultTable();

$ex_code_scan->searchBadCode( '.',  'eval(gzinflate',  '*.*' );
$ex_code_scan->showResultTable();

?>
<?