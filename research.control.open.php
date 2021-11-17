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
    else
    {
        $id = $_GET['id'];

        if(!is_numeric($id))
        {
            header("Location: research.control.php");
            die();
        }
    }

    if(isset($_POST['submit-all']))
    {
        $company = $_POST['Empresa'];
        $stkCompany = $_POST['STK_Empresa_Name'];
        $ticker = $_POST['Ticker_Referencia'];
        $rank = $_POST['Rank'];
        $rating = $_POST['STK_Quality_Rating'];
        $analyst = $_POST['Analyst'];
        $description = $_POST['Description'];
        $id = $_POST['id'];

        $sql = "UPDATE research_control SET Empresa=:company, 
                                            STK_Empresa_Name=:stkCompany,
                                            Ticker_Referencia=:ticker,
                                            Rank=:rank,
                                            STK_Quality_Rating=:rating,
                                            Analyst=:analyst,
                                            Description=:description
                                            
                WHERE id = :id";

        try
        {
            $stmt = $db->prepare($sql);

            $stmt->bindParam(":company", $company);
            $stmt->bindParam(":stkCompany", $stkCompany);
            $stmt->bindParam(":ticker", $ticker);
            $stmt->bindParam(":rank", $rank);
            $stmt->bindParam(":rating", $rating);
            $stmt->bindParam(":analyst", $analyst);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":id", $id);

            $stmt->execute();

            $infoUpdated = true;

            $message = "Updated ".$company." research info";
            
            $description = str_replace("\n", "<br>", $description);

            $detail = "
                <p>Row ID: $id</p>
                <p>Company: <b>$company</b></p>
                <p>STK Company: <b>$stkCompany</b></p>
                <p>Ticker: <b>$ticker</b></p>
                <p>Universe Rank: <b>$rank</b></p>
                <p>Quality Rating: <b>$rating</b></p>
                <p>Analyst: <b>$analyst</b></p>
                <br><br>
                <p>Description:</p>
                <p><b>$description</b></p>
            ";
            logMessage($db, $message, "Research Info", $detail);
        }
        catch(PDOException $e)
        {
            echoMessage("error", "Database error: ".$e->getMessage());
            die();
        }
    
        $scroll = "anchor-validation";
        $lastValidation = date("Y-m-d G:i:s");
        $position = $_POST['Position_Drivers'];
        $triggers = $_POST['Triggers'];
        $risks = $_POST['Risks'];
        $todo = $_POST['ToDoList'];
        $sources = $_POST['Analyst_Sources'];
        $keydata = $_POST['KeyDataMonitor'];
        $id = $_POST['id'];
        $company = $_POST['company'];
        $analyst = $_POST['analyst'];
        $ticker = $_POST['ticker'];

        $sql = "UPDATE research_control SET Data_LastDriversValidation=:lastValidation, 
                                            Position_Drivers=:position,
                                            Triggers=:triggers,
                                            Risks=:risks,
                                            ToDoList=:todo,
                                            Analyst_Sources=:sources,
                                            KeyDataMonitor=:keydata
                                            
                WHERE id = :id";

        try
        {
            $stmt = $db->prepare($sql);

            $stmt->bindParam(":lastValidation", $lastValidation);
            $stmt->bindParam(":position", $position);
            $stmt->bindParam(":triggers", $triggers);
            $stmt->bindParam(":risks", $risks);
            $stmt->bindParam(":todo", $todo);
            $stmt->bindParam(":sources", $sources);
            $stmt->bindParam(":keydata", $keydata);
            $stmt->bindParam(":id", $id);

            $stmt->execute();

            $validationUpdated = true;

            $message = "Updated ".$company." driver validation";
            
            $position = str_replace("\n", "<br>", $position);
            $triggers = str_replace("\n", "<br>", $triggers);
            $keydata = str_replace("\n", "<br>", $keydata);
            $risks = str_replace("\n", "<br>", $risks);
            $todo = str_replace("\n", "<br>", $todo);
            $sources = str_replace("\n", "<br>", $sources);

            $detail = "
                <p>Row ID: $id</p>
                <p>Company: <b>$company</b></p>
                <p>Last Validation: <b>$lastValidation</b></p>
                <p>Position Drivers: <b>$position</b></p>
                <p>Triggers: <b>$triggers</b></p>
                <p>Risks: <b>$risks</b></p>
                <p>To do list / Follow ups: <b>$todo</b></p>
                <p>Analyst / Sources: <b>$sources</b></p>
                <p>Key Data to Monitor: <b>$keydata</b></p>
            ";

            // Email
            if(isset($_POST['sendmail']))
            {
                if($_POST['sendmail'] == "sendmail")
                {
                    $style_table = "border-collapse: collapse;border: 1px solid #999;background-color: #eee;";
                    $style_h1 = "font-family: Verdana;color: black;font-size: 26px;";
                    $style_text = "font-family: Verdana;color: black;font-size: 14px;";

                    $style_table2 = "border-collapse: collapse;border: 1px solid #999;";
                    $style_th = "background-color: #005082;color: white;font-weight: bold;min-width: 150px";

                    $body = "<h1 style=\"$style_h1\">New drivers update - ".date("j-M-Y")."</h1>";
                    $body .= "<table style=\"$style_table $style_text\">";
                        $body .= "<tr><td>Analyst</td><td><b>$analyst</b></td></tr>";
                        $body .= "<tr><td>Company</td><td><b>$company</b></td></tr>";
                        $body .= "<tr><td>Ticker</td><td><b>$ticker</b></td></tr>";
                    $body .= "</table>";

                    $body .= "<br>";
                    $body .= "<table style=\"$style_table2 $style_text\">";
                        $body .= "<tr>";
                            $body .= "<th style=\"$style_th\">Position Drivers</th>";
                            $body .= "<th style=\"$style_th\">Triggers</th>";
                            $body .= "<th style=\"$style_th\">Risks</th>";
                            $body .= "<th style=\"$style_th\">Follow Ups</th>";
                            $body .= "<th style=\"$style_th\">Analyst / Sources</th>";
                            $body .= "<th style=\"$style_th\">Key data to Monitor</th>";
                        $body .= "</tr>";
                        $body .= "<tr>";

                            $position = str_replace("\n", "<br>", $position);
                            $triggers = str_replace("\n", "<br>", $triggers);
                            $risks = str_replace("\n", "<br>", $risks);
                            $todo = str_replace("\n", "<br>", $todo);
                            $sources = str_replace("\n", "<br>", $sources);
                            $keydata = str_replace("\n", "<br>", $keydata);

                            $body .= "<td>$position</td>";
                            $body .= "<td>$triggers</td>";
                            $body .= "<td>$risks</td>";
                            $body .= "<td>$todo</td>";
                            $body .= "<td>$sources</td>";
                            $body .= "<td>$keydata</td>";
                        $body .= "</tr>";
                    $body .= "</table>";

                    $body = str_replace("<td>", "<td style=\"border: 1px solid #999;padding: 10px;\">", $body);

                    $to = "contato@stkcapital.com.br";

                    $cc = "antenor.fernandes@stkcapital.com.br, bernardo.cenzo@stkcapital.com.br, carlos.silva@stkcapital.com.br, daniel.grozdea@stkcapital.com.br, eduardo.leal@stkcapital.com.br, joao.emilio.ribeiro@stkcapital.com.br, miguel.galvao@stkcapital.com.br, pedro.quaresma@stkcapital.com.br";

                    $contato = "contato@stkcapital.com.br";

                    $subject = $_SESSION['user']." - Updated drivers - ".date("d/m/Y");

                    $headers = "From: ".$contato."\r\n";
                    $headers .= "Reply-To: ".$contato."\r\n";
                    $headers .= "CC: ".$cc."\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO8859-1\r\n";

                    //if(mail($to, $subject, $body, $headers))
                    //    $mailed = true;
                    //else
                    //    $mailed = false;
                }
            }

            logMessage($db, $message, "Driver Validation", $detail);
        }
        catch(PDOException $e)
        {
            echoMessage("error", "Database error: ".$e->getMessage());
            die();
        }
    }

    try
    {
        $stmt = $db->prepare("SELECT Empresa, STK_Empresa_Name, Ticker_Referencia, Rank, STK_Quality_Rating, Analyst, Description, Data_LastDriversValidation, Position_Drivers, Triggers, Risks, ToDoList, Analyst_Sources, KeyDataMonitor FROM research_control WHERE id = :id");

        $stmt->bindParam(":id", $id);

        $stmt->execute();
        $info = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $db->prepare("SELECT * FROM portfolio_drivers WHERE STK_Empresa_Name = :empresa AND Ticker_Referencia = :ticker");
        
        $stmt->bindParam(":empresa", $info['STK_Empresa_Name']);
        $stmt->bindParam(":ticker", $info['Ticker_Referencia']);

        $stmt->execute();
        $pdriver = $stmt->fetch(PDO::FETCH_ASSOC);
        $pdriver_id = $pdriver['id'];

        if(!empty($pdriver))
        {
            foreach($pdriver as $key => $pd)
            {
                if(is_numeric($pd))
                {
                    if(strpos($key, "Preco_Fechamento_Dia_Ajustado") === false &&
                        strpos($key, "Prob_Upside") === false &&
                        strpos($key, "Target_Price") === false &&
                        strpos($key, "Risk_Reward") === false &&
                        strpos($key, "Downside_Price") === false)
                    {
                        $pdriver[$key] = (float)$pdriver[$key] * 100;
                    }

                    $pdriver[$key] = number_format($pdriver[$key], 2);

                    if($key == "Upside_Pctg" || $key == "Exposure_LB" || $key == "Exposure_LO" || $key == "Exposure_Global" || $key == "Downside_Pctg")
                    {
                        $pdriver[$key] = $pdriver[$key]."%";
                    }
                }
            }
        }

        $stmt = $db->prepare("SELECT hide_sections, textbox_rows FROM user_configs WHERE user=:user AND page=:page");
        $stmt->bindParam(":user", $_SESSION['user']);
        $page = "research_control";
        $stmt->bindParam(":page", $page);

        $stmt->execute();

        $config = $stmt->fetch(PDO::FETCH_ASSOC);

        if(isset($_POST['submit-config']))
        {
            $hide_sections = $_POST['hide'];
            $textbox_rows = $_POST['rows_displayed'];
            if(empty($textbox_rows))
                $textbox_rows = 10;

            if(empty($config))
            {
                $stmt = $db->prepare("INSERT INTO user_configs (page, hide_sections, textbox_rows, user) VALUES (:page, :hide_sections, :textbox_rows, :user)");
            }
            else
            {
                $stmt = $db->prepare("UPDATE user_configs SET hide_sections=:hide_sections, textbox_rows=:textbox_rows WHERE page=:page AND user=:user");
            }
            
            $stmt->bindParam(":hide_sections", $hide_sections);
            $stmt->bindParam(":user", $_SESSION['user']);
            $stmt->bindParam(":textbox_rows", $textbox_rows);
            $stmt->bindParam(":page", $page);

            $stmt->execute();

            $stmt = $db->prepare("SELECT hide_sections, textbox_rows FROM user_configs WHERE user=:user AND page=:page");
            $stmt->bindParam(":user", $_SESSION['user']);
            $page = "research_control";
            $stmt->bindParam(":page", $page);

            $stmt->execute();

            $config = $stmt->fetch(PDO::FETCH_ASSOC);

            $configs_saved = true;
        }

        if(!empty($config))
            $spoiler = ($config['hide_sections'] == 1) ? "spoiler-closed" : "spoiler-openned";
        else
            $spoiler = "spoiler-openned";
    }
    catch(PDOException $e)
    {
        echoMessage("error", "Database error: ".$e->getMessage());
        die();
    }
?>
<!DOCTYPE html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Research Control Edit - STAR [DEV]</title>

		<meta name="robots" content="noindex, nofollow">
		<link rel="icon" href="img/stklogo2.png">
		
        <?php include("css-scripts.php"); ?>
	</head>
	<body>
        <?php include("header.php"); ?>

        <section class="section_update_brazil minimal-section">
            <form method="POST" action="">
                <?php
                if(isset($config))
                {
                    if(!empty($config))
                    {
                        $hide = ($config['hide_sections'] == 1) ? "checked" : "";
                        $textbox_rows = $config['textbox_rows'];
                    }
                }
                ?>
                <p><b>Page configuration:</b> here you can change how the page is rendered for you (and only for you)</p>

                <p>
                    Automatically hide sections? (You can unhide it by clicking on the title)
                    <input type="radio" name="hide" value="1" <?php if(isset($hide)) { if($hide == "checked") { echo($hide); } } ?>> Yes 
                    <input type="radio" name="hide" value="0" <?php if(isset($hide)) { if($hide != "checked") { echo("checked"); } } else { echo("checked"); } ?>> No
                </p>

                <p>
                    Number of rows on text boxes (height):
                    <input type="text" name="rows_displayed" value="<?php if(isset($textbox_rows)) { echo($textbox_rows); } ?>" placeholder="Enter a number" size="5">
                </p>

                <input type="submit" name="submit-config" value="Save">

                <?php
                if(isset($configs_saved))
                {
                    echo("<p>Configs saved.</p>");
                }
                ?>
            </form>
        </section>

        <form method="POST" action="" onsubmit="return checkForm()">
        <input type="submit" class="submit-float" name="submit-all" value="Update all infos">

        <section class="rcontrol-edit section_update_brazil">
            <?php
            if(isset($mailed))
            {
                $type = ($mailed) ? "ok" : "warn";
                $msg = ($mailed) ? "Driver updated and e-mail sended." : "The driver was updated but the e-mail failed.";
                echoMessage($type, $msg);
            }
            ?>

            <h1>Research Info - <?php echo($info['STK_Empresa_Name']); ?></h1>

            <?php
            if(isset($infoUpdated))
                echoMessage("ok", "Info updated successfully - ".date("d/m/Y G:i:s"));
            ?>

                <input type="hidden" name="id" value="<?php echo($id); ?>">

                <table>
                    <tr>
                        <td>Company:</td>
                        <td><input type="text" name="Empresa" value="<?php echo($info['Empresa']); ?>"></td>
                    </tr>
                    <tr>
                        <td>STK Company Name:</td>
                        <td><input type="text" name="STK_Empresa_Name" value="<?php echo($info['STK_Empresa_Name']); ?>"></td>
                    </tr>
                    <tr>
                        <td>Ticker:</td>
                        <td><input type="text" name="Ticker_Referencia" value="<?php echo($info['Ticker_Referencia']); ?>"></td>
                    </tr>
                    <tr>
                        <td>Universe Rank:</td>
                        <td><input type="text" name="Rank" value="<?php echo($info['Rank']); ?>"></td>
                    </tr>
                    <tr>
                        <td>Quality Rating:</td>
                        <td><input type="text" name="STK_Quality_Rating" value="<?php echo($info['STK_Quality_Rating']); ?>"></td>
                    </tr>
                    <tr>
                        <td>Analyst:</td>
                        <td><input type="text" name="Analyst" value="<?php echo($info['Analyst']); ?>"></td>
                    </tr>
                </table>

                <p>Description:</p>
                <textarea name="Description" rows="<?php
                    if(isset($config))
                        echo($config['textbox_rows']);
                    else
                        echo("10");
                 ?>"><?php echo($info['Description']); ?></textarea>
            
        </section>

        <section class="rcontrol-edit section_update_brazil">
            <span class="pdriver-span">(click to toggle)</span>
            <h1 class="h1spoiler" id="anchor-validation" data-name="driver_validation">Driver Validation <i class="fas fa-caret-down"></i></h1>

            <?php
            if(isset($validationUpdated))
                echoMessage("ok", "Driver validation updated successfully - ".date("d/m/Y G:i:s"));
            ?>
            <div id="spoiler-driver_validation" class="<?php echo($spoiler); ?>">
            
                <input type="hidden" name="company" value="<?php echo($info['Empresa']); ?>">
                <input type="hidden" name="analyst" value="<?php echo($info['Analyst']); ?>">
                <input type="hidden" name="ticker" value="<?php echo($info['Ticker_Referencia']); ?>">

                <table>
                    <tr>
                        <td>Last Driver Validation:</td>
                        <?php
                        $lastValidation = date("d/m/Y", strtotime($info['Data_LastDriversValidation']));
                        ?>
                        <td><input type="text" name="Data_LastDriversValidation" value="<?php echo($lastValidation); ?>" readonly></td>
                    </tr>
                </table>

                <p>Position Drivers:</p>
                <textarea name="Position_Drivers" rows="<?php
                    if(isset($config))
                        echo($config['textbox_rows']);
                    else
                        echo("10");
                 ?>"><?php echo($info['Description']); ?><?php echo($info['Position_Drivers']); ?></textarea>

                <p>Triggers:</p>
                <textarea name="Triggers" rows="<?php
                    if(isset($config))
                        echo($config['textbox_rows']);
                    else
                        echo("10");
                 ?>"><?php echo($info['Description']); ?><?php echo($info['Triggers']); ?></textarea>

                <p>Risks:</p>
                <textarea name="Risks" rows="<?php
                    if(isset($config))
                        echo($config['textbox_rows']);
                    else
                        echo("10");
                 ?>"><?php echo($info['Description']); ?><?php echo($info['Risks']); ?></textarea>

                <p>To do list / Follow ups:</p>
                <textarea name="ToDoList" rows="<?php
                    if(isset($config))
                        echo($config['textbox_rows']);
                    else
                        echo("10");
                 ?>"><?php echo($info['Description']); ?><?php echo($info['ToDoList']); ?></textarea>

                <p>Analyst / Sources:</p>
                <textarea name="Analyst_Sources" rows="<?php
                    if(isset($config))
                        echo($config['textbox_rows']);
                    else
                        echo("10");
                 ?>"><?php echo($info['Description']); ?><?php echo($info['Analyst_Sources']); ?></textarea>

                <p>Key Data to Monitor:</p>
                <textarea name="KeyDataMonitor" rows="<?php
                    if(isset($config))
                        echo($config['textbox_rows']);
                    else
                        echo("10");
                 ?>"><?php echo($info['Description']); ?><?php echo($info['KeyDataMonitor']); ?></textarea>

                <input type="checkbox" name="sendmail" value="sendmail" checked> Send e-mail after saving changes
        
            </div>
        </section>

        </form>

        <section class="rcontrol-edit section_update_brazil">
            <span class="pdriver-span">(click to toggle)</span>
            <h1 class="h1spoiler" id="anchor-pdriver" data-name="portfolio_driver">Portfolio Driver <i class="fas fa-caret-down"></i></h1>

            <div id="spoiler-portfolio_driver" class="pdriver <?php echo($spoiler); ?>">
                
            <?php
            if(empty($pdriver))
            {
                echo("<p>Portfolio driver not found.</p>");
            }
            else
            {
            ?>
            <table>
                <tr class="pd-theader">
                    <td colspan="3">Portfolio's Exposure</td>
                    <td></td>
                    <td colspan="7">Target & Downside</td>
                </tr>
                <tr>
                    <th>%LB</th>
                    <th>%LO</th>
                    <th>%Global</th>
                    <th>Next Report Date</th>
                    <th>Prob Upside</th>
                    <th>Current Price</th>
                    <th>Target Price</th>
                    <th>Upside %</th>
                    <th>Downside</th>
                    <th>Downside %</th>
                    <th>Risk/Reward</th>
                </tr>
                <tr>
                    <?php

                    $nextReportDate = date("d/m/Y", strtotime($pdriver['Next_Report_Dt']));

                    echo("<td>{$pdriver['Exposure_LB']}</td>");
                    echo("<td>{$pdriver['Exposure_LO']}</td>");
                    echo("<td>{$pdriver['Exposure_Global']}</td>");
                    echo("<td>{$nextReportDate}</td>");
                    echo("<td>{$pdriver['Prob_Upside']}</td>");
                    echo("<td>{$pdriver['Preco_Fechamento_Dia_Ajustado']}</td>");
                    echo("<td>{$pdriver['Target_Price']}</td>");
                    echo("<td>{$pdriver['Upside_Pctg']}</td>");
                    echo("<td>{$pdriver['Downside_Price']}</td>");
                    echo("<td>{$pdriver['Downside_Pctg']}</td>");
                    echo("<td>{$pdriver['Risk_Reward']}</td>");
                    ?>
                </tr>
            </table>

            <h3>Target & Downside Assumptions:</h3>
            <p><?php 

                $txt = $pdriver['Target_Downside_Assumptions'];
                $txt = str_replace("\n", "<br>", $txt);

                echo($txt);

            ?></p>

            <table>
                <tr class="pd-theader">
                    <td colspan="4">Price Changes</td>
                </tr>
                <tr>
                    <th>Beta</th>
                    <th>Alpha 1M</th>
                    <th>Alpha 3M</th>
                    <th>Alpha 6M</th>
                </tr>
                <tr>
                    <?php
                    echo("<td>{$pdriver['Beta']}</td>");
                    echo("<td>{$pdriver['Alpha_1M']}</td>");
                    echo("<td>{$pdriver['Alpha_3M']}</td>");
                    echo("<td>{$pdriver['Alpha_6M']}</td>");
                    ?>
                </tr>
            </table>

            <div style="overflow-x: scroll">
                <table class="research-maintenance">
                    <tr class="pd-theader">
                        <td colspan="11">Research Maintenance</td>
                    </tr>
                    <tr>
                        <th>MGMT</th>
                        <th>SS</th>
                        <th>BS</th>
                        <th>PEER</th>
                        <th>CLIENT</th>
                        <th>SUPP</th>
                        <th>PLANT</th>
                        <th>REG</th>
                        <th>CONS</th>
                        <th>BOD</th>
                        <th>DRIV</th>
                    </tr>
                    <tr class="color-dates">
                        <?php
                        echo(getDateTD($pdriver['LastDate_MGMT']));
                        echo(getDateTD($pdriver['LastDate_SS']));
                        echo(getDateTD($pdriver['LastDate_BS']));
                        echo(getDateTD($pdriver['LastDate_PEER']));
                        echo(getDateTD($pdriver['LastDate_CLIENT']));
                        echo(getDateTD($pdriver['LastDate_SUPP']));
                        echo(getDateTD($pdriver['LastDate_PLANT']));
                        echo(getDateTD($pdriver['LastDate_REG']));
                        echo(getDateTD($pdriver['LastDate_CONS']));
                        echo(getDateTD($pdriver['LastDate_BOD']));
                        echo(getDateTD($pdriver['LastDate_DRIV']));
                        ?>
                    </tr>
                </table>
            </div>

            <?php

            if(isset($_POST['submit-files']))
            {
                $scroll = "anchor-upload";
                if(isset($_FILES['model']))
                {
                    if(!empty($_FILES['model']['tmp_name']))
                    {
                        $modelName = basename($_FILES['model']['name']);
                        $modelBytes = file_get_contents($_FILES['model']['tmp_name']);
                        $modelExtension = strtolower(pathinfo($modelName, PATHINFO_EXTENSION));

                        $sql = "UPDATE portfolio_drivers SET model_file = :model_file, model_ext = :model_ext WHERE id = :id";
                        
                        $stmt = $db->prepare($sql);
                        $stmt->bindParam(":model_file", $modelBytes);
                        $stmt->bindParam(":model_ext", $modelExtension);
                        $stmt->bindParam(":id", $pdriver_id);
                        $stmt->execute();

                        logMessage($db, "Updated the model file.", "Portfolio Driver");
                    }
                    if(!empty($_FILES['model']['name']) && empty($_FILES['model']['tmp_name']))
                    {
                        $modelFileError = "There was an error uploading the model file.";
                    }
                }
                if(isset($_FILES['presentation']))
                {
                    if(!empty($_FILES['presentation']['tmp_name']))
                    {
                        $pptName = basename($_FILES['presentation']['name']);
                        $pptBytes = file_get_contents($_FILES['presentation']['tmp_name']);
                        $pptExtension = strtolower(pathinfo($pptName, PATHINFO_EXTENSION));
                    
                        $sql = "UPDATE portfolio_drivers SET presentation_file = :presentation_file, presentation_ext = :presentation_ext WHERE id = :id";
                        
                        $stmt = $db->prepare($sql);
                        $stmt->bindParam(":presentation_file", $pptBytes);
                        $stmt->bindParam(":presentation_ext", $pptExtension);
                        $stmt->bindParam(":id", $pdriver_id);
                        $stmt->execute();

                        logMessage($db, "Updated the presentation file.", "Portfolio Driver");
                    }
                    if(!empty($_FILES['presentation']['name']) && empty($_FILES['presentation']['tmp_name']))
                    {
                        $pptFileError = "There was an error uploading the presentation file.";
                    }
                }
            }

            }
            ?>

            <h3 id="anchor-upload">Files</h3>
            <?php

            if(isset($modelName))
                if(!empty($modelName))
                    echoMessage("ok", "Model file updated. Original file name: ".$modelName);
            if(isset($pptName))
                if(!empty($pptName))
                    echoMessage("ok", "Presentation file updated. Original file name: ".$pptName);

            if(isset($pptFileError))
                echoMessage("error", $pptFileError);
            if(isset($modelFileError))
                echoMessage("error", $modelFileError);

            ?>
            <div class="pdriver-links">
                <div class="pdriver-link">
                    <a href="portfolio.drivers.file.php?id=<?php echo($pdriver_id); ?>&type=model" target="_blank">
                        <i class="far fa-file-excel"></i>
                        Model
                    </a>
                </div>
                <div class="pdriver-link">
                    <a href="portfolio.drivers.file.php?id=<?php echo($pdriver_id); ?>&type=presentation" target="_blank">
                        <i class="far fa-file-powerpoint"></i>
                        Presentation
                    </a>
                </div>
                <div class="pdriver-link">
                    <a href="research.logs.php?ticker=<?php echo($info['Ticker_Referencia']); ?>&rcontrol=<?php echo($id); ?>">
                        <i class="far fa-file-alt"></i>
                        Logs
                    </a>
                </div>
            </div>

            <h3>File Upload</h3>

            <form action="" method="POST" enctype="multipart/form-data">
                <p>You don't need to upload both files at the same time, but you can.</p>

                <p>Model file:</p>
                <input type="file" name="model">

                <p>Presentation file:</p>
                <input type="file" name="presentation">

                <input type="submit" name="submit-files" value="Send Files">
            </form>

            </div>

        </section>
        <?php

        function getDateTD($date) 
        {
            if(empty($date))
            {
                return "<td></td>";
            }

            $timestamp = strtotime($date);
            $date = date("d/M/Y", $timestamp);
            
            $dif = time() - $timestamp;

            // o tempo em segundos para 90 dias
            if($dif >= (60 * 60 * 24 * 90))
                $style = "background-color: red;color: white;";
            elseif($dif > (60 * 60 * 24 * 60))
                $style = "background-color: yellow;";
            else
                $style = "background-color: #b2f050;";

            return "<td style=\"$style\">$date</td>";
        }

        ?>
        <script>
        function checkForm()
        {
            if(confirm('Please confirm that you want to save changes.'))
                return true;

            return false;
        }
        $(function() {
            $('h1').click(function() {
                var name = $(this).data("name");
                $('#spoiler-'+name).toggle('slide', {direction: 'up'}, 300);
            });
            <?php
            if(isset($scroll))
            {
                // Scroll para a área atualizada
                echo('
                
                setTimeout(function() {
                    var scroll = $("#'.$scroll.'").offset().top - 70;
                    $(document).scrollTop(scroll);
                }, 300);
                
                ');
            }
            ?>
        });
        </script>
    </body>
</html>
<?php
ob_end_flush();
?>