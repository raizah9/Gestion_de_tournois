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
    $index = "reserver.php";
    require('./include/header.inc.php');
?>
<script>
    function validateForm() {
        var prixMin = parseFloat(document.getElementById('prix_min').value);
        var prixMax = parseFloat(document.getElementById('prix_max').value);
        var dateEntree = new Date(document.getElementById('date_entree').value);
        var dateSortie = new Date(document.getElementById('date_sortie').value);

        if (dateEntree >= dateSortie) {
            alert("La date de sortie doit être postérieure à la date d'entrée.");
            return false;
        }
        if (prixMin > prixMax) {
            alert("Le prix minimum doit être inferieure au prix maximum.");
            return false;
        }

        return true;
    }

    function reserver(dateEntreee,dateSortiee,idChambre) {
        var date_entree_reserve = dateEntreee;
        var date_sortie_reserve = dateSortiee;
        var idchambre = idChambre;

        // Créer un formulaire dynamique
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = './reserver.php'; // Spécifiez la page de destination
        form.style.display = 'none';

        // Ajoutez des champs au formulaire
        var inputVar1 = document.createElement('input');
        inputVar1.type = 'hidden';
        inputVar1.name = 'date_entree_reserve';
        inputVar1.value = date_entree_reserve;
        form.appendChild(inputVar1);

        var inputVar2 = document.createElement('input');
        inputVar2.type = 'hidden';
        inputVar2.name = 'date_sortie_reserve';
        inputVar2.value = date_sortie_reserve;
        form.appendChild(inputVar2);

        var inputVar3 = document.createElement('input');
        inputVar3.type = 'hidden';
        inputVar3.name = 'idchambre';
        inputVar3.value = idchambre;
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
            <form action="./reserver.php" method="post" onsubmit="return validateForm()">
                <?php
                    if (isset($_POST['date_entree_reserve']) && $_POST['date_entree_reserve'] != '') {
                        echo reserverRoom($_POST['date_entree_reserve'],$_POST['date_sortie_reserve'],$_POST['idchambre'],$idclient);
                        echo '<script>';
                        echo "reserver('','','')";
                        echo '</script>';
                    }elseif (isset($_POST['date_entree_reserve']) && $_POST['date_entree_reserve'] == '') {
                        echo "<p><strong>reservation faite</strong></p>\n";
                    }
                ?>
                <p><strong>Pour voir toutes les diponibilités et reserver veuillez entrer la date d'entrée et la date de sortie</strong></p>
                <label for="type_chambre">Type de chambre:</label>
                <select name="type_chambre" id="type_chambre">
                    <option value="indifferent">Indifférent</option>
                    <option value="SIMPLE">Simple</option>
                    <option value="DOUBLE">Double</option>
                    <option value="SUITE">Suite</option>
                </select>

                <label for="animaux">Animaux acceptés:</label>
                <select name="animaux" id="animaux">
                    <option value="indifferent">Indifférent</option>
                    <option value="true">Acceptés</option>
                    <option value="false">Non acceptés</option>
                </select>

                <label for="prix_min">Prix minimum:</label>
                <input type="number" name="prix_min" min="0" id="prix_min">

                <label for="prix_max">Prix maximum:</label>
                <input type="number" name="prix_max" min="0" id="prix_max">

                <label for="date_entree">Date d'entrée :</label>
                <input type="date" id="date_entree" name="date_entree" min="<?php echo date('Y-m-d'); ?>" required>

                <label for="date_sortie">Date de sortie :</label>
                <input type="date" id="date_sortie" name="date_sortie" min="<?php echo date('Y-m-d'); ?>" required>

                <input type="submit" value="Filtrer">
            </form>
        </div>
        <?php
            if (isset($_POST['date_entree'])) {
                $typeChambre = $_POST['type_chambre'];
                $animaux = $_POST['animaux'];
                $prixMin = $_POST['prix_min'];
                $prixMax = $_POST['prix_max'];
                $date_entree = $_POST['date_entree'];
                $date_sortie = $_POST['date_sortie'];
                afficheDispoRooms($date_sortie,$date_entree,$typeChambre,$animaux,$prixMin,$prixMax);
            }else {
                echo "<h2>Nos modèle de chambres</h2>\n
                    <article>\n
                    <figure>\n
                        <img src=\"./images/chambre_simple.jpg\" alt=\"chambre simple\">\n
                    </figure>\n
                    <div>\n
                        <p>type chambre : chambre simple</p>\n
                        <p>animaux : généralement acceptés</p>\n
                        <p>prix : environ de 90 euros</p>\n
                    </div>\n
                    </article>\n
                    <article>\n
                    <figure>\n
                        <img src=\"./images/chambre_double.jpg\" alt=\"chambre double\">\n
                    </figure>\n
                    <div>\n
                        <p>type chambre : chambre double</p>\n
                        <p>animaux : généralement acceptés</p>\n
                        <p>prix : environ de 140 euros</p>\n
                    </div>\n
                    </article>\n
                    <article>\n
                    <figure>\n
                        <img src=\"./images/suite.jpg\" alt=\"suite\">\n
                    </figure>\n
                    <div>\n
                        <p>type chambre : suite</p>\n
                        <p>animaux : acceptés</p>\n
                        <p>prix : environ de 200 euros</p>\n
                    </div>\n
                    </article>\n";
            }
            
        ?>
    </section>
</main>

    <?php require('./include/footer.inc.php'); ?>