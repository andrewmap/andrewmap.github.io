<?php
    ob_start();
    include("db.php");
    include("sessions.php");
    include("login.check.php");
?>
<!DOCTYPE html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Log Search - STAR [DEV]</title>

		<meta name="robots" content="noindex, nofollow">
		<link rel="icon" href="img/stklogo2.png">
		
        <?php include("css-scripts.php"); ?>
	</head>
	<body>
        <?php include("header.php"); ?>

        <section class="rcontrol logs section_update_brazil">
            <h1>Log Search</h1>
            <?php
            if($_SERVER['REMOTE_ADDR'] != "127.0.0.1")
            {
                echo("<p>Section under development.</p>");
                die();
            }
            ?>
            <form method="POST" action="" onsubmit="return checkForm()">

                <p>Analyst: <a href="#" class="selectall-analyst">(invert selection)</a></p>
                <input type="checkbox" class="checkbox-first checkbox-analyst" name="analyst-pq" value="PQ" <?php echo((isset($_POST['analyst-pq']) ? "checked" : "")); ?>> PQ
                <input type="checkbox" class="checkbox-analyst" name="analyst-el" value="EL" <?php echo((isset($_POST['analyst-el']) ? "checked" : "")); ?>> EL
                <input type="checkbox" class="checkbox-analyst" name="analyst-dg" value="DG" <?php echo((isset($_POST['analyst-dg']) ? "checked" : "")); ?>> DG
                <input type="checkbox" class="checkbox-analyst" name="analyst-cs" value="CS" <?php echo((isset($_POST['analyst-cs']) ? "checked" : "")); ?>> CS
                <input type="checkbox" class="checkbox-analyst" name="analyst-mg" value="MG" <?php echo((isset($_POST['analyst-mg']) ? "checked" : "")); ?>> MG
                <input type="checkbox" class="checkbox-analyst" name="analyst-na" value="N/A" <?php echo((isset($_POST['analyst-na']) ? "checked" : "")); ?>> N/A

                <script>
                $(function() {
                    $('.selectall-analyst').click(function() {
                        $('.checkbox-analyst').each(function() {
                            $(this).prop('checked', !$(this).prop('checked'));
                        });
                    });
                });
                </script>

                <?php
                if(isset($_POST['submit']))
                {
                    $orderalpha = ($_POST['order'] == "Setor_Empresa") ? "checked" : "";
                    $orderdate = ($_POST['order'] == "Data DESC") ? "checked" : "";
                    $orderticker = ($_POST['order'] == "Ticker") ? "checked" : "";
                }
                ?>
                
                <p>Order by: </p>
                <input type="radio" name="order" value="Data DESC" class="checkbox-first" <?php echo((isset($orderdate) ? $orderdate : "checked")); ?>> Date
                <input type="radio" name="order" value="Setor_Empresa" <?php echo((isset($orderalpha) ? $orderalpha : "")); ?>> Sector / Company
                <input type="radio" name="order" value="Ticker" <?php echo((isset($orderticker) ? $orderticker : "")); ?>> Ticker

                <p>Search from:</p>
                <input type="text" id="search-from" name="search-from" placeholder="YYYY" value="<?php echo((isset($_POST['search-from'])) ? $_POST['search-from'] : "2017"); ?>""> <i>(Some functionalities can get slower if you search more than 2 years back)</i>

                <p>[Optional] Search results that contain: (here you can specify companies, names, words)</p>
                <input type="text" id="search-any" name="search-any" placeholder="Anything" value="<?php echo((isset($_POST['search-any'])) ? $_POST['search-any'] : ""); ?>">

                <input type="submit" name="submit" value="Search">
            </form>

            <script>
            function checkForm() {
                var analystSelected = false;
                $('.checkbox-analyst').each(function() {
                    if($(this).prop('checked') == true)
                        analystSelected = true;
                });

                var anysearch = $('#search-any').val();

                if(!analystSelected && anysearch.length == 0) {
                    alert("You need to select at least one analyst.");
                    return false;
                }

                var year = $('#search-from').val();
                var yearRegex = /^(20[0-9]{2})$/;
                if(!yearRegex.test(year))
                {
                    alert("Enter a valid year (>= 2014)");
                    return false;
                }
            }
            </script>

            <?php
            if(isset($_POST['submit']))
            {
                $sql = "SELECT id, Data, Ticker, Code_Type, Sponsor, Contact FROM research_logs WHERE ";

                $analysts = "(";
                $analysts .= (isset($_POST['analyst-pq'])) ? "'PQ',"  : "";
                $analysts .= (isset($_POST['analyst-el'])) ? "'EL',"  : "";
                $analysts .= (isset($_POST['analyst-dg'])) ? "'DG',"  : "";
                $analysts .= (isset($_POST['analyst-cs'])) ? "'CS',"  : "";
                $analysts .= (isset($_POST['analyst-mg'])) ? "'MG',"  : "";
                $analysts .= (isset($_POST['analyst-na'])) ? "'N/A'," : "";
                $analysts = substr_replace($analysts, "", -1);
                $analysts .= ")";
                
                if(!isset($_POST['search-any']))
                {
                    if(strlen($analysts) <= 1)
                    {
                        echo("<p>You need to select at least one analyst.</p>");
                        die();
                    }
                }
                else
                {
                    if(strlen($analysts) <= 1 && strlen($_POST['search-any']) <= 0)
                    {
                        echo("<p>You need to select at least one analyst.</p>");
                        die();
                    }
                    elseif(strlen($analysts <= 1) && strlen($_POST['search-any']) > 0)
                    {
                        $analysts = "('PQ', 'EL', 'DG', 'CS', 'MG', 'N/A')";
                    }
                }
                
                $sql .= "Analyst IN $analysts ";

                if(isset($_POST['search-from']))
                {
                    $searchFrom = $_POST['search-from']."-01-01 00:00:00";
                    $sql .= "AND Data >= '$searchFrom' ";
                }

                if(isset($_POST['search-any']))
                {
                    if(strlen($_POST['search-any']) > 1)
                        $sql .= "AND (MATCH (Ticker, Setor_Empresa, Code_Type, Message_Type, Sponsor, Contact, Location, Comment) AGAINST (:search IN BOOLEAN MODE)) ";
                }

                $sql .= "ORDER BY ".$_POST['order']." ";

                //echo($sql."<br>");

                include("db.php");

                try
                {
                    $stmt = $db->prepare($sql);
                    
                    if(isset($_POST['search-any']))
                        if(strlen($_POST['search-any']) > 1)
                            $stmt->bindParam(":search", $_POST['search-any']);

                    $stmt->execute();

                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                catch(PDOException $e)
                {
                    echo("<p>Ocorreu um erro no banco de dados: ".$e->getMessage()."</p>");
                    die();
                }

            ?>
                <div class="hr"></div>
                <p>Filter listed results: (just start typing)</p>
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
                    <input type="text" name="filter-ticker" id="filter-ticker" placeholder="FILTER BY TICKER"> 
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
                        <th>Ticker</th>
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
                        

                        echo("
                        
                        <tr class=\"tr-link\" href=\"research.logs.open.php?id={$result['id']}&ticker={$result['Ticker']}\">
                            <td>{$date}</td>
                            <td class=\"ticker\">{$result['Ticker']}</td>
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

                        window.open(link, "_blank");
                    });
                    var filterJustUpdated = false;
                    var filterUpdatedTimer = -1;
                    $('#filter-ticker').keyup(function(event) {
                        startFilter("ticker", filterJustUpdated, filterUpdatedTimer);
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
                        $('#filter-ticker').val("");
                        $('#filter-code').val("");
                        $('#filter-sponsor').val("");
                        $('#filter-contact').val("");
                        filter("ticker", "", function() {
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

            <?php
            }
            ?>
        </section>
    </body>
</html>
<?php
ob_end_flush();
?>