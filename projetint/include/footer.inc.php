<?php
    declare(strict_types=1);
    require_once("./include/fonctions.inc.php");

    function get_navigateur(): string {
        $agent = $_SERVER['HTTP_USER_AGENT'];
    
        if (strpos($agent, 'Opera') || strpos($agent, 'OPR/')){
            return 'Opera';
        } else if (strpos($agent, 'Edg')){
            return 'Edge';
        } else if (strpos($agent, 'Chrome')){
            return 'Chrome';
        } else if (strpos($agent, 'Safari')){
            return 'Safari';
        } else if (strpos($agent, 'Firefox')){
            return 'Firefox';
        } else if (strpos($agent, 'MSIE') || strpos($agent, 'Trident/7')){
            return 'Internet Explorer';
        }
        return 'Autre';
    }
?>
<!-- FOOTER -->
    <footer>
        <nav>
            <a href="#" title="Haut de page">
				<ul style="float: right;padding: 15px;">
					<li><img src="images/up.png" alt="Flèche vers le haut" /></li>
				</ul>
			</a>
            <ul>
                <li>Réalisé par <strong>Amar GUERROUF</strong>, <strong>Karim OULFID</strong> et <strong>Zakaria SMARA</strong></li>
                <li>Votre navigateur : <?= get_navigateur(); ?></li>
                <li>© <?= date("Y"); ?></li>
            </ul>
        </nav>
    </footer>
</body>
</html>