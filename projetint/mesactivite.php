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
    $index = "mesactivite.php";
    require('./include/header.inc.php');
?>

<main class="overlay">
<section>
        <h2>Vos reservations d'aujourdh'hui</h2>
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
            $query = "DELETE FROM reserve WHERE date_reservation != '$toDay';";
            $result = pg_query($conn, $query);
            if (!$result) {
                die("Check query failed: " . pg_last_error());
            }

            $query = "SELECT * FROM reserve r
            JOIN salleactivite sa ON r.idsalleactivite = sa.idsalleactivite
            JOIN salle s ON s.idsalle = sa.idsalleactivite
            JOIN pratique p ON r.idsalleactivite = p.idsalleactivite AND r.heure_debut = p.heure_debut
            JOIN activite a ON p.idactivite = a.idactivite
            WHERE idclient = '$idclient';";
            $result = pg_query($conn, $query);
            if (!$result) {
                die("Check query failed: " . pg_last_error());
            }
            $imgSrc = '';
            $alt = '';
            $acces_handicap = '';
            while ($row = pg_fetch_assoc($result)) {
                $idsalleactivite = $row['idsalleactivite'];
                $heureDebut = $row['heure_debut'];
                switch ($row['type_activite']) {
                    case 'TENNIS':
                        $imgSrc = "./images/tennis.jpg";
                        $alt = "tennis";
                        break;
                    case 'FOOTBALL':
                        $imgSrc = "./images/football.jpg";
                        $alt = "football";
                    break;
                    case 'BASKETBALL':
                        $imgSrc = "./images/basketball.jpg";
                        $alt = "basketball";
                    break;
                    case 'DESSIN':
                        $imgSrc = "./images/dessin.jpg";
                        $alt = "dessin";
                    break;
                    case 'MUSIQUE':
                        $imgSrc = "./images/musique.jpg";
                        $alt = "musique";
                    break;
                    default:
                        break;
                }
                if ($row['acces_handicap'] == 't') {
                    $acces_handicap = "accessible";
                }else {
                    $acces_handicap = "non accessible";
                }
                echo "<article>\n
                    <figure>\n
                        <img src=\"".$imgSrc."\" alt=\"".$alt."\">\n
                    </figure>\n
                    <div>\n
                        <p>activite : ".$row['type_activite']."</p>\n
                        <p>durée : ".$row['dure']."</p>\n
                        <p>age minimum : ".$row['age_minimum']."</p>\n
                        <p>nombre de participant : ".$row['nb_participant']."</p>\n
                        <p>heure debut : ".$row['heure_debut']."</p>\n
                        <p>etage : ".$row['etage']." , salle: ".$row['no_salle']."</p>\n
                        <p>accés handicaps : ".$acces_handicap."</p>\n
                    </div>\n
                    </article>\n";
            }
        ?>
    </section>
</main>

    <?php require('./include/footer.inc.php'); ?>