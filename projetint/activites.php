<?php
    declare(strict_types=1);
    $custom_style = "article{background: white;}\n
                    p{color:black;}\n
                    div {\n
                        border: 1px solid #ccc;\n
                        padding: 10px;\n
                        margin: 10px;\n
                        border-radius: 5px;\n
                    }\n
            
                    p {\n
                        margin: 0;\n
                        padding: 5px;\n
                        background-color: white;\n
                    }\n
            
                    p:nth-child(odd) {\n
                        background-color: #f2f2f2;\n
                    }\n";
    $index = "activites.php";
    require('./include/header.inc.php');
?>
<script>
    function validateForm() {
        // Récupérer l'heure actuelle
        var maintenant = new Date();
        var heureActuelle = maintenant.getHours();
        var minuteActuelle = maintenant.getMinutes();

        // Récupérer l'heure saisie par l'utilisateur
        var champHeure = document.getElementById('heure');
        var heureUtilisateur = champHeure.value.split(':');
        var heureSaisie = parseInt(heureUtilisateur[0], 10);
        var minuteSaisie = parseInt(heureUtilisateur[1], 10);
            
        // Comparer l'heure saisie avec l'heure actuelle
        if (heureSaisie < heureActuelle || (heureSaisie === heureActuelle && minuteSaisie <= minuteActuelle)) {
            alert("Veuillez saisir une heure supérieure à l'heure actuelle.");
            return false; // Empêcher la soumission du formulaire
        }

        return true; // Autoriser la soumission du formulaire
    }

    function reserver(heureDebut,date_reserve,idsalleactivite) {
        var id_salle_activite = idsalleactivite;
        var date_reserve = date_reserve;
        var heuredebut = heureDebut;

        // Créer un formulaire dynamique
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = './activites.php'; // Spécifiez la page de destination
        form.style.display = 'none';

        // Ajoutez des champs au formulaire
        var inputVar1 = document.createElement('input');
        inputVar1.type = 'hidden';
        inputVar1.name = 'date_reserve';
        inputVar1.value = date_reserve;
        form.appendChild(inputVar1);

        var inputVar2 = document.createElement('input');
        inputVar2.type = 'hidden';
        inputVar2.name = 'id_salle_activite';
        inputVar2.value = id_salle_activite;
        form.appendChild(inputVar2);

        var inputVar3 = document.createElement('input');
        inputVar3.type = 'hidden';
        inputVar3.name = 'heuredebut';
        inputVar3.value = heuredebut;
        form.appendChild(inputVar3);

        // Ajouter le formulaire à la page
        document.body.appendChild(form);

        // Soumettez le formulaire
        form.submit();
    }
  </script>

<main class="overlay">
    <section>
        <div>
            <form action="./activites.php" method="post" onsubmit="return validateForm()">
                <?php
                    if (isset($_POST['date_reserve']) && $_POST['date_reserve'] != '') {
                        echo reserverAct($_POST['heuredebut'],$_POST['date_reserve'],$_POST['id_salle_activite'],$idclient);
                        echo '<script>';
                        echo "reserver('','','')";
                        echo '</script>';
                    }elseif (isset($_POST['date_reserve']) && $_POST['date_reserve'] == '') {
                        echo "<p><strong>reservation faite</strong></p>\n";
                    }
                ?>
                <p><strong>Pour voir toutes les diponibilités d'aujourd'hui et reserver veuillez entrer l'heure de début souhaitée</strong></p>
                <label for="activite">Activité:</label>
                <select name="activite" id="activite">
                    <option value="indifferent">Indifférent</option>
                    <option value="TENNIS">Tennis</option>
                    <option value="FOOTBALL">FootBall</option>
                    <option value="BASKETBALL">BasketBall</option>
                    <option value="DESSIN">Dessin</option>
                    <option value="MUSIQUE">Musique</option>
                </select>

                <label for="heure_debut">Heure debut souhaitée :</label>
                <input type="time" id="heure_debut" name="heure_debut" required>

                <input type="submit" value="Filtrer">
            </form>
        </div>
        <?php
            if (isset($_POST['heure_debut'])) {
                $typeactivite = $_POST['activite'];
                $heure_debut = $_POST['heure_debut'].':00';
                afficheDispoAct($typeactivite,$heure_debut,$idclient);
            }else {
                echo "<h2>Nos modèles d'activités</h2>\n
                    <article>\n
                    <figure>\n
                        <img src=\"./images/tennis.jpg\" alt=\"tennis\">\n
                    </figure>\n
                    <div>\n
                        <p>activité : tennis</p>\n
                        <p>durée : 1h</p>\n
                        <p>age minimum : 4 ans</p>\n
                    </div>\n
                    </article>\n
                    <article>\n
                    <figure>\n
                        <img src=\"./images/football.jpg\" alt=\"football\">\n
                    </figure>\n
                    <div>\n
                        <p>activité : football</p>\n
                        <p>durée : 1h30min</p>\n
                        <p>age minimum : 5 ans</p>\n
                    </div>\n
                    </article>\n
                    <article>\n
                    <figure>\n
                        <img src=\"./images/basketball.jpg\" alt=\"basketball\">\n
                    </figure>\n
                    <div>\n
                        <p>activité : basketball</p>\n
                        <p>durée : 1h</p>\n
                        <p>age minimum : 5 ans</p>\n
                    </div>\n
                    </article>\n
                    <article>\n
                    <figure>\n
                        <img src=\"./images/dessin.jpg\" alt=\"dessin\">\n
                    </figure>\n
                    <div>\n
                        <p>activité : dessin</p>\n
                        <p>durée : 1h</p>\n
                        <p>age minimum : 3 ans</p>\n
                    </div>\n
                    </article>\n
                    <article>\n
                    <figure>\n
                        <img src=\"./images/musique.jpg\" alt=\"musique\">\n
                    </figure>\n
                    <div>\n
                        <p>activité : musique</p>\n
                        <p>durée : 1h</p>\n
                        <p>age minimum : 5 ans</p>\n
                    </div>\n
                    </article>\n";
            }
            
        ?>
    </section>
</main>

    <?php require('./include/footer.inc.php'); ?>