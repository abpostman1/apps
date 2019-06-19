<?php

// ENUMERATION DE TOUTES LES CARACTERISTIQUES PRESENTES DANS LE CATALOGUE FINDIS

ini_set('display_errors',1); error_reporting(E_ALL);

include('includes/conn_findis.php');

echo ('départ<br>');

function testSql($test) {
    if (!$test) {
        printf('Erreur : %s\n', $mysqli->error);
        exit();
    }
}


$row = 1;

// test si cron ou http

//if (isset($_SERVER['SHELL'])) {
//	$prefix = 'httpdocs/hub';
//	$context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
//	$url = 'http://abpostman1:axel3927@vps364444.ovh.net/var/export/flow/ventes.xml';
//	$flow = file_get_contents($url, false, $context);
//	$xml =  simplexml_load_string($flow);
//	
//	//uniquement pour l'include de calcul_marge.php
//	$prefix2 = 'httpdocs/hub/ventes/';
//}
//else {
	//$prefix = '..';
	//$prefix2 = '';

$xml = simplexml_load_file('source/findis.xml') or die("Error: Cannot create object");
//}
header('Content-Type: text/html; charset=utf-8');
	

// TRAITEMENT DU FICHIER PRODUITS

$sql = $mysqli->query("TRUNCATE TABLE carac");

$count = 0;
$charge = 0;
$port = 0;

while (isset($xml->prods->prod[$count])) {
	
	
	$count_caract = 0 ;
	$count_img = 0;
    
    // Aglomération des caratéristiques pour construire la DESCRIPTION produit
	
	foreach ($xml->prods[0]->prod[$count]->caracts->caract as $caract){
			
		$lib_caract = $xml->prods[0]->prod[$count]->caracts->caract[$count_caract]->lib;
		$val_caract = $xml->prods[0]->prod[$count]->caracts->caract[$count_caract]->val;
		$count_caract ++;
        
        // recensement de toutes les caractérisiques
        
        $lib_caract = utf8_decode(addslashes($lib_caract));
        
        $sql2 = $mysqli->query("SELECT COUNT(id) AS count FROM carac WHERE lib_findis = '$lib_caract'") ;
        
        if (!$sql2) {
        printf('Erreur : %s\n', $mysqli->error);
        exit();
    }
        
        $result = $sql2->fetch_assoc();
        if ($result['count'] == 0){
            
            $sql3 = $mysqli->query("INSERT INTO carac VALUES ('', '$lib_caract', '')");
        }
		
	}

	$count ++;
}

	
//$mysqli->close(); 