<?php
// on évite la mise en cache
header("Pragma: no-cache");
header("Cache-Control: no-cache, must-revalidate");
require "../verifSession.php";
require_once "../mysqliConnect.php";
require_once "../function.php";

$sql = "SELECT m.*,u.idutil,u.thelogin,u.thecolor,u.theimage FROM themessage m 
        INNER JOIN theuser u 
          ON u.idutil = m.theuser_idutil
ORDER BY m.idmessage DESC LIMIT 0,30";
$recup = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
// pas de résultats
if (!mysqli_num_rows($recup)) {
    echo "<h3>No message yet !</h3>";
} else {

    $tous = mysqli_fetch_all($recup, MYSQLI_ASSOC);
    $tous = array_reverse($tous);


    foreach ($tous AS $item) {

        $item['thecontent'] = traiteChaine(links(Censurer($item['thecontent'])));
        $choiceLeftRight = $item["thelogin"] == $_SESSION["thelogin"] ? " right" : " left";
        ?>

        <div class='message<?= $choiceLeftRight ?>' style='color:<?= $item["thecolor"] ?>'>
            <i><img src="img/profil/thumbs/<?=$item['theimage']?>"height="50" width="50" > <?=$item['thelogin']?>
                <?php
                if(in_array($item['idutil'],$_SESSION['online'])) {
                    ?>
                    <img src="../img/online.jpg " width="10px" height="10px">
                    <?php
                }else {
                    ?>
                    <img src="../img/offline.jpg" width="10px" height="10px">
                    <?php
                }
                    ?>
                    </i><br>

            <p><?= $item['thecontent'] ?><br><br><span id='date'><?= thedate($item['thedatetime']) ?></span></p></div>
        <?php
    }
}
//var_dump($_SESSION['online']);
