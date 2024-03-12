<?php
    declare(strict_types=1);
    require_once('./include/fonctions.inc.php');
    if (isset($_POST['id_client']) && isset($_POST['mot_de_passe'])) {
        // Start the session
        session_start();

        // Validate and sanitize user inputs
        $idClient = filter_var($_POST['id_client'], FILTER_SANITIZE_STRING);
        $motDePasse = filter_var($_POST['mot_de_passe'], FILTER_SANITIZE_STRING);

        if ($idClient !== false && $motDePasse !== false) {
            // Use prepared statements to prevent SQL injection
            $html = connecter($idClient, $motDePasse);

            // Redirect the user based on the result
            if ($html === '') {
                $_SESSION['idclient'] = $idClient;
                header("Location: ../index.php");
                exit();
            } else {
                header("Location: ../connecter.php?connexion=failed");
                exit();
            }
        } else {
            // Handle invalid or missing input values
            header("Location: ../connecter.php?connexion=failed");
            exit();
        }
    }

    $custom_style = "main{height: 100vh;}\n";
    $index = "connecter.php";
    require('./include/header.inc.php');
?>

<main class="overlay">
<?php
    if (isset($_GET['mode'])) {
        if ($_GET['mode'] == 'inscription') {
            require('./include/inscription.inc.php');
        }
    }else {
        require('./include/connexion.inc.php');
    }
?>
</main>

    <?php require('./include/footer.inc.php'); ?>