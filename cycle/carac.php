<?php

include('includes/conn_cycle.php');
ini_set('display_error',1);error_reporting(EALL);

$file = 'cycle.csv';

$entete = array('ref','desc');

// ecriture de l'entete dans le csv

$fp = fopen($file, 'w+');
//fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
fputcsv($fp, $entete,';');

$sql = $mysqli->query("SELECT * FROM cycle");

while ($result = $sql->fetch_assoc()) {
    $Ref = $result['Ref'];
    $parent = $result['parent'];
    $Marque = utf8_encode($result['Marque']);
    $Designation = utf8_encode($result['Designation']);
    $Htr = $result['Htr'];
    $Col = $result['Col'];
    $Famille_code = $result['Famille_code'];
    $Famille = $result['Famille'];
    $cost = $result['cost'];
    $ppi = $result['ppi'];
    $Gencod = $result['Gencod'];
    $pret_rouler = $result['pret_rouler'];
    $Description = utf8_encode($result['Description']);
    $url_image = $result['url_image'];
    $name = utf8_encode($result['name']);
    $Cadre = utf8_encode($result['Cadre']);
    $Fourche = utf8_encode($result['Fourche']);
    $Moteur = utf8_encode($result['Moteur']);
    $Batterie = utf8_encode($result['Batterie']);
    $Autonomie = utf8_encode($result['Autonomie']);
    $Console = utf8_encode($result['Console']);
    $Chargeur = utf8_encode($result['Chargeur']);
    $Pedalier = utf8_encode($result['Pedalier']);
    $Jantes = utf8_encode($result['Jantes']);
    $moyeux = utf8_encode($result['moyeux']);
    $Pneus = utf8_encode($result['Pneus']);
    $Manettes = utf8_encode($result['Manettes']);
    $derailleur_ar = utf8_encode($result['derailleur_ar']);
    $derailleur_av = utf8_encode($result['derailleur_av']);
    $Cassette = utf8_encode($result['Cassette']);
    $Selle = utf8_encode($result['Selle']);
    $tige_selle = utf8_encode($result['tige_selle']);
    $Cintre = utf8_encode($result['Cintre']);
    $Potence = utf8_encode($result['Potence']);
    $Freins = utf8_encode($result['Freins']);
    $garde_boue = utf8_encode($result['garde_boue']);
    $porte_bag = utf8_encode($result['porte_bag']);
    $couvre_chaine = utf8_encode($result['couvre_chaine']);
    $bequille = utf8_encode($result['bequille']);
    $eclairage = utf8_encode($result['eclairage']);
    $pedales = utf8_encode($result['pedales']);
    $Coloris = utf8_encode($result['Coloris']);
    $Tailles = utf8_encode($result['Tailles']);
    $Poids = utf8_encode($result['Poids']);
    $plus1 = utf8_encode($result['plus1']);
    $plus2 = utf8_encode($result['plus2']);
    $plus3 = utf8_encode($result['plus3']);
    
    $desc = '<h2>Caractéristiques produit</h2>
   
    <h2>Description produit</h2>
        <p>'.$Description.'<br></p>
        
    <table class="data-table">
        <tbody>
            <tr><td><strong>Nom</strong></td><td>'.$name.'</td></tr>
            <tr><td><strong>Autonomie</strong></td><td>'.$Autonomie.'</td></tr>
            <tr><td><strong>Batterie</strong></td><td>'.$Batterie.'</td></tr>
            <tr><td><strong>Cadre</strong></td><td>'.$Cadre.'</td></tr>
            <tr><td><strong>Fourche</strong></td><td>'.$Fourche.'</td></tr>
            <tr><td><strong>Moteur</strong></td><td>'.$Moteur.'</td></tr>
            <tr><td><strong>Console</strong></td><td>'.$Console.'</td></tr>
            <tr><td><strong>Chargeur</strong></td><td>'.$Chargeur.'</td></tr>
            <tr><td><strong>Pédalier</strong></td><td>'.$Pedalier.'</td></tr>
            <tr><td><strong>Jantes</strong></td><td>'.$Jantes.'</td></tr>
            <tr><td><strong>Moyeux</strong></td><td>'.$moyeux.'</td></tr>
            <tr><td><strong>Pneus</strong></td><td>'.$Pneus.'</td></tr>
            <tr><td><strong>Manettes</strong></td><td>'.$Manettes.'</td></tr>
            <tr><td><strong>Dérailleur ar</strong></td><td>'.$derailleur_ar.'</td></tr>
            <tr><td><strong>Dérailleur av</strong></td><td>'.$derailleur_av.'</td></tr>
            <tr><td><strong>Cassette</strong></td><td>'.$Cassette.'</td></tr>
            <tr><td><strong>Selle</strong></td><td>'.$Selle.'</td></tr>
            <tr><td><strong>Tige selle</strong></td><td>'.$tige_selle.'</td></tr>
            <tr><td><strong>Cintre</strong></td><td>'.$Cintre.'</td></tr>
            <tr><td><strong>Potence</strong></td><td>'.$Potence.'</td></tr>
            <tr><td><strong>Freins</strong></td><td>'.$Freins.'</td></tr>
            <tr><td><strong>Garde_boue</strong></td><td>'.$garde_boue.'</td></tr>
            <tr><td><strong>Porte bag</strong></td><td>'.$porte_bag.'</td></tr>
            <tr><td><strong>Couvre chaine</strong></td><td>'.$couvre_chaine.'</td></tr>
            <tr><td><strong>Béquille</strong></td><td>'.$bequille.'</td></tr>
            <tr><td><strong>Eclairage</strong></td><td>'.$eclairage.'</td></tr>
            <tr><td><strong>Pédales</strong></td><td>'.$pedales.'</td></tr>
            <tr><td><strong>Coloris</strong></td><td>'.$Coloris.'</td></tr>
            <tr><td><strong>Tailles</strong></td><td>'.$Tailles.'</td></tr>
            <tr><td><strong>Poids</strong></td><td>'.$Poids.'</td></tr>
            <tr><td><strong>plus1</strong></td><td>'.$plus1.'</td></tr>
            <tr><td><strong>plus2</strong></td><td>'.$plus2.'</td></tr>
            <tr><td><strong>plus3</strong></td><td>'.$plus3.'</td></tr>
        </tbody>
    </table>'; 
    
    $desc .= '
     <table class="data-table">
        <tbody>
            <tr><td><strong>Ref</strong></td><td>'.$Ref.'</td></tr>
            <tr><td><strong>Réf. parente</strong></td><td>'.$parent.'</td></tr>
            <tr><td><strong>Marque</strong></td><td>'.$Marque.'</td></tr>
            <tr><td><strong>Designation</strong></td><td>'.$Designation.'</td></tr>
            <tr><td><strong>Hauteur</strong></td><td>'.$Htr.'</td></tr>
            <tr><td><strong>Famille</strong></td><td>'.$Famille.'</td></tr>
            <tr><td><strong>Gencod</strong></td><td>'.$Gencod.'</td></tr>
        </tbody>
    </table>
    ';

$short = '<p>
            Vélo '.$Marque.' '.$Designation.'<br>
            <strong>Batterie : </strong>'.$Batterie.'<br>
            <strong>Autonomie : </strong>'.$Autonomie.'</p>';
    
    if ($plus1 <> "" && $plus2 <> "" && $plus3 <> "") {
        $short .= '<p><strong>Les plus produit : </strong>';
    }
            
    if ($plus1 <> "") {
        $short .= '<br>'.$plus1;
    }
    
    if ($plus2 <> "") {
                $short .= '<br>'.$plus2;
            }
    
    if ($plus3 <> "") {
                $short .= '<br>'.$plus3;
            }
    if ($plus1 <> "" || $plus2 || "" || $plus3 <> "") {
        $short .= '</p>';
    }
    
    $short = trim($short);
    $ref = trim($ref);

    
  
    $line = array($Ref,$desc,$short);
    fputcsv($fp, $line,';');

}