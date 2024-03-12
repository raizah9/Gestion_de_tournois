<?php
    declare(strict_types=1);

    $index = "index.php";
    // $custom_style = "main{\n
    //     position: relative;\n
    //     background-image: url('./images/background.jpg');\n
    //     background-size: cover;\n
    //     background-position: center;\n
    //     background-repeat: no-repeat;\n
    //     min-height: 100vh;\n
    //     }\n
    //     main::before {\n
    //         content: \"\";\n
    //         position: absolute;\n
    //         left: 0;\n
    //         width: 100%;\n
    //         height: 100%;\n
    //         background-color: rgba(15, 23, 43, .7);\n
    //     }\n";
    require('./include/header.inc.php');
?>

<main class="overlay">
    <h1>DOM'IHOTEL</h1>
    <section>
        <h2>Discover An Intelligent, Luxurious Hotel</h2>
        <article>
            <h3>Allez à</h3>
            <div>
                <a href="./reserver.php">reserver une chambre</a>
            </div>
            <div>
                <a href="./meschambre.php">vos chambres</a>
            </div>
        </article>
    </section>
</main>

    <?php require('./include/footer.inc.php'); ?>