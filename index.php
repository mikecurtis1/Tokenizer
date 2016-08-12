<?php 

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once dirname(__FILE__) . '/lib/Tokenizer.php';
require_once dirname(__FILE__) . '/lib/Token.php';

$q = '';
if ( isset($_GET['q']) ) {
    $q = $_GET['q'];
}

$tokenizer = new Tokenizer();
$tokens = $tokenizer->tokenize($q);

include('template.php');
