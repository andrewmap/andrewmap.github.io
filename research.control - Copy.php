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

		<title>Research Control - STAR [DEV]</title>

		<meta name="robots" content="noindex, nofollow">
		<link rel="icon" href="img/stklogo2.png">
		
        <?php include("css-scripts.php"); ?>
	</head>
	<body>
        <?php include("header.php"); ?>

        <section class="rcontrol table-overflow section_update_brazil">
            <h1>Research Control</h1>
            
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

                <p>Universe: </p>

                <?php
                if(isset($_POST['submit']))
                {
                    $uni1check = (isset($_POST['universe1'])) ? "checked" : "";
                    $uni2check = (isset($_POST['universe2'])) ? "checked" : "";
                    $uni3check = (isset($_POST['universe3'])) ? "checked" : "";
                    $uni4check = (isset($_POST['universe4'])) ? "checked" : "";
                    $brcheck = (isset($_POST['brazil'])) ? "checked" : "";
                    $globalcheck = (isset($_POST['global'])) ? "checked" : "";
                }
                ?>

                <input type="checkbox" class="checkbox-first checkbox-universe" name="universe1" value="1" <?php echo((isset($uni1check) ? $uni1check : "checked")); ?>> 1
                <input type="checkbox" class="checkbox-universe" name="universe2" value="2" <?php echo((isset($uni2check) ? $uni2check : "checked")); ?>> 2
                <input type="checkbox" class="checkbox-universe" name="universe3" value="3" <?php echo((isset($uni3check) ? $uni3check : "checked")); ?>> 3
                <input type="checkbox" class="checkbox-universe" name="universe4" value="4" <?php echo((isset($uni4check) ? $uni4check : "checked")); ?>> 4
                <br>
                <input type="checkbox" class="checkbox-first" name="brazil" value="brazil" <?php echo((isset($brcheck) ? $brcheck : "checked")); ?>> Brazil
                <input type="checkbox" name="global" value="global" <?php echo((isset($globalcheck) ? $globalcheck : "checked")); ?>> Global

                <?php
                if(isset($_POST['submit']))
                {
                    $orderalpha = ($_POST['order'] == "Empresa") ? "checked" : "";
                    $orderdate = ($_POST['order'] == "Data_LastDriversValidation DESC") ? "checked" : "";
                    $orderticker = ($_POST['order'] == "Ticker_Referencia") ? "checked" : "";
                }
                ?>
                
                <p>Order by: </p>
                <input type="radio" name="order" value="Empresa" class="checkbox-first" <?php echo((isset($orderalpha) ? $orderalpha : "checked")); ?>> Company
                <input type="radio" name="order" value="Data_LastDriversValidation DESC" <?php echo((isset($orderdate) ? $orderdate : "")); ?>> Last Validation
                <input type="radio" name="order" value="Ticker_Referencia" <?php echo((isset($orderticker) ? $orderticker : "")); ?>> Ticker

                <p>Search from:</p>
                <input type="text" id="search-from" name="search-from" placeholder="YYYY" value="<?php echo((isset($_POST['search-from'])) ? $_POST['search-from'] : "2017"); ?>""> <i>(Some functionalities can get slower if you search more than 2 years back)</i>

                <p>Search results that contain: (here you can specify companies, names, words)</p>
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

                var universeSelected = false;
                $('.checkbox-universe').each(function() {
                    if($(this).prop('checked') == true)
                        universeSelected = true;
                });

                if(!universeSelected) {
                    alert("You need to select at least one rank.");
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
                $sql = "SELECT id, Empresa, STK_Empresa_Name, Ticker_Referencia, Rank, STK_Quality_Rating, Analyst, Data_LastDriversValidation FROM research_control WHERE ";

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
                }

                $sql .= "analyst IN $analysts AND ";

                $universe = "(";
                $universe .= (isset($_POST['universe1'])) ? "1,"  : "";
                $universe .= (isset($_POST['universe2'])) ? "2,"  : "";
                $universe .= (isset($_POST['universe3'])) ? "3,"  : "";
                $universe .= (isset($_POST['universe4'])) ? "4,"  : "";
                $universe = substr_replace($universe, "", -1);
                $universe .= ")";

                if(strlen($universe) <= 1)
                {
                    echo("<p>You need to select at least one rank.</p>");
                    die();
                }

                $sql .= "rank IN $universe ";

                if(isset($_POST['brazil']) && !isset($_POST['global']))
                    $sql .= "AND country = 'BRAZIL' ";
                elseif(isset($_POST['global']) && !isset($_POST['brazil']))
                    $sql .= "AND country <> 'BRAZIL' ";

                if(isset($_POST['search-from']))
                {
                    $searchFrom = $_POST['search-from']."-01-01 00:00:00";
                    $sql .= "AND Data_LastDriversValidation >= '$searchFrom' ";
                }

                if(isset($_POST['search-any']))
                {
                    //$sql .= "AND MATCH (Empresa, STK_Empresa_Name, Description, Position_Drivers, Triggers, Risks, ToDoList, Analyst_Sources, KeyDataMonitor, Ticker_Referencia) AGAINST (:search IN BOOLEAN MODE) ";
                }

                $sql .= "ORDER BY ".$_POST['order']." ";

                echo($sql."<br>");

                include("db.php");

                try
                {
                    $stmt = $db->prepare($sql);
                    if(isset($_POST['search-any']))
                    {
                        //$stmt->bindParam(":search", $_POST['search-any']);
                    }
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
                    <input type="text" name="filter-company" id="filter-company" placeholder="FILTER BY COMPANY">
                </p>
                <p class="filter-p">
                    <input type="text" name="filter-ticker" id="filter-ticker" placeholder="FILTER BY TICKER"> 
                </p>
                <p class="filter-loading"></p>
                
                <div class="rcontrol-reset-filter"><i class="fas fa-times"></i> Reset filters</div>

                <div class="research-floatright">Click on any result to open and edit</div>

                <p id="results">Results: <?php echo(count($results)); ?></p>
                
                <table class="research-table">
                    <tr class="th-line">
                        <th>Company</th>
                        <th>STK Company</th>
                        <th>Ticker</th>
                        <th class="rsc-center">Universe Rank</th>
                        <th class="rsc-center">Quality Rating</th>
                        <th class="rsc-center">Analyst</th>
                        <th class="rsc-center">Last Validation</th>
                    </tr>
                    <?php
                    $i = 0;
                    foreach($results as $result)
                    {
                        if(!is_null($result['Data_LastDriversValidation']))
                            $date = date("d/m/Y", strtotime($result['Data_LastDriversValidation']));
                        else
                            $date = "--";
                        

                        echo("
                        
                        <tr class=\"tr-link\" href=\"research.control.open.php?id={$result['id']}\">
                            <td class=\"company\">{$result['Empresa']}</td>
                            <td>{$result['STK_Empresa_Name']}</td>
                            <td class=\"ticker\">{$result['Ticker_Referencia']}</td>
                            <td class=\"rsc-center\">{$result['Rank']}</td>
                            <td class=\"rsc-center\">{$result['STK_Quality_Rating']}</td>
                            <td class=\"rsc-center\">{$result['Analyst']}</td>
                            <td class=\"rsc-center\">{$date}</td>
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
                    $('#filter-company').keyup(function(event) {
                        console.log("company filterJustUpdated = true, timer cleared");
                        filterJustUpdated = true;
                        clearTimeout(filterUpdatedTimer);
                        
                        filterUpdatedTimer = setTimeout(function() {
                            console.log("company filterJustUpdated = false, filter() called");
                            filterJustUpdated = false;
                            $('.filter-loading').text("Loading...");

                            setTimeout(function() {
                                filter("company", $('#filter-company').val(), function() {
                                    clearLoading();
                                    console.log("Loaded");
                                });
                            }, 500);

                        }, 1000);
                    });
                    $('#filter-ticker').keyup(function(event) {
                        console.log("ticker filterJustUpdated = true, timer cleared");
                        filterJustUpdated = true;
                        clearTimeout(filterUpdatedTimer);
                        
                        filterUpdatedTimer = setTimeout(function() {
                            console.log("ticker filterJustUpdated = false, filter() called");
                            filterJustUpdated = false;
                            $('.filter-loading').text("Loading...");

                            setTimeout(function() {
                                filter("ticker", $('#filter-ticker').val(), function() {
                                    clearLoading();
                                    console.log("Loaded");
                                });
                            }, 500);

                        }, 1000);
                    });
                    $('.rcontrol-reset-filter').click(function() {
                        $('#filter-company').val("");
                        $('#filter-ticker').val("");
                        filter("ticker", "", function() {
                            clearLoading();
                        });
                    });
                });
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