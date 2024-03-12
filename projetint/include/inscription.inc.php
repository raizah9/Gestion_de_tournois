<?php
    declare(strict_types=1);

    require_once('./include/fonctions.inc.php');
?>


<section>
    <h2>Inscription</h2>
    <article>
        <h3>Inscrivez-vous</h3>
        <form method="post">
            <?php
                // Vérifier si le formulaire a été soumis
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nom'])) {
                    echo inscrire($_POST['nom'],$_POST['prenom'],$_POST['sexe'],$_POST['num_passeport']
                    ,$_POST['nationalite'],$_POST['num_telephone'],$_POST['birthdate'],$_POST['mot_de_passe']);
                }
            ?>
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required>

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" required>

            <label for="sexe">Sexe :</label>
            <select id="sexe" name="sexe" required>
                <option value="HOMME">Homme</option>
                <option value="FEMME">Femme</option>
                <!-- Ajoutez d'autres options selon vos besoins -->
            </select>

            <label for="num_passeport">Numéro de passeport :</label>
            <input type="text" id="num_passeport" name="num_passeport" maxlength="8" required>

            <label for="nationalite">Nationalité :</label>
            <input type="text" id="nationalite" name="nationalite" maxlength="50" required>

            <label for="num_telephone">Numéro de téléphone :</label>
            <input type="tel" id="num_telephone" name="num_telephone" pattern="[0-9]+" maxlength="10" required>

            <label for="birthdate">Date de naissance :</label>
            <input type="date" id="birthdate" name="birthdate" required>

            <label for="mot_de_passe">Mot de passe :</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" required>

            <input type="submit" value="S'inscrire">
            <div id="inscription-link">
                    <p>Vous avez déja un compte ? <a href="../connecter.php">Connectez-vous ici</a>.</p>
            </div>
        </form>
    </article>
</section>
