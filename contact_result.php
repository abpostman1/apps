<!DOCTYPE html>
<?php 
include ('includes/variables.php');
include ('includes/functions.php');
ini_set('display_errors',1); error_reporting(E_ALL);
session_start();

print_r($_SESSION);
// check si controle statut connexion et groupe OK
//CheckSession();


?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Résultat message</title>
		<!-- Bootstrap -->
		<link rel="stylesheet" href="css/bootstrap.css">
		<script src="https://www.google.com/recaptcha/api.js"></script>
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<!-- HEADER -->
	
		<?php include_once('includes/header.php'); ?>
		
		<!-- / HEADER --> 
		<!--  SECTION-1 -->
		
		<div class="jumbotron">
			<div class="container">
				
				<?php include_once('includes/info_conn.php'); ?>			

				<div class="row">
					<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 text-center">
						<h3>Erreur !</h3>
					</div>
				</div>

				<p>&nbsp;</p>
			</div>
			
			<div class="container">
				<div class="row">			
			
			<?php 
			if(isset($_GET['sent']) and $_GET['sent'] == 'ok') { 
				echo '<div class="alert alert-success">Votre message a bien été envoyé. Nous vous répondrons dans les meilleurs délais.</div>';
			}
			else {  
					echo '<div class="alert alert-danger">Erreur lors de l\'envoi de votre message. Veuillez procéder à un nouvel essai directement en bas de page.</div>';
			}
					?>
				</div>
			</div>
		</div>
		
		
		<section>
			<div class="row">
				<div class="col-lg-12 page-header text-center">
					<h2>FONCTIONNEMENT</h2>
				</div>
			</div>
			<div class="container ">
				<div class="row">
					<div class="col-lg-4 col-sm-12 text-center">
						<img class="img-circle" alt="140x140" style="width: 140px; height: 140px;" src="images/140X140.gif" data-holder-rendered="true">
						<h3>Connectez-vous</h3>
						<p>Accédez à la page de votre compte pour visualiser vos informations.</p>
					</div>
					<div class="col-lg-4 col-sm-12 text-center">
						<img class="img-circle" alt="140x140" style="width: 140px; height: 140px;" src="images/140X140.gif" data-holder-rendered="true">
						<h3>Visualisez votre coupon</h3>
						<p>Relevez le N° de coupon ou imprimez-le si vous le pouvez.</p>
					</div>
					<div class="col-lg-4 col-sm-12 text-center">
						<img class="img-circle" alt="140x140" style="width: 140px; height: 140px;" src="images/140X140.gif" data-holder-rendered="true">
						<h3>Faites vos achats en COOP</h3>
						<p>Rendez vous dans une COOP près de chez vous muni de votre coupon.</p>
					</div>
				</div>
				
			</div>
			

			<!-- / CONTAINER--> 
		</section>
		
		<?php include_once('includes/footer.php') ;?>
		
	</body>
</html>