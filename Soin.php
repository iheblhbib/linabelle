<?php
    session_start();
    if (  isset($_SESSION["nom"]) || !empty($_SESSION["nom"]) )
    {
        $nom_id = $_SESSION['nom'];
        $prenom_id = $_SESSION['prenom'] ; 
        $id_id = $_SESSION['id']; 
    }
     try{
        $con = mysqli_connect("localhost","root","","lina_belle","3308");                
    } catch (Exception $e){
        die('Erreur : '.$e->getMessage());
    }

    $id_categ = $_GET['id_categorie'];
    
   $req_prods = mysqli_query($con,"SELECT nom_type, image_type,id_t
                                 FROM type
                                WHERE  id_categorie = '$id_categ'
                                ORDER BY RAND();");   

    $req_details = mysqli_query($con,"SELECT *
                                FROM categorie
                                WHERE  id_ct = $id_categ");
    $details = mysqli_fetch_array($req_details);

    if(!empty($_SESSION['id'])){
        $query7 = mysqli_query($con,"SELECT id_prod, nom_type , nom , prix , quantite
                                    FROM  produits
                                    JOIN type  ON (id_type = id_t)
                                    JOIN pannier  ON (id_p = id_prod)
                                    WHERE id_client=$id_id AND (id_type = id_t) AND (id_p = id_prod)") ;
        
        $query8 = mysqli_query($con,"SELECT prix , quantite
                                        FROM  produits
                                        JOIN type  ON (id_type = id_t)
                                        JOIN pannier  ON (id_p = id_prod)
                                        WHERE id_client=$id_id AND (id_type = id_t) AND (id_p = id_prod)") ;
        $qty = 0;
        while($req = mysqli_fetch_array($query8)){
            $qty += $req['prix']*$req['quantite'];
        }
    }

    if(isset($_GET['del'])){
        $id_s = $_GET['del'];
        $deletion = mysqli_query($con,"DELETE FROM pannier WHERE id_prod=$id_s");
        if($deletion){
            header('Location: Soin?id_categorie=1');
        }
    }      
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="image/x-icon" href="images/title.png" />
    <title>Lina Belle Consmetics</title>

    <!-- mobile responsive meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- ** Plugins Needed for the Project ** -->
    <!-- Bootstrap -->
    <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/themify/css/themify-icons.css">
    <link rel="stylesheet" href="plugins/counto/animate.css">
    <link rel="stylesheet" href="plugins/aos/aos.css">
    <link rel="stylesheet" href="plugins/owl-carousel/owl.carousel.min.css">
    <link rel="stylesheet" href="plugins/owl-carousel/owl.theme.default.min.css">
    <link rel="stylesheet" href="plugins/magnific-popup/magnific-popup.css">
    <link rel="stylesheet" href="plugins/animated-text/animated-text.css">

    <!-- Main Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="css/popup_panier.css">
    <link rel="stylesheet" href="css/popup_achat.css">

</head>

<body>

    <!-- Header Start -->
    <nav class="navbar navbar-expand-lg  main-nav " id="navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="images/linabelle.png" alt="" class="img-fluid">
            </a>

            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
		<span class="ti-align-justify"></span>
	  </button>

            <div class="collapse navbar-collapse" id="navbarsExample09">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item  active"><a class="nav-link" href="index.php">Accueil</a></li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="Produits.php" id="dropdown01"  aria-haspopup="true" aria-expanded="false">Nos Produits</a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown01">
                            <li><a class="dropdown-item" href="Soin?id_categorie=1">Soins du Cheveux</a></li>
                            <li><a class="dropdown-item" href="Soin?id_categorie=2">Soin du Visage</a></li>
                            <li><a class="dropdown-item" href="Soin?id_categorie=3">Soin du peau</a></li>
                            <li><a class="dropdown-item" href="Soin?id_categorie=4">Déodorants et Antibactérien</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown active">
                        <a class="nav-link dropdown-toggle" href="Soin?id_categorie=5" id="dropdown02" aria-haspopup="true" aria-expanded="false">Pour Hommes</a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown02">
                            <li><a class="dropdown-item" href="produit?id_t=10">Rasage et aprés rasage</a></li>
                            <li><a class="dropdown-item" href="Coiffante Homme.php">Coiffante homme</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown05" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">A propos</a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown05">
                            <li><a class="dropdown-item" href="About-us.php">Qui Sommes-nous</a></li>
                            <li><a class="dropdown-item" href="Contact.php">Contactez-nous</a></li>
                        </ul>
                    </li>
                    <?php 
                        if ( ! isset($_SESSION["nom"]) || empty($_SESSION["nom"]) ){
                            echo '
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="dropdown05" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Connectez-Vous</a>
                                <ul class="dropdown-menu" aria-labelledby="dropdown05">
                                    <li><a class="dropdown-item" href="inscription.php" >Créer un compte<img src="images/logo/sticker.png"></a></li>
                                    <li><a class="dropdown-item" href="connection.php">Connectez-Vous<img src="images/logo/login.png"></a></li>
                                </ul>
                            </li>';
                        } else {
                            echo '
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="dropdown05" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$nom_id.' '.$prenom_id.'</a>
                                <ul class="dropdown-menu" aria-labelledby="dropdown05">
                                    <li><a class="dropdown-item" href="Historique.php"><img src="images/logo/paper.png"> Historique</a></li>
                                    <li><a class="dropdown-item" href="Parametre.php"><img src="images/logo/gear.png"> Paramétre</a></li>
                                    <li><a class="dropdown-item" href="deconnecter.php"><img src="images/logo/logout.png"> Déconnecter</a></li>
                                </ul>
                            </li>';
                        }
                    ?> 
                </ul>
                <div class="popup" onclick="myFunction()">
                    <a onclick="document.getElementById('modal-wrapper').style.display='block '" name="pannier"><img src="images/online-store.png" ></a>
                </div>
                <div id="modal-wrapper" class="modal">

                    <form class="modal-content animate" action="payement.php">

                        <div class="imgcontainer">
                            <span onclick="document.getElementById('modal-wrapper').style.display='none'" class="close" title="Close PopUp">&times;</span>
                            <h1 style="text-align:center">Votre panier</h1>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                <th scope="col">#</th>                                
                                <th scope="col">Produit</th>
                                <th scope="col">Nom de produit</th>
                                <th scope="col">Prix</th>
                                <th scope="col">Quantité</th>
                                <th scope="col">Retirer</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                    $n=0;
                                    if(!empty($_SESSION['id'])){
                                    while($liste = mysqli_fetch_array($query7)){
                                        $n++;
                                ?>
                                    <tr>
                                    <th scope="row"><?php echo $n ?></th>
                                    <td><?php echo $liste['nom_type']; ?></td>
                                    <td><?php echo $liste['nom']; ?></td>
                                    <td><?php echo $liste['prix']; ?></td>                                
                                    <td><?php echo $liste['quantite']; ?></td>
                                    <td><a href="Soin?id_categorie=<?php echo $id_categ; ?>&del=<?php echo $liste['id_prod']; ?>" class="del"><img src="images/trash.png" ></a></td>
                                    </tr>';
                                <?php   } 
                                    }
                                ?>
                                
                            </tbody>
                        </table>  
                        <div class="row justify-content-center">
                            <div class="form-row" > 
                                <div class="modal-footer">

                                    <div class="col-lg-6">                                                              
                                        <div class="text-center">
                                        <input type="submit" name="payer"  class="btn btn-main" value="Acheter les produits">     
                                        </div>
                                    </div>

                                    <div class="input-group mb-3 col-lg-6">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Total</span>
                                        </div>                                                        
                                        <input type="text" class="form-control text-sm-right" readonly value="<?php  if(!empty($_SESSION['id'])){ echo $qty; }?>" /> 
                                        <div class="input-group-append">
                                            <span class="input-group-text">.00 DA</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>                  
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header Close -->

    <section class="page-title section pb-0">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center ">
                    <h2 class="display-4"><?php echo $details['description'] ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </section> 
    
   
    <section class=" portfolio ">
        <div class="container">
            <div class="row shuffle-wrapper portfolio-gallery">
            <?php 
            while($row = mysqli_fetch_array($req_prods)){
             ?>
                <div class="col-lg-3 col-4 mb-4 shuffle-item">
                    <div class="position-relative inner-box">
                        <div class="image position-relative ">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image_type']);?>" class="img-fluid w-100 d-block">     
                            <div class="overlay-box">
                                <div class="overlay-inner">
                                    <div class="overlay-content">
                                    <a class="overlay-content" href="Produit?id_t=<?php echo $row['id_t'];  ?>">
                                            <h5 class="mb-0"><?php echo $row['nom_type']; ?></h5>
                                            <p>Décourvez</p>                                            
                                    </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
            }
 ?>
                
            </div>
        </div>

    </section>

   <!-- Footer start -->
 <section class="footer">
        <div class="container">
            <div class="row ">
                <div class="col-lg-6">
                    <p class="mb-0">Copyrights © 2020. Lina Belle Cosometics</p>
                </div>
                <div class="col-lg-6">
                    <div class="widget footer-widget text-lg-right mt-5 mt-lg-0">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item"><a href="https://www.facebook.com/Lina.Belle.Cosmetics" target="_blank"><i class="ti-facebook mr-3"></i></a>
                            </li>
                            <li class="list-inline-item"><a href="https://www.instagram.com/lina.belle.cosmetics/" target="_blank"><i class="ti-instagram mr-3"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer End -->
    <!-- jQuery -->
    <script src="plugins/jQuery/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="plugins/bootstrap/bootstrap.min.js"></script>
    <script src="plugins/aos/aos.js"></script>
    <script src="plugins/owl-carousel/owl.carousel.min.js"></script>
    <script src="plugins/shuffle/shuffle.min.js"></script>
    <script src="plugins/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="plugins/animated-text/animated-text.js"></script>
    <script src="plugins/counto/apear.js"></script>
    <script src="plugins/counto/counTo.js"></script>

    <!-- Google Map -->
    <script src="plugins/google-map/map.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkeLMlsiwzp6b3Gnaxd86lvakimwGA6UA&callback=initMap"></script>
    <!-- Main Script -->
    <script src="js/script.js"></script>
    <?php 
    mysqli_close($con);
 ?>
</body>
</html>