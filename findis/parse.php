<?php

// ECLATEMENT DU FICHIER CYCLE DE VIE (CSV)
// PARSE_CAT.PHP doit être exécuté préalablement.

ini_set('display_errors',1); error_reporting(E_ALL);

include('includes/conn_findis.php');

echo ('départ<br>');

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

$xml = simplexml_load_file('source/FINDIS_Articles.xml') or die("Error: Cannot create object");
//$xml = simplexml_load_file('source/findis_mini.xml') or die("Error: Cannot create object");

//}
header('Content-Type: text/html; charset=utf-8');


// TRAITEMENT DU FICHIER STOCKS (exportCycleDeVie)

$countEnStock = 0;
$countHorsStock = 0;
$countTotal = 0;
$big_desc = '';

$sql = $mysqli->query("TRUNCATE TABLE produits");

if (($handle = fopen("source/exportCycleDeVie.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
	    $sku = htmlspecialchars(mysqli_real_escape_string($mysqli,$data[0]));
        $dispo = htmlspecialchars(mysqli_real_escape_string($mysqli,$data[9]));
		
		$stock = htmlspecialchars(mysqli_real_escape_string($mysqli,$data[1]));
		
		// si le produit est SUR COMMANDE avec STOCK à 0, on applique stock = 99
		// constat : certains produits sont SUR COMMANDE mais un stock est tout de même renseigné.
		
		if ($dispo == "Sur commande") {
			$dispo = 'commande';
			$stock = 99;
		}
		
		$countTotal ++;
        
        if ($stock > 0) {
            $countEnStock ++;
        }
        else {
            $countHorsStock ++;
        }
        
        //echo $stock.' = '.$sku.'<br>';
        
       $sql = $mysqli->query("INSERT INTO produits (id,sku,stock,dispo) VALUES ('', '$sku', '$stock', '$dispo')");
        
        if (!$sql) {
            printf('Erreur : %s\n', $mysqli->error);
            print_r($sql);
            // test pour trouver erreur sql
            echo '<br>INSERT INTO produits'. $sku.'<br>';
            exit();
                
        }
        
    }
    fclose($handle);
    
    echo 'total produits : '.$countTotal.'<br>';
    echo 'dont '.$countHorsStock.' hors stock';
}

//exit();
	

// TRAITEMENT DU FICHIER PRODUITS

$count = 0;
$charge = 0;
$port = 0;


while (isset($xml->prods->prod[$count])) {
	
	$art_id = $xml->prods->prod[$count]->art_id;
	//echo($art_id.'-'.$count.'<br>');
	$art_cle = $xml->prods->prod[$count]-> art_cle ;
	$const_ref = $xml->prods->prod[$count]->const_ref ;
	$const_gar = $xml->prods->prod[$count]->const_gar ;
	$poids = $xml->prods->prod[$count]->poids ;
	$ean = $xml->prods->prod[$count]->ean ;
	$codeDouane = $xml->prods->prod[$count]->codeDouane ;
	$statut = $xml->prods->prod[$count]->statut ;
	$partnumber = $xml->prods->prod[$count]->partnumber ;
	$marque = htmlspecialchars(mysqli_real_escape_string($mysqli,$xml->prods->prod[$count]->marque)) ;
	$parCombien = $xml->prods->prod[$count]->parCombien ;
	$lib = htmlspecialchars(mysqli_real_escape_string($mysqli,$xml->prods->prod[$count]->lib)) ;
	$prx_ht = floatval($xml->prods->prod[$count]->prx_ht) ;
	$prx_taxe = floatval($xml->prods->prod[$count]->prx_taxe) ;
	$prx_copie = $xml->prods->prod[$count]->prx_copie ;
	$prx_public = $xml->prods->prod[$count]->prx_public ;
	$descr_c = htmlspecialchars(mysqli_real_escape_string($mysqli,$xml->prods->prod[$count]->descr_c)) ;
	$descr_l = htmlspecialchars(mysqli_real_escape_string($mysqli,$xml->prods->prod[$count]->descr_l));
	$idCategorieWeb = $xml->prods->prod[$count]->idCategorieWeb ;
	$nomCategorieWeb = ucfirst(strtolower($xml->prods->prod[$count]->nomCategorieWeb)) ;
    $nomCategorieWeb = utf8_decode($nomCategorieWeb);
	$category = $xml->prods->prod[$count]->category ;
    
    $count_caract = 0;
    
    $big_desc = "<h2>Détails produit</h2>";
    $big_desc .='<h3>DESCRIPTION</h3><br>'; 
    $big_desc .= "<p>".$descr_l.'</p><br>';
    $big_desc .= '<h3>CARACTERISTIQUES</h3><br>' ;
    
    $couverts = '';
    $decibels = '';
    $capacite = '';
    $essorage = '';
    $sonore = '';
    $energie = '';
    
    
   $big_desc .= '<table class="data-table">
        <tbody>';


    foreach ($xml->prods[0]->prod[$count]->caracts[0]->caract as $caract){
			
		$lib_caract = htmlspecialchars(mysqli_real_escape_string($mysqli,$xml->prods[0]->prod[$count]->caracts[0]->caract[$count_caract]->lib));
		$val_caract = htmlspecialchars(mysqli_real_escape_string($mysqli,$xml->prods[0]->prod[$count]->caracts[0]->caract[$count_caract]->val));
        
        
        if ($val_caract <> ''){
            
                        
            $big_desc .= '<tr><td>'.$lib_caract.'</td><td>'.$val_caract.'</td></tr>';
            
            
            // nettoyage des trs/min : seuls les chiffres sont conservés
            
            
            if ($lib_caract == "Le + produit") {
                $plusProduit = $val_caract ;
            }
            
            
            // ne garder que les chiffres
            if ($lib_caract == "Vitesse d\'essorage maxi"){
                $essorage = utf8_decode(preg_replace('~\D~', '', $val_caract));
            }
           
            if ($lib_caract == 'Capacité'){
                $capacite = utf8_decode($val_caract);
                if ($capacite == 'L'){
                    $capacite = '';
                }
            }
            
            if ($lib_caract == 'Niveau sonore'){
                $decibels = utf8_decode($val_caract);
                if ($decibels == '0 dB'){
                    $decibels = '';
                }
            }
          
            if ($lib_caract == 'Nombre de couverts'){
                $couverts = utf8_decode($val_caract);
            }
            
            
            if ($lib_caract == 'Label énergie'){
                $energie = utf8_decode($val_caract);
            }      
            
        }
        
		$count_caract ++;
		
	}
    
    $big_desc .= '</tbody></table>';

    
    $big_desc .= '<br>';
    
    // convention syntaxe des SKU
    
    $newSku = 'FND-'.$const_ref;
    
    // Calcul prix de vente TTC = (prix achat X marge + ECOPART + assur)X1.2
    // assurance = 4% du prix d'achat HT
    
    if ($prx_ht < 1000) {
    	$newPv = ($prx_ht * 1.15 + $prx_taxe + ($prx_ht * 0.04)) * 1.2;
	}
	
	else {
		$newPv = ($prx_ht * 1.15 + $prx_taxe + ($prx_ht * 0.02)) * 1.2;
	}
    
		/// Récup de la famille pour créer le final path de la catégorie du produit (ajout categorieWeb)
	
	$sql = $mysqli->query("SELECT path FROM familles WHERE id = '$category'");
	$result = $sql->fetch_assoc();
		
	if ($nomCategorieWeb != "") {
		
		$finalPath = addslashes($result['path']."/".$nomCategorieWeb."::1::1::1");
	}
	
	else {
		$finalPath = addslashes($result['path']);
	}
    
    
    if ($big_desc == '<h2>Détails produit</h2><h3>DESCRIPTION</h3><br><p></p><br>') {
        $big_desc = '';
    }
    
    $big_desc = $big_desc;
    
    // DISPLAY
    
    // redimensionnement image
	
	$img = $xml->prods->prod[$count]->imgs->img ;
    $img = str_replace('/150/','/600/', $img);
    $img = str_replace('150.jpg','600.jpg', $img);
	
	echo '<br>Sku : '.$art_id ;
	//echo '<br>Clé art. :'.$art_cle ;
//	echo '<br>Ref constructeur :'.$const_ref ;
//	echo '<br>Const. gar . ???'.$const_gar ;
//	echo '<br>Poids :'.$poids ;
//	echo '<br>Ean :'.$ean ;
//	echo '<br>Code douane :'.$codeDouane ;
//	echo '<br>Statut :'.$statut ;
//	echo '<br>Part number :'.$partnumber ;
//	echo '<br>Marque : '.$marque ;
//	echo '<br>Vendu par : '.$parCombien ;
	echo '<br>Libellé : '.$lib ;
//	echo '<br>Prix HT : '.$prx_ht ;
//	echo '<br>Prix taxe : '.$prx_taxe ;
//	echo '<br>Prix copie : '.$prx_copie ;
//	echo '<br>Prix public : '.$prx_public ;
//	echo '<br>Description courte : '.$descr_c ;
//	echo '<br>Description Longue : '.$descr_l ;
//	echo '<br>ID catégorie WEB : '.$idCategorieWeb ;
//	echo '<br>Nom caté. WEB : '.$nomCategorieWeb ;
//	echo '<br>ID catégorie : '.$category ;
//	//echo '<br><img src="'.str_replace('150','150',$img).'" alt="'.$lib.'">';
	echo '<br>Final path : '.utf8_encode($finalPath);
//	
	// $details = '<h2>Détails produit</h2><br>';
    
    
    
   // echo $details ;
    
    //echo '<br>esso : '.$essorage ;
//    echo '<br>capa : '.$capacite ;
//    echo '<br>sonore : '.$decibels ;
//    echo '<br>energie : '.$energie;
//    echo '<br>couverts : '.$couverts;
//    echo '<br>Big : '.$big_desc ;
    
	echo '<hr>';
    
    
    if (!isset($plusProduit)) {
        $plusProduit = '';
    }
    
    // ENREGISTREMENT DU FINAL PATH, nouveau prix de vente et ref consctruct DANS LA TABLE PRODUITS
	
    $sql2 = $mysqli->query("UPDATE produits SET 
        path = '$finalPath',
        name = '$lib',
        refConstruct = '$const_ref',
        pv = '$prx_ht',
        newPv = '$newPv',
        newSku = '$newSku',
        eco_participation = '$prx_taxe',
        ean = '$ean',
        weight = '$poids',
        brand = '$marque',
        essorage = '$essorage',
        capacite = '$capacite',
        decibels = '$decibels',
        nb_couverts = '$couverts',
        energie = '$energie',
        description = '$big_desc',
        image = '$img',
        plusProduit = '$plusProduit'
        WHERE sku = '$art_id'");
    
    if (!$sql2) {
        printf('Erreur UPDATE produits: %s\n', $mysqli-> error);
                    // test pour trouver erreur sql
            echo '<br>UPDATE produits'. $art_id.'<br>';

        exit();
    }
	
	$count_caract = 0 ;
	$count_img = 0 ;
    
	$count ++;
}


// récupération des quantités du fichier (exportArticles.csv) notamment qty en cours de réappro
// donnée uniquement dispo dans ce fichier

if (($handle = fopen("source/exportArticles.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
	    
        // utilisation de la ref fournisseur car le SKU est déjà en FND- dans la table produit
        
        $sku =   $data[1];
        $reappro = intval ($data[16]);
        
        echo $sku.' - '.$reappro.'<br>';
		//$stock = intval ($data[8]);
        
        //$finalQty = $reappro + $stock;
        
        $sql = $mysqli->query("UPDATE produits SET reappro = '$reappro' WHERE refConstruct = '$sku'");

        if (!$sql) {
            printf('Erreur produits SET reappro '.$sku.': %s\n', $mysqli->error);
            exit();
        }
        
    }
    fclose($handle);
    
}
	
$mysqli->close(); 