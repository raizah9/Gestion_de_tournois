<?php
/**
 * @files
 * @author Amar GUERROUF
 * @version 1.0.0
 */
declare(strict_types=1);

function inscrire($nom, $prenom, $sexe, $numPass, $nationalite, $numTel, $birthDate, $mdp): String{
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

    // Check if the combination of nom and prenom already exists
    $checkQuery = "SELECT COUNT(*) FROM Client WHERE nom = $1 AND prenom = $2";
    $checkResult = pg_query_params($conn, $checkQuery, array($nom, $prenom));

    if (!$checkResult) {
        die("Check query failed: " . pg_last_error());
    }

    $count = pg_fetch_result($checkResult, 0);

    // If the combination already exists, handle accordingly
    if ($count > 0) {
        pg_close($conn);
        return '<p>Le nom et prénom existent déjà dans la base de données.</p>';
    }

    // Get the last client number
    $query = "SELECT idclient FROM client ORDER BY idclient DESC LIMIT 1";
    $result = pg_query($conn, $query);

    if (!$result) {
        die("Query failed: " . pg_last_error());
    }
    
    if (pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        $lastClientNum = intval(substr($row['idclient'], -3)) + 1;
        $newIdClient = "CL" . str_pad(strval($lastClientNum), 3, '0', STR_PAD_LEFT);
    } else {
        $newIdClient = "CL001";
    }
    
    

    // Hash the password
    $mdpHashed = password_hash($mdp, PASSWORD_DEFAULT);

    // Use prepared statements for insertion
    $insertQuery = "INSERT INTO Client (idClient, nom, prenom, mdp, sexe, num_passeport, nationalite, num_telephone, birth_date)
                    VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)";
    
    $insertResult = pg_query_params($conn, $insertQuery, array(
        $newIdClient,
        $nom,
        $prenom,
        $mdpHashed,
        $sexe,
        $numPass,
        $nationalite,
        $numTel,
        $birthDate
    ));

    if (!$insertResult) {
        die("Insertion failed: " . pg_last_error());
    }

    // Close the database connection
    pg_close($conn);

    $html = '<p>Your idclient is: ' . $newIdClient . ', <a href="https://guerrouf.alwaysdata.net/connecter.php">Connectez-vous ici</a>.</p>';

    return $html;
}



function connecter($idcl, $mdp): String{
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

    // Use prepared statements to prevent SQL injection
    $query = "SELECT mdp FROM client WHERE idclient = $1";
    $result = pg_query_params($conn, $query, array($idcl));

    // Check if the query was successful
    if (!$result) {
        die("Query failed: " . pg_last_error());
    }

    $html = '';

    // Fetch the data from the result
    $row = pg_fetch_assoc($result);

    if (!$row || !password_verify($mdp, $row['mdp'])) {
        $html = '<p>idclient ou mot de passe incorrect</p>';
    }

    // Close the database connection
    pg_close($conn);

    return $html;
}

function afficheProfile($idclient): void{
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

    // Use prepared statements to prevent SQL injection
    $query = "SELECT * FROM client WHERE idclient = $1";
    $result = pg_query_params($conn, $query, array($idclient));

    // Check if the query was successful
    if (!$result) {
        die("Query failed: " . pg_last_error());
    }
    $userInfo = pg_fetch_assoc($result);
    echo '<p>votre id client est : '.$userInfo['idclient'].'</p>';
    echo '<label for="nom">Nom:</label>';
    echo '<input type="text" id="nom" name="nom" value="' . $userInfo['nom'] . '" maxlength="255" required>';
    echo '<label for="prenom">Prénom:</label>';
    echo '<input type="text" id="prenom" name="prenom" value="' . $userInfo['prenom'] . '" required>';
    echo '<label for="sexe">Sexe:</label>';
    echo '<select id="sexe" name="sexe" value="'.$userInfo['sexe'].'" required>';
    echo '<option value="HOMME">Homme</option>';
    echo '<option value="FEMME">Femme</option>';
    echo '</select>';
    echo '<label for="nationalite">Nationalité:</label>';
    echo '<input type="text" id="nationalite" name="nationalite" value="' . $userInfo['nationalite'] . '" maxlength="50" required>';
    echo '<label for="num_passeport">Numéro de Passeport:</label>';
    echo '<input type="text" id="num_passeport" name="num_passeport" value="' . $userInfo['num_passeport'] . '" maxlength="8" required>';
    echo '<label for="num_tel">Numéro de Téléphone:</label>';
    echo '<input type="text" id="num_tel" name="num_tel" value="' . $userInfo['num_telephone'] . '" pattern="[0-9]+" maxlength="10" required>';
    echo '<label for="birth_date">Date de Naissance:</label>';
    echo '<input type="date" id=""birth_date name="birth_date" value="' . $userInfo['birth_date'] . '" required>';
    echo '<input type="submit" value="Mettre à jour">';
    echo '<a href="../index.php?deconnexion=oui">deconnexion</a>';
}

function miseAJour($idclient,$nom, $prenom, $sexe, $numPass, $nationalite, $numTel, $birthDate): String{
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

    // Check if the combination of nom and prenom already exists
    $checkQuery = "UPDATE client
    SET nom = $2,
        prenom = $3,
        sexe = $4,
        nationalite = $6,
        num_passeport = $5,
        num_telephone = $7,
        birth_date = $8
    WHERE idclient = $1;";
    $checkResult = pg_query_params($conn, $checkQuery, array($idclient,$nom, $prenom, $sexe, $numPass, $nationalite, $numTel, $birthDate));
    $html = '';
    if (!$checkResult) {
        die("Check query failed: " . pg_last_error());
    }else {
        $html = "<p>mise à jour effectuée</p>";
    }
    return $html;
}

function afficheDispoRooms($dateSortie,$dateEntree,$typeChambre,$animaux,$prixMin,$prixMax): void{
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

    // Use prepared statements to prevent SQL injection
    $query = "SELECT c.*
            FROM chambre c
            WHERE c.idchambre NOT IN (
                SELECT a.idchambre
                FROM atribue a
                WHERE (
                    (a.date_entre <= '$dateSortie' AND a.date_sortie >= '$dateEntree')
                )
            )
            AND statut = 'true'";
    if ($typeChambre != 'indifferent') {
        $query .= "AND type_chambre = '$typeChambre'";
    }
    if ($animaux != 'indifferent') {
        $query .= "AND animaux = '$animaux'";
    }
    if ($prixMin != '') {
        $prixMinDouble = doubleval($prixMin);
        $query .= "AND prix >= $prixMinDouble";
    }
    if ($prixMax != '') {
        $prixMaxDouble = doubleval($prixMax);
        $query .= "AND prix <= $prixMaxDouble";
    }
    $result = pg_query($conn, $query);
    $imgSrc = '';
    $alt = '';
    $animaux = '';
    while ($row = pg_fetch_assoc($result)) {
        $idChambre = $row['idchambre'];
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
        if ($row['animaux'] == 'TRUE') {
            $animaux = "acceptés";
        }else {
            $animaux = "réfusés";
        }
        echo "<article>\n
            <figure>\n
                <img src=\"".$imgSrc."\" alt=\"".$alt."\">\n
            </figure>\n
            <div>\n
                <p>type chambre : ".$row['type_chambre']."</p>\n
                <p>animaux : ".$animaux."</p>\n
                <p>description : ".$row['description']."</p>\n
                <p>prix : ".$row['prix']."</p>\n
                <button onclick=\"reserver('$dateEntree','$dateSortie','$idChambre')\">reserver</button>\n
            </div>\n
            </article>\n";
    }
}

function reserverRoom($dateEntree,$dateSortie,$idChambre,$idclient): void{
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

    // Use prepared statements to prevent SQL injection
    $query = "INSERT INTO atribue (idClient, idchambre, date_entre, date_sortie)
    VALUES ($4, $3, $1, $2);";
    $result = pg_query_params($conn, $query, array($dateEntree,$dateSortie,$idChambre,$idclient));
    if (!$result) {
        die("Check query failed: " . pg_last_error());
    }
}

function afficheDispoAct($typeactivite,$heure_debut,$idclient): void{
    $host = "postgresql-guerrouf.alwaysdata.net";      // Provided by your hosting provider
    $port = "5432";      // Provided by your hosting provider
    $dbname = "guerrouf_projetbdreseau"; // Provided by your hosting provider
    $user = "guerrouf";   // Provided by your hosting provider
    $password = "wwCs82*A"; // Provided by your hosting provider
    $dateReserve = date("Y-m-d");
    $heure_actuelle = date('H:i:s');
    // Create a connection to the PostgreSQL database
    $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

    if (!$conn) {
        die("Connection failed: " . pg_last_error());
    }

    $query = "DELETE FROM reserve WHERE date_reservation != '$dateReserve';";
    $result = pg_query($conn, $query);
    if (!$result) {
        die("Check query failed: " . pg_last_error());
    }

    $query = "UPDATE pratique
            SET nb_participant = 0
            WHERE heure_debut < '$heure_actuelle'
            OR idsalleactivite NOT IN (
                SELECT idsalleactivite FROM reserve);";
    $result = pg_query($conn, $query);
    if (!$result) {
        die("Check query failed: " . pg_last_error());
    }
    
    $query = "SELECT
            a.type_activite,
            a.dure,
            a.age_minimum,
            p.nb_participant,
            p.heure_debut,
            s.no_salle,
            s.etage,
            sa.idsalleactivite
            FROM 
                activite a
            JOIN 
                pratique p ON a.idactivite = p.idactivite
            JOIN 
                salleactivite sa ON p.idsalleactivite = sa.idsalleactivite
            LEFT JOIN
                reserve r ON p.idsalleactivite = r.idsalleactivite AND p.heure_debut = r.heure_debut
            JOIN 
                salle s ON sa.idsalleactivite = s.idsalle
            WHERE 
                p.heure_debut >= '$heure_debut'
                AND p.nb_participant < sa.capacite
                AND a.disponible = 'true'
                AND (R.idclient IS NULL OR r.idclient != '$idclient')
            ";
    if ($typeactivite != 'indifferent') {
        $query .= "AND a.type_activite = '$typeactivite';";
    }
    $result = pg_query($conn, $query);
    $imgSrc = '';
    
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
                <button onclick=\"reserver('$heureDebut','$dateReserve','$idsalleactivite')\">reserver</button>\n
            </div>\n
            </article>\n";
    }
}

function reserverAct($heureDebut,$dateReserve,$idsalleactivite,$idclient): void{
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

    // Use prepared statements to prevent SQL injection
    $query = "INSERT INTO reserve (idClient, idsalleactivite, date_reservation, heure_debut)
    VALUES ($4, $3, $2, $1);";
    $result = pg_query_params($conn, $query, array($heureDebut,$dateReserve,$idsalleactivite,$idclient));
    if (!$result) {
        die("Check query failed: " . pg_last_error());
    }
    $query = "UPDATE pratique
            SET nb_participant = nb_participant + 1
            FROM reserve
            WHERE pratique.idsalleactivite = reserve.idsalleactivite AND pratique.heure_debut = reserve.heure_debut
            AND reserve.idsalleactivite = '$idsalleactivite'
            AND reserve.heure_debut = '$heureDebut';";
    $result = pg_query($conn, $query);
    if (!$result) {
        die("Check query failed: " . pg_last_error());
    }
}
?>