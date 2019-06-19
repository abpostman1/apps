<?php


//////////////////////////////////////////////
/// CONSTRUCTION DU CSV
//////////////////////////////////////////////

ini_set('display_errors',1); error_reporting(E_ALL);
ini_set("default_charset", "UTF-8");
//mb_internal_encoding("UTF-8");

$date = date('Ymd');
$orphans = 0;
$qtyInStock = 0;
$qtyOutStock = 0;
$countCommande = 0;

$file = __DIR__ .'/source/'.$date.'_maj_findis.csv';
//$source = $file;
//touch($file);
//chmod($file, 0777);

include (__DIR__ .'/includes/conn_findis.php');

$entete = array('store','websites','attribute_set','type','categories','sku','name','meta_title','meta_description','image','image_label','small_image','thumbnail','url_key','url_path','ean','shipping_delay','price','special_price','special_from_date','special_to_date','cost','weight','eco_participation','status','visibility','tax_class_id','color','brand','supplier_name','size','essorage','capacite','decibels','classe_energetique','description','short_description','meta_keyword','news_from_date','news_to_date','qty','is_in_stock','stock_status_changed_automatically','product_name','small_image_label','thumbnail_label','dispo','nb_couverts','reappro');

// ecriture de l'entete dans le csv

$fp = fopen($file, 'w+');
//fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
fputcsv($fp, $entete,';');

$sql = $mysqli->query("SELECT * FROM produits");




while ($result = $sql->fetch_assoc()) {
    
    $sku = $result['newSku'];
    
    // requête pour vérifier que le SKU n'est pas protégé

    $sql2 = $mysqli->query("SELECT protected FROM pourFindis WHERE sku = '$sku'");
    $result2 = $sql2->fetch_assoc();
    $testSku = $result2["protected"];
    
    // s'il n'est pas protégé, on ajoute le produit au CSV
    
    if ($testSku <> 1)
    {

        $categories = $result['path'];
        $categories = utf8_encode($categories);
        $image = $result['image'];
        $ean = (string)$result['ean'];
        $price = $result['newPv'];
        $cost = $result['pv'];
        $weight = $result['weight'];
        $eco_participation = $result['eco_participation'];
        $brand = $result['brand'];
        $essorage = $result['essorage'];
        $capacite = $result['capacite'];
        $decibels = $result['decibels'];
        $classe_energetique = $result['energie'];
        $description = $result['description'];
        $qty = $result['stock'];
        $dispo = $result['dispo'];

        $nb_couverts = $result['nb_couverts'];
        $name = $result['name'];
        $plusProduit = $result['plusProduit'];
        $reappro = $result['reappro'];

        $store = 'admin';
        $websites = 'base';
        $attribute_set = 'Findis';
        $type = 'simple';

        // si QTY ou en cours de reappro > 0
        
        if ( $qty > 0 || $reappro > 0 ) {
            $is_in_stock = 1;
            $status = 'enabled';
        }
        
        // si indispo et en cours de réappro : Le produit est considéré comme dispo avec délai 3 sem.
        
        elseif ( $qty <= 0 || $reappro > 0 )
        {
            $is_in_stock = 1;
            $qty = 99;
            $status = 'enabled';
            $dispo = "En cours de réapprovisionnement. Habituellement expédié sous 3 semaines";
        }
        
        // si indispo mais en SUR COMMANDE : contact COOP obligatoire
        
        elseif ( $dispo = "commande" && $qty <= 0)
        {
            $is_in_stock = 0;
            $status = 'disabled';
            $dispo = "Produit uniquement sur commande. Veuillez nous contacter pour vérifier la disponibilité.";
        }
        
        else {
            $is_in_stock = 0;
            $status = 'disabled';
        }
        
        $color = '';
        $supplier_name = 'FINDIS';
        $stock_status_changed_automatically = '1';
        $product_name = $name;
        $visibility = 'Catalogue, Recherche';
        $tax_class_id = 'Taxe Produits';
        $size = '';
        $news_from_date = '';
        $news_to_date = '';
        $meta_title = $name.' | Lacoop.fr';
        $meta_description = 'Achetez '.$name.' à petit prix sur LaCoop.fr. Paiement 3x à partir de 300€.';
        $image_label = $name.' - Lacoop.fr';
        $small_image = str_replace('/600/','/300/',$image);
        $small_image = str_replace('_600.jpg','_300.jpg',$small_image);
        $thumbnail = str_replace('/600/','/150/',$image);
        $thumbnail = str_replace('_600.jpg','_150.jpg',$thumbnail);
        $small_image_label = $name.' - Lacoop.fr';
        $thumbnail_label = $name.' - Lacoop.fr';

        $search = array(' ', '.','/',';',',');
        $replace = '-';

        $url_key = str_replace($search,$replace,strtolower($name));
        $url_key = str_replace('---', '-', $url_key);
        $url_key = str_replace('--', '-', $url_key);
        $url_path = $url_key.'.html';

        $shipping_delay = '';
        $special_price = '';
        $special_from_date = '';
        $special_to_date = '';
        $short_description = '<p>';


        // Construction de la short desc.
        //$short_description .= '<strong>'.$name.'</strong></p><p>';

        $short_description .= '<strong>Le + produit : </strong>'.$plusProduit.'</p>';

        if ($capacite > 0 and $capacite != '') {
            $short_description .= '<br>Capacité : '.$capacite;
        }
        if ($essorage > 0 and $essorage != '') {
            $short_description .= '<br>Essorage max. : '.$essorage;
        }
        if ($decibels > 0 and $decibels != '') {
            $short_description .= '<br>Volume sonore max. : '.$decibels;
        }
        if ($classe_energetique != '') {
            $short_description .= '<br>Classe énergétique : '.$classe_energetique;
        }
        if ($nb_couverts > 0 and $nb_couverts != '') {
            $short_description .= '<br>Nombre de couverts. : '.$nb_couverts;
        }
        $short_description .= '<br>Voir les détails produit ci-dessous</p>';

        //$short_description = $short_description;

        $meta_keyword = '';

        //http://www.findis.fr/data/photos/600/7332543430161_1_600.jpg

        if ($qty > 0 && $dispo <> "commande") 
        {
            $qtyInStock ++ ;
        }

        if ($qty <= 0)
        {
            $qtyOutStock ++ ;
        }

        if ($dispo == "commande")
        {
            $countCommande ++;
        }
        


        $line = array( $store,$websites,$attribute_set,$type,$categories,$sku,$name,$meta_title,$meta_description,$image,$image_label,$small_image,$thumbnail,$url_key,$url_path,$ean,$shipping_delay,$price,$special_price,$special_from_date,$special_to_date,$cost,$weight,$eco_participation,$status,$visibility,$tax_class_id,$color,$brand,$supplier_name,$size,$essorage,$capacite,$decibels,$classe_energetique,$description,$short_description,$meta_keyword,$news_from_date,$news_to_date,$qty,$is_in_stock,$stock_status_changed_automatically,$product_name,$small_image_label,$thumbnail_label,$dispo,$nb_couverts)
        ;

        //$line = array_map("utf8_encode", $line);

        // ecriture ligne produit dans le csv uniquement si le PATH est supérieure à 70 carac (catégories orphelines ou non enregistrée dans le référentiel sont inférieures à 70 caractères)


        if (strlen($categories) >= 70) {

            //echo $categories.'<br>';

            fputcsv($fp, $line,';');

        }

        else {
            $orphans ++;
        }
    }
}

//SendFtp($file);

fclose($fp);
//unlink($source);

echo '<br>'.$orphans.' produits ignorés<br>'.$qtyInStock.' produits en stock<br>'.$countCommande.' en précommande.<br>'.$qtyOutStock.' produits hors stock';

$mysqli->close();


