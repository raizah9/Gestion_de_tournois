<?php
    declare(strict_types=1);
    
    $index = "profile.php";
    require('./include/header.inc.php');
?>

<main class="overlay">
    <section>
        <h2>Vos informations</h2>
        <article>
            <form method="post">
            <?php
                // Vérifier si le formulaire a été soumis
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nom'])) {
                    echo miseAJour($idclient,$_POST['nom'],$_POST['prenom'],$_POST['sexe'],$_POST['num_passeport']
                    ,$_POST['nationalite'],$_POST['num_tel'],$_POST['birth_date']);
                }
                afficheProfile($idclient);
            ?>
            </form>
        </article>
    </section>
    
</main>

    <?php require('./include/footer.inc.php'); ?>