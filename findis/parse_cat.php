<?php

// CREATION DES FAMILLES et ajout du path (colonne CATEGORY dans le CSV final)
// LE PATH sera récupéré lors du traitement du fichier PRODUIT et ajouté au CSV
// A EXECUTER AVANT PARSE.PHP pour que le FINAL PATH soit bien construit
$cat_mere = 0;
$missing = array();

ini_set('display_errors',1); error_reporting(E_ALL);

//echo ('départ ...<br>');

include ('includes/conn_findis.php');


// Mise à jour des LIB_TEMP (sql) si la catégorie concernée est dépendante d'une catégorie ROOT

$sql000 = $mysqli->query("SELECT * FROM famille_root");

while ($result000 = $sql000->fetch_assoc()) {
    
    $child_id = $result000['cat_id'];
    $parent_id = $result000['root'];
    
    $sql001 = $mysqli->query("SELECT lib_coop FROM familles WHERE id = '$child_id'");
    $result001 = $sql001->fetch_assoc();
    $child_lib = $result001['lib_coop'];
    
    $sql002 = $mysqli->query("SELECT lib_coop FROM familles WHERE id = '$parent_id'");
    $result002 = $sql002->fetch_assoc();
    $parent_lib = $result002['lib_coop'];
    
    $lib_temp = $parent_lib.'::1::1::1/'.$child_lib;
    
    $sql003 = $mysqli->query("UPDATE familles SET lib_temp = '$lib_temp' WHERE id = '$child_id'");
}


// récupération du libellé normalisé pour construire le PATH

function Read($cat_id,$mysqli) {
	
	$sql100 = $mysqli->query("SELECT lib_coop, lib_temp FROM familles WHERE id = '$cat_id'");
	$result100 = $sql100->fetch_assoc();
	$lib_result = $result100['lib_coop'];
    $lib_temp = $result100['lib_temp'];
    
    // vérification de l'existence de la caté du produit pour alerter de la nécessité de créer la caté manquante.
    if ($lib_result <> "")
    {
        if ($lib_temp == ''){   
            return $lib_result;
        }
        else {
            return $lib_temp;
        }
	//echo $lib_result;exit();
    }
    
    else 
    {
      $missing[] = $cat_id; 
        return;
    }
}
	
// écriture du PATH

function Write($id,$path,$mysqli) {
	
	//echo $id.'-'.$path;//exit;
	$path = addslashes($path);	
	$sql01 = $mysqli->query("UPDATE familles SET path = '$path' WHERE id = '$id'");
}
	

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
	$xml = simplexml_load_file('source/FINDIS_Familles.xml') or die("Error: Cannot create object");
//}

//header('Content-Type: text/html; charset=utf-8');


//include ('includes/conn_findis.php');
$count1 = 0;


//////////////////////// cat 1  /////////////////////////

while (isset($xml->catalogue[0]->cat1[$count1])) {
    
    
	$cat_mere ++;
    $path = "";
	
	$cat1_id = $xml->catalogue[0]->children()->cat1[$count1]['id'];
	$cat1_name = ucfirst(strtolower($xml->catalogue[0]->children()->cat1[$count1]['name']));
    
		
	$read = Read($cat1_id,$mysqli);
	$path .= $read."::1::1::1";
    
	$write = Write($cat1_id,$path,$mysqli);
    
	echo '<hr>';
	echo '<h2>'.$cat1_id.'&nbsp;:&nbsp;'.$cat1_name.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp'.utf8_encode($path).'</h2><br>'; 
	
    //////////////////////// cat 2  /////////////////////////	
	
	$count2 = 0;
	
	while (isset($xml->catalogue[0]->cat1[$count1]->cat2[$count2])) {
		
		$cat2_id = $xml->catalogue[0]->cat1[$count1]->children()->cat2[$count2]['id'];
		$cat2_name = ucfirst(strtolower($xml->catalogue[0]->cat1[$count1]->children()->cat2[$count2]['name']));
		
		
		// check si cat2 = cat 1 . si oui, pas d'affichage
		
		if ((string)$cat2_id != (string)$cat1_id) {
							
            $read = Read($cat1_id,$mysqli);
            $path = $read."::1::1::1/";

            $read = Read($cat2_id,$mysqli);
            $path .= $read."::1::1::1";

            //echo $path;exit();
            $write = Write($cat2_id,$path,$mysqli);

            echo '<h4><span style="margin-left:50px;display:inline-block">>>&nbsp;'.$cat2_id.'&nbsp;:&nbsp;'.$cat2_name;
            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp'.utf8_encode($path).'</span></h4><br>';
		}
		
		
	    //////////////////////// cat 3  /////////////////////////	
		
		$count3 = 0;

		while (isset($xml->catalogue[0]->cat1[$count1]->cat2[$count2]->cat3[$count3])) {

			$cat3_id = $xml->catalogue[0]->cat1[$count1]->cat2[$count2]->children()->cat3[$count3]['id'];
			$cat3_name = ucfirst(strtolower($xml->catalogue[0]->cat1[$count1]->cat2[$count2]->children()->cat3[$count3]['name']));
			
			// check si cat3 = cat 2 . si oui, pas d'affichage
		
			if ((string)$cat3_id != (string)$cat2_id) {

				$read = Read($cat1_id,$mysqli);
				$path = $read."::1::1::1/";
			
				$read = Read($cat2_id,$mysqli);
				$path .= $read."::1::1::1/";
				
				$read = Read($cat3_id,$mysqli);
				$path .= $read."::1::1::1";

				//echo $path;exit();
				$write = Write($cat3_id,$path,$mysqli);
                
                echo '<span style="margin-left:100px;display:inline-block">>>&nbsp;'.$cat3_id.'&nbsp;:&nbsp;'.$cat3_name;
				echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp'.utf8_encode($path).'</span><br>';
				
				}
				
	//			
    //	    //////////////////////// cat 4  /////////////////////////	
    //				
				$count4 = 0;
				
				while (isset($xml->catalogue[0]->cat1[$count1]->cat2[$count2]->cat3[$count3]->cat4[$count4])) {
					
					$cat4_id = $xml->catalogue[0]->cat1[$count1]->cat2[$count2]->cat3[$count3]->children()->cat4[$count4]['id'];
					$cat4_name = ucfirst(strtolower($xml->catalogue[0]->cat1[$count1]->cat2[$count2]->cat3[$count3]->children()->cat4[$count4]['name']));
					
					if ((string)$cat4_id != (string)$cat3_id) {
				
						$read = Read($cat1_id,$mysqli);
						$path = $read."::1::1::1/";

						$read = Read($cat2_id,$mysqli);
						$path .= $read."::1::1::1/";

						$read = Read($cat3_id,$mysqli);
						$path .= $read."::1::1::1/";

						$read = Read($cat4_id,$mysqli);
						$path .= $read."::1::1::1";
                        
                        $array1 = array(' -', '- ');
                        $array2 = '-';
                        $path = str_replace($array1, $array2, $path);

						$write = Write($cat4_id,$path,$mysqli);

						echo '<span style="margin-left:200px;display:inline-block;color:red;">>>&nbsp;'.$cat4_id.'&nbsp;:&nbsp;'.$cat4_name;
						echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp'.utf8_encode($path).'</span><br>';
						
					}
					
									
				$count4 ++;
				
			}
			

			$count3 ++;	

		}
		
		$count2 ++;
		
	}

	$count1 ++;
}

echo $cat_mere;


// ajout sur toutes les catégorries de la SUPER CATEGORIE ROOT 'tous nos univers'

$sql = $mysqli->query('SELECT id, path FROM familles');

while ($result = $sql->fetch_assoc())
{
    $path = $result['path'];
    $id = $result['id'];
    
    if ($id <> 55555 and $id <> 66666) 
    {
      
        // ajout de la caté SUPER ROOT
        $racine = utf8_decode("Tous nos univers::1::1::1/");
        $path = $racine.$path;
        // réécriture du PATH FINAL
        Write($id,$path,$mysqli);
    }
    
    
    
    
}

// affichage des cat manquantes dans le référentiel des catégories (nouvelle caté FINDIS)

// il faudra créer une fonction MAIL pour prévenir les admin des caté manquantes

print_r($missing);
	
$mysqli->close(); 










