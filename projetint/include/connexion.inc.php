<section>
    <h2>connexion</h2>
    <article>
        <h3>Connectez-vous</h3>        
        <form method="post">
            <?php
                if (isset($_GET['connexion']) && $_GET['connexion'] == "failed") {
                    echo '<p>idclient ou mot de passe incorrect</p>';
                }
                
            ?>
            <label for="id_client">ID Client :</label>
            <input type="text" id="id_client" name="id_client" required>

            <label for="mot_de_passe">Mot de passe :</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" required>

            <input type="submit" value="Se connecter">
            <div id="inscription-link">
                <p>Vous n'avez pas de compte ? <a href="../connecter.php?mode=inscription">Inscrivez-vous ici</a>.</p>
            </div>
        </form>
    </article>
</section>
