<?php
    ob_start();
    include("db.php");
    include("sessions.php");
    include("login.check.php");

    if(!isset($_GET['id']))
    {
        header("Location: research.control.php");
        die();
    }

    $ticker = $_GET['ticker'];
    $id = $_GET['id'];

    if(isset($_POST['submit-log']))
    {
        $id = $_POST['id'];
        $analyst = $_POST['analyst'];
        $sector = $_POST['sector'];
        $code = $_POST['code'];
        $access = $_POST['access'];
        $message = $_POST['message'];
        $sponsor = $_POST['sponsor'];
        $quality = $_POST['quality'];
        $contact = $_POST['contact'];
        $location = $_POST['location'];
        $comment = $_POST['comment'];
        $now = time();

        $sql = "UPDATE research_logs SET Analyst=:analyst, Setor_Empresa=:sector, Code_Type=:code, Access_Type=:access, Message_Type=:message, Sponsor=:sponsor, Quality_Score=:quality, Contact=:contact, Location=:location, Comment=:comment, Data_Atualizacao=:now WHERE id=:id";

        $stmt = $db->prepare($sql);

        $stmt->bindParam(":analyst", $analyst);
        $stmt->bindParam(":sector", $sector);
        $stmt->bindParam(":code", $code);
        $stmt->bindParam(":access", $access);
        $stmt->bindParam(":message", $message);
        $stmt->bindParam(":sponsor", $sponsor);
        $stmt->bindParam(":quality", $quality);
        $stmt->bindParam(":contact", $contact);
        $stmt->bindParam(":location", $location);
        $stmt->bindParam(":comment", $comment);
        $stmt->bindParam(":now", $now);
        $stmt->bindParam(":id", $id);

        $stmt->execute();

        $comment = str_replace("\n", "<br>", $comment);
        $detail = "
            <p>Analyst: <b>$analyst</b></p>
            <p>Sector / Company: <b>$sector</b></p>
            <p>Code Type: <b>$code</b></p>
            <p>Access Type: <b>$access</b></p>
            <p>Message Type: <b>$message</b></p>
            <p>Sponsor: <b>$sponsor</b></p>
            <p>Quality Score: <b>$quality</b></p>
            <p>Contact: <b>$contact</b></p>
            <p>Location: <b>$location</b></p>
            <p>Comment: <b>$comment</b></p>
        ";
        logMessage($db, "Updated a $ticker log", "Log", $detail);
        $updated = true;
    }

    $sql = "SELECT * FROM research_logs WHERE id=:id";

    try 
    {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":id", $id);

        $stmt->execute();

        $info = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e)
    {
        echo("Database error: ".$e->getMessage());
        die();
    }
?>
<!DOCTYPE html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Log - <?php echo($ticker); ?> - STAR [DEV]</title>

		<meta name="robots" content="noindex, nofollow">
		<link rel="icon" href="img/stklogo2.png">
		
        <?php include("css-scripts.php"); ?>
	</head>
	<body>
        <?php include("header.php"); ?>

        <section class="logs rcontrol section_update_brazil">

            <h1>Log <?php echo($ticker); ?></h1>
            <?php
                $rcontrol_string = (isset($_GET['rcontrol'])) ? "&rcontrol={$_GET['rcontrol']}" : "";
            ?>
            <a class="back-button" href="research.logs.php?ticker=<?php echo($ticker.$rcontrol_string); ?>">« Back to <?php echo($ticker); ?> logs</a>
            
            <br><br>

            <?php
                if(isset($updated))
                    echoMessage("ok", "Log updated - ".date("d/m/Y - G:i:s"));
            ?>

            <form method="POST" action="">
                <input type="hidden" name="id" value="<?php echo($info['id']); ?>">

                <?php
                $date = date("d/m/Y", strtotime($info['Data']));
                ?>

                <p>Date: <b><?php echo($date); ?></b></p>
                <br>
                <p>Analyst:</p>
                <input type="text" name="analyst" value="<?php echo($info['Analyst']); ?>">

                <p>Sector / Company:</p>
                <input type="text" name="sector" value="<?php echo($info['Setor_Empresa']); ?>">

                <p>Code:</p>
                <select name="code">
                    <?php
                        $codes = array("MGMT", "SS", "BS", "PEER", "CLIENT", "SUPP", "PLANT", "REG", "CONS", "BOD", "DRIV");
                        foreach($codes as $cd)
                        {
                            $selected = ($cd == $info['Code_Type']) ? "selected" : "";
                            echo("<option value=\"$cd\" $selected>$cd</option>");
                        }
                    ?>
                </select>

                <p>Access Type:</p>
                <select name="access">
                    <?php
                        $types = array("N/A", "Presentation", "Meeting", "Conf Call");
                        foreach($types as $tp)
                        {
                            $selected = ($tp == $info['Access_Type']) ? "selected" : "";
                            echo("<option value=\"$tp\" $selected>$tp</option>");
                        }
                    ?>
                </select>

                <p>Message Type:</p>
                <select name="message">
                    <?php
                        $msgs = array("N/A", "Key Events", "New Findings", "Recommendation");
                        foreach($msgs as $msg)
                        {
                            $selected = ($msg == $info['Message_Type']) ? "selected" : "";
                            echo("<option value=\"$msg\" $selected>$msg</option>");
                        }
                    ?>
                </select>

                <p>Sponsor:</p>
                <input type="text" name="sponsor" value="<?php echo($info['Sponsor']); ?>">

                <p>Quality Score:</p>
                <select name="quality">
                    <?php
                        $quals = array("N/A", 0, 1, 2, 3);
                        foreach($quals as $qual)
                        {
                            $selected = ($qual == $info['Quality_Score']) ? "selected" : "";
                            echo("<option value=\"$qual\" $selected>$qual</option>");
                        }
                    ?>
                </select>

                <p>Contact:</p>
                <input type="text" name="contact" value="<?php echo($info['Contact']); ?>">

                <p>Location:</p>
                <input type="text" name="location" value="<?php echo($info['Location']); ?>">

                <p>Comment:</p>
                <textarea name="comment" rows="10"><?php echo($info['Comment']); ?></textarea>

                <?php

                $comment = preg_replace("/(https*:\/\/www\.evernote\.com\/[a-zA-Z]+\/[a-zA-Z0-9_-]+\/)/", "<a href=\"$1\" target=\"_blank\">$1</a>", $info['Comment']);
                $comment = str_replace("\n", "<br>", $comment);

                $created = date("d/m/Y G:i:s", strtotime($info['Data_Inclusao']));
                $updated = date("d/m/Y G:i:s", strtotime($info['Data_Atualizacao']));

                ?>
                <p>Comment (text):</p>
                <br>
                <p><?php echo($comment); ?></p>
                <br>
                <p>Created: <?php echo($created); ?></p>
                <p>Updated: <?php echo($updated); ?></p>
                <p><i>STK ID: <?php echo($info['stk_id']); ?></i></p>

                <input type="submit" value="Update" name="submit-log">

            </form>

        </section>
    </body>
</html>