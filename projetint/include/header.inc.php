<?php
declare(strict_types=1);
require_once('./include/fonctions.inc.php');
session_start();

if ($index == 'profile.php' && !isset($_SESSION['idclient'])) {
    header("Location: ./index.php");
    exit();
}

if (($index == 'reserver.php' || $index == 'meschambre.php' || $index == 'activites.php' || $index == 'mesactivite.php') && !isset($_SESSION['idclient'])) {
    header("Location: ./connecter.php");
    exit();
}

$idclient ='';
if (isset($_SESSION['idclient'])) {
    $idclient = $_SESSION['idclient'];
}

if (isset($_SESSION['idclient']) && isset($_GET['deconnexion']) && $_GET['deconnexion'] == 'oui') {
    session_destroy();
    unset($_SESSION['idclient']);
}

// Liste des pages du site
$pages = array('index.php' => 'Accueil','reserver.php' => 'resérver','meschambre.php' => 'Mes chambre','activites.php' => 'activités','mesactivite.php' => 'Mes activités','connecter.php' => 'connexion','profile.php' => 'profile');

// Génération de l'image du logo
$logo_path = './images/logo.png';
$logo_data = file_get_contents($logo_path);
$logo_base64 = base64_encode($logo_data);

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr">
<head>
    <meta charset="utf-8" />
    <title><?php if(isset($title)){
        echo $title;
    } else {
        echo $pages[$index];
    } ?> - DOM'IHOTEL</title>
    <link rel="icon" type="icon/ico" href="favicon.ico" />
    <meta name="description" content="DOM'IHOTEL : un site de gestion d'hotel." />
    <meta name="author" content="Amar GUERROUF" />
    <meta name="date" content="2023-03-23T15:00:00+0100" />
    <meta name="keywords" content="hotel, reserver, Amar GUERROUF, chambre, tennis, sport" />
    <link rel="stylesheet" href="styles/style.css" />
    <?php
    if(isset($custom_style)){
        if ($index == 'connecter.php') {
            if (!isset($_GET['mode'])) {
                echo "<!-- STYLE PERSONNALISE -->\n<style>\n$custom_style\n</style>\n<!-- FIN STYLE PERSONNALISE -->\n";
            }
        }else {
            echo "<!-- STYLE PERSONNALISE -->\n<style>\n$custom_style\n</style>\n<!-- FIN STYLE PERSONNALISE -->\n";
        }
        
    }
    ?>
</head>
<body>
    <!-- HEADER -->
    <header>
        <a href="index.php" title="Accueil - DOM'IHOTEL">
            <img src="data:image/png;base64,<?= $logo_base64 ;?>" alt="Logo du site DOM'IHOTEL" />
        </a>
        <nav>
            <ul>
<?php
    foreach($pages as $key => $object){
        if ($key == 'connecter.php' && !isset($_SESSION['idclient'])) {
            echo "\t\t\t\t<li><a href=\"$key\" title=\"Accéder à $object\">$object</a></li>\n";
        }elseif ($key == 'profile.php' && isset($_SESSION['idclient'])) {
            echo "\t\t\t\t<li><a href=\"$key\" title=\"Accéder à $object\">$object</a></li>\n";
        }elseif ($key != 'index.php' && $key != 'connecter.php' && $key != 'profile.php') {
            echo "\t\t\t\t<li><a href=\"$key\" title=\"Accéder à $object\">$object</a></li>\n";
        }
        
    }
?>
            </ul>
        </nav>
    </header>
