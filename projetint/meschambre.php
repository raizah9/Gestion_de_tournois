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
    $index = "meschambre.php";
    require('./include/header.inc.php');
?>

<main class="overlay">
    <section>
        <h2>Vos reservations de chambres à venir</h2>
        <?php
            $host = "postgresql-guerrouf.alwaysdata.net";      // Provided by your hosting provider
            $port = "5432";      // Provided by your hosting provider
            $dbname = "guerrouf_projetbdreseau"; // Provided by your hosting provider
            $user = "guerrouf";   // Provided by your hosting provider
            $password = "wwCs82*A"; // Provided by your hosting provider
        
            // Create a connection to the PostgreSQL database
            $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
        
            if (!$conn) {
                die("Connection failed: " . pg_last_error());
            }
            $toDay = date("Y-m-d");
            $query = "DELETE FROM atribue WHERE date_sortie < '$toDay';";
            $result = pg_query($conn, $query);
            if (!$result) {
                die("Check query failed: " . pg_last_error());
            }

            $query = "SELECT * FROM atribue a
            JOIN chambre c ON a.idchambre = c.idchambre
            JOIN salle s ON s.idsalle = c.idchambre
            LEFT JOIN ouvrir o ON c.idchambre = o.idsalle
            WHERE idclient = '$idclient';";
            $result = pg_query($conn, $query);
            if (!$result) {
                die("Check query failed: " . pg_last_error());
            }
            $imgSrc = '';
            $alt = '';
            $animaux = '';
            while ($row = pg_fetch_assoc($result)) {
                switch ($row['type_chambre']) {
                    case 'SIMPLE':
                        $imgSrc = "./images/chambre_simple.jpg";
                        $alt = "chambre simple";
                        break;
                    case 'DOUBLE':
                        $imgSrc = "./images/chambre_double.jpg";
                        $alt = "chambre double";
                    break;
                    case 'SUITE':
                        $imgSrc = "./images/suite.jpg";
                        $alt = "suite";
                    break;
                    default:
                        break;
                }
                if ($row['animaux'] == 't') {
                    $animaux = "acceptés";
                }else {
                    $animaux = "réfusés";
                }
                if ($row['date_derniere_ouverture'] == NULL) {
                    $date_derniere_ouverture = 'jamais ouverte';
                }else {
                    $timestamp = strtotime($row['date_derniere_ouverture']);
                    $date_derniere_ouverture = date('Y-m-d H:i:s', $timestamp);
                }
                echo "<article>\n
                    <figure>\n
                        <img src=\"".$imgSrc."\" alt=\"".$alt."\">\n
                    </figure>\n
                    <div>\n
                        <p>type chambre : ".$row['type_chambre']."</p>\n
                        <p>animaux : ".$animaux."</p>\n
                        <p>description : ".$row['description']."</p>\n
                        <p>etage : ".$row['etage'].", salle ".$row['no_salle']."</p>\n
                        <p>date d'entrée : ".$row['date_entre'].", date de sortie : ".$row['date_sortie']."</p>\n
                        <p>date derniere ouverture de la chambre : ".$date_derniere_ouverture."</p>\n
                        <p>prix : ".$row['prix']."</p>\n
                    </div>\n
                    </article>\n";
            }
        ?>
    </section>
</main>

    <?php require('./include/footer.inc.php'); ?>