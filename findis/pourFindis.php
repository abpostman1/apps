<?php
/////////////////////////////////////////////////////////////////////////////////
// fichier exporté de MAGENTO contenant :
// - SKU
// - Name
// - attribute_set
// - supplier_name
// - cost
// - price
// - id
//
// Sert à construire un fichier de référence pour COMPARE.PHP
//
//
//PARSE.PHP doit être lancé avant
/////////////////////////////////////////////////////////////////////////////////

ini_set('display_errors',1); error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

include 'includes/conn_findis.php';

$xml = simplexml_load_file('source/pourFindis.xml');
if (!$xml){
	echo 'Erreur fichier';
	//exit();
}

$count = 0;
$count2 = 0;
$today = date ('Y-m-d');

$sql = $mysqli->query("TRUNCATE TABLE pourFindis");


foreach ($xml -> item as $item) {
	$sku = $item->sku;
	$name = addslashes(utf8_decode($item->name));
	$attribute_set = $item->attribute_set;
	$supplier = $item->supplier;
	$cost = floatval($item->cost);
	$price = floatval($item->price);
    $mageId = $item->id;
    $protected = $item->protectedsku;
	
	$sql2 = $mysqli->query("INSERT INTO pourFindis VALUES ('', '$sku', '$name', '$attribute_set', '$supplier', '$cost', '$price', '$mageId', '$today', '', '', '', '$protected')");
        
    if (!$sql2){
        printf('Erreur  %s\n', $mysqli->error);
        exit();
    }
    
    //$sql3 = $mysqli->query("SELECT stock, dispo, newPv FROM produits WHERE refConstruct = '$sku'");
//    
//    if (!$sql3) {
//        printf ('Erreur, %s\n', $mysqli->error);
//        exit();
//    }
//    
//    $result3 = $sql3->fetch_assoc();
//    $stock = $result3['stock'];
//    $newPv = $result3['newPv'];
//    $dispo = $result3['dispo'];
//    $sql4 = $mysqli->query("UPDATE pourFindis SET stock = '$stock', newPv = '$newPv', dispo = '$dispo' WHERE sku = '$sku'");
//    if (!$sql4) {
//        printf ('Erreur, %s\n', $mysqli->error);
//        exit();
//    }
    $count ++;
}
	
echo $count.' skus ajoutés';


