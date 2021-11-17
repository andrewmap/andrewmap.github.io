<?php
    ob_start();
    include("db.php");
    include("sessions.php");
    include("login.check.php");

    if(!isset($_GET['ticker']))
    {
        header("Location: research.control.php");
        die();
    }

    $ticker = $_GET['ticker'];

    $sql = "SELECT id, Data, Analyst, Code_Type, Sponsor, Contact FROM research_logs WHERE Ticker=:ticker ORDER BY Data_Atualizacao DESC";

    try 
    {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":ticker", $ticker);

        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

		<title>Research Logs - STAR [DEV]</title>

		<meta name="robots" content="noindex, nofollow">
		<link rel="icon" href="img/stklogo2.png">
		
        <?php include("css-scripts.php"); ?>
	</head>
	<body>
        <?php include("header.php"); ?>

        <section class="rcontrol logs section_update_brazil">
            
            <h1>Research Logs - <?php echo($ticker); ?></h1>

            <?php 
            if(isset($_GET['rcontrol']))
            {
            ?>
            <a class="back-button" href="research.control.open.php?id=<?php echo($_GET['rcontrol']); ?>">« Back to <?php echo($ticker); ?> research control</a>
            
            <br><br>
            <?php
            }
            ?>
            <p>Filter listed results: (just start typing)</p>
            <p class="filter-p">
                <input type="text" name="filter-analyst" id="filter-analyst" placeholder="FILTER BY ANALYST">
            </p>
            <p class="filter-p">
                <!-- <input type="text" name="filter-code" id="filter-code" placeholder="FILTER BY CODE">  -->
                By code:<br>
                <select name="filter-code" id="filter-code">
                    <option value="">All</option>
                    <option value="MGMT">MGMT</option>
                    <option value="SS">SS</option>
                    <option value="BS">BS</option>
                    <option value="PEER">PEER</option>
                    <option value="CLIENT">CLIENT</option>
                    <option value="SUPP">SUPP</option>
                    <option value="PLANT">PLANT</option>
                    <option value="REG">REG</option>
                    <option value="CONS">CONS</option>
                    <option value="BOD">BOD</option>
                    <option value="DRIV">DRIV</option>
                </select>
            </p>
            <p class="filter-p">
                <input type="text" name="filter-sponsor" id="filter-sponsor" placeholder="FILTER BY SPONSOR"> 
            </p>
            <p class="filter-p">
                <input type="text" name="filter-contact" id="filter-contact" placeholder="FILTER BY CONTACT"> 
            </p>
            <p class="filter-loading"></p>
            
            <div class="rcontrol-reset-filter"><i class="fas fa-times"></i> Reset filters</div>

            <div class="research-floatright">Click on any result to open and edit</div>

            <p id="results">Results: <?php echo(count($results)); ?></p>
            
            <table class="research-table">
                <tr class="th-line">
                    <th>Date</th>
                    <th>Analyst</th>
                    <th>Code</th>
                    <th>Sponsor</th>
                    <th>Contact</th>
                </tr>
                <?php
                $i = 0;
                foreach($results as $result)
                {
                    if(!is_null($result['Data']))
                        $date = date("d/m/Y", strtotime($result['Data']));
                    else
                        $date = "--";
                    
                    $rcontrol_string = (isset($_GET['rcontrol'])) ? "&rcontrol={$_GET['rcontrol']}" : "";
                    echo("
                    
                    <tr class=\"tr-link\" href=\"research.logs.open.php?id={$result['id']}&ticker={$ticker}$rcontrol_string\">
                        <td>{$date}</td>
                        <td class=\"analyst\">{$result['Analyst']}</td>
                        <td class=\"code\">{$result['Code_Type']}</td>
                        <td class=\"sponsor\">{$result['Sponsor']}</td>
                        <td class=\"contact\">{$result['Contact']}</td>
                    </tr>
                
                    ");
                    $i++;
                }
                ?>
            </table>
            <script>
            $(function() {
                $('.tr-link').click(function() {
                    var link = $(this).attr('href');

                    window.open(link, "_self");
                });
                var filterJustUpdated = false;
                var filterUpdatedTimer = -1;
                $('#filter-analyst').keyup(function(event) {
                    startFilter("analyst", filterJustUpdated, filterUpdatedTimer);
                });
                $('#filter-code').change(function(event) {
                    startFilter("code", filterJustUpdated, filterUpdatedTimer);
                });
                $('#filter-sponsor').keyup(function(event) {
                    startFilter("sponsor", filterJustUpdated, filterUpdatedTimer);
                });
                $('#filter-contact').keyup(function(event) {
                    startFilter("contact", filterJustUpdated, filterUpdatedTimer);
                });
                $('.rcontrol-reset-filter').click(function() {
                    $('#filter-analyst').val("");
                    $('#filter-code').val("");
                    $('#filter-sponsor').val("");
                    $('#filter-contact').val("");
                    filter("analyst", "", function() {
                        clearLoading();
                    });
                });
            });
            function startFilter(type, filterJustUpdated, filterUpdatedTimer)
            {
                console.log(type+" filterJustUpdated = true, timer cleared");
                filterJustUpdated = true;
                clearTimeout(filterUpdatedTimer);
                
                filterUpdatedTimer = setTimeout(function() {
                    console.log(type+" filterJustUpdated = false, filter() called");
                    filterJustUpdated = false;
                    $('.filter-loading').text("Loading...");

                    setTimeout(function() {
                        filter(type, $('#filter-'+type).val(), function() {
                            clearLoading();
                            console.log("Loaded");
                        });
                    }, 500);

                }, 1000);
            }
            function clearLoading()
            {
                $('.filter-loading').text("");
            }
            function filter(type, value, callback)
            {
                value = value.toUpperCase();
                console.log("Loading");
                var results = 0;
                
                $('.tr-link').each(function() {
                    var tdval = $(this).find('.'+type).text();
                    tdval = tdval.toUpperCase();

                    console.log("("+tdval+") indexOf: "+ value + " = " + tdval.indexOf(value));

                    if(tdval.indexOf(value) == -1)
                    {
                        $(this).hide(0);
                    }
                    else
                    {
                        $(this).show(0);
                        results++;
                    }
                });

                $('#results').text("Results: " + results);

                if(typeof callback === 'function')
                    callback();
            }
            </script>

        </section>

    </body>
</html>
<?php
ob_end_flush();
?>