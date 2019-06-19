<?php 

// comparaison des SKU en ligne VS sku dans le stock findis
// la référence est le fichier EN LIGNE
// le script POURFINDIS DOIT ETRE LANCE AVANT
////////////////////////////////////////////////


ini_set('display_errors',1); error_reporting(E_ALL);

include('includes/conn_findis.php');

$countEnLigne = 0 ;
$countTotal = 0 ;
$countHs = 0 ;

// Pour chaque produit en ligne ...
// pour Magento SKU = référence constructeur
// Pour Findis, c'est la référence constructeur qui fait office de SKU

$sql = $mysqli->query("SELECT sku FROM pourFindis WHERE supplier = 'FINDIS'");

if (!$sql) {
    printf('Erreur : %s\n', $mysqli->error);
    exit();
}

while ($result = $sql->fetch_assoc()) {
    $sku = $result['sku'];
    
    // check si présent dans le fichier FINDIS
    // 
    
    $sql2 = $mysqli->query("SELECT sku, stock FROM produits WHERE refConstruct = '$sku'");
    
    if (!$sql2) 
    {
        printf('Erreur : %s\n', $mysqli->error);
        exit();
    }
    
    $result2 = $sql2->num_rows;

    if ($result2 > 0) 
    {
        $countEnLigne ++ ;
        
        $result3 = $sql2->fetch_assoc();
        $stock = $result3['stock'];
        
        if ($stock < 1 )
        {
            $countHs ++ ;
        }

    }

    $countTotal ++ ;

    
}

echo 'Sur les '.$countTotal.' présents dans sur Magento, '.$countEnLigne.' produits Findis sont en ligne.'.$countHs.' sont en rupture chez Findis.';
