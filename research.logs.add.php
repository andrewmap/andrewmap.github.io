<?php
    ob_start();
    include("db.php");
    include("sessions.php");
    include("login.check.php");

    // array para guardar somente os logs que foram enviados (são 16 campos, mas nem todos são preenchidos)
    $submited = array();
    if(isset($_POST['submit-logs']))
    {
        // um loop em todos os possíveis logs inseridos, de 0 a 15
        // quando um log estiver vazio, ele não é salvo e nem inserido na array "submited"
        for($i = 0; $i <= 15; $i++)
        {
            $b = $i + 1;

            $ticker = $_POST['ticker'.$i];

            // segue somente se foi preenchido o ticker
            // não é necessário checar tudo aqui, pois o javascript já faz essa checagem geral antes de enviar
            if(empty($ticker))
                continue;

            $sector = $_POST['sector'.$i];
            $code = $_POST['code'.$i];
            $access = $_POST['access'.$i];
            $message = $_POST['message'.$i];
            $sponsor = $_POST['sponsor'.$i];
            $quality = $_POST['quality'.$i];
            $contact = $_POST['contact'.$i];
            $where = $_POST['where'.$i];
            $comment = $_POST['comment'.$i];
            $mail = (isset($_POST['mail'.$i])) ? 1 : 0;

            // insere na array de logs enviados
            array_push($submited, array(
                "ticker" => $ticker,
                "analyst" => $_SESSION['user'],
                "sector" => $sector,
                "code" => $code,
                "access" => $access,
                "message" => $message,
                "sponsor" => $sponsor,
                "quality" => $quality,
                "contact" => $contact,
                "location" => $where,
                "comment" => $comment,
                "mail" => $mail
            ));
        }

        // mensagem do e-mail
        $mail_msg = "";
        // cria uma array para salvar logs com problema (algum campo vazio)
        $problems = array();
        foreach($submited as $i => $item)
        {
            // loopa em cada set de log e verifica se existe algum campo vazio
            // se existir, insere a index associativa desse log na array "problems"
            $stop = false;
            foreach($item as $key => $data)
            {
                if(empty($data) && $key != "mail")
                    $stop = true;
            }

            // se tiver um problema com esse log, não insere
            if($stop == true)
            {
                $problems[$i] = true;
                continue;
            }
            
            $problems[$i] = false;
            
            $sql = "INSERT INTO research_logs (Data, Analyst, Ticker, Setor_Empresa, Code_Type, Access_Type, Message_Type, Sponsor, Quality_Score, Contact, Location, Comment, Data_Inclusao, Data_Atualizacao) VALUES (:data, :analyst, :ticker, :sector, :code, :access, :message, :sponsor, :quality, :contact, :location, :comment, :inclusao, :atualizacao)";

            try
            {
                $stmt = $db->prepare($sql);

                $date = date("Y-m-d G:i:s");

                $stmt->bindParam(":data", $date);
                $stmt->bindParam(":analyst", $_SESSION['user']);
                $stmt->bindParam(":ticker", $item['ticker']);
                $stmt->bindParam(":sector", $item['sector']);
                $stmt->bindParam(":code", $item['code']);
                $stmt->bindParam(":access", $item['access']);
                $stmt->bindParam(":message", $item['message']);
                $stmt->bindParam(":sponsor", $item['sponsor']);
                $stmt->bindParam(":quality", $item['quality']);
                $stmt->bindParam(":contact", $item['contact']);
                $stmt->bindParam(":location", $item['location']);
                $stmt->bindParam(":comment", $item['comment']);
                $stmt->bindParam(":inclusao", $date);
                $stmt->bindParam(":atualizacao", $date);

                $stmt->execute();

                // atualizar o last date do portfolio driver
                $stmt = $db->prepare("UPDATE portfolio_drivers SET LastDate_".$item['code']."=:date WHERE Ticker_Referencia=:ticker");
                $stmt->bindParam(":date", $date);
                $stmt->bindParam(":ticker", $item['ticker']);
                $stmt->execute();

                $detail = "
                    <p>Analyst: <b>{$_SESSION['user']}</b></p>
                    <p>Ticker: <b>{$item['ticker']}</b></p>
                    <p>Sector / Company: <b>{$item['sector']}</b></p>
                    <p>Code Type: <b>{$item['code']}</b></p>
                    <p>Access Type: <b>{$item['access']}</b></p>
                    <p>Message Type: <b>{$item['message']}</b></p>
                    <p>Sponsor: <b>{$item['sponsor']}</b></p>
                    <p>Quality Score: <b>{$item['quality']}</b></p>
                    <p>Contact: <b>{$item['contact']}</b></p>
                    <p>Location: <b>{$item['location']}</b></p>
                    <p>Comment: <b>{$item['comment']}</b></p>
                ";
                logMessage($db, "Inserted a log (".$item['ticker'].")", "Log", $detail);

                if($item['mail'] == 1)
                {
                    $comment_format = preg_replace("/(https*:\/\/www\.evernote\.com\/[a-zA-Z]+\/[a-zA-Z0-9_-]+\/)/", "<a href=\"$1\" target=\"_blank\">$1</a>", $item['comment']);

                    $mail_msg .= $_SESSION['user']." - ".$item['ticker']." - ".$item['message']." - ".date("d/m/Y");
                    $mail_msg .= "<br>".$item['code']." - ".$item['contact'];
                    $mail_msg .= "<br>Comment: <br>".$comment_format;
                    $mail_msg .= "<br>----------------------------------------------<br><br>";
                }
            }
            catch(PDOException $e)
            {
                echo("Database error: ".$e->getMessage());
                die();
            }
        }

        $to = "contato@stkcapital.com.br";

        $cc = "antenor.fernandes@stkcapital.com.br, bernardo.cenzo@stkcapital.com.br, carlos.silva@stkcapital.com.br, daniel.grozdea@stkcapital.com.br, eduardo.leal@stkcapital.com.br, joao.emilio.ribeiro@stkcapital.com.br, miguel.galvao@stkcapital.com.br, pedro.quaresma@stkcapital.com.br";

        $contato = "contato@stkcapital.com.br";

        $subject = $_SESSION['user'].": New Findings & Recommendations - ".date("d/m/Y");

        $headers = "From: ".$contato."\r\n";
        $headers .= "Reply-To: ".$contato."\r\n";
        $headers .= "CC: ".$cc."\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO8859-1\r\n";

        //echo($mail_msg);   
        //die();

        // send mail
        //if(mail($to, $subject, $mail_msg, $headers))
        //    $mailed = true;
        //else
        //    $mailed = false;

        // echo("<pre>");
        // print_r($submited);
        // echo("</pre>");
        // die();
    }

    $stmt = $db->prepare("SELECT DISTINCT Ticker_Referencia FROM research_control ORDER BY Ticker_Referencia ASC;");

    $stmt->execute();
    $tickers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->prepare("SELECT DISTINCT Sponsor FROM research_logs ORDER BY Sponsor ASC;");

    $stmt->execute();
    $sponsors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->prepare("SELECT DISTINCT Location FROM research_logs ORDER BY Location ASC;");

    $stmt->execute();
    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // echo("<pre>");
    // print_r($tickers);
    // echo("</pre>");
?>
<!DOCTYPE html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Add Log - STAR [DEV]</title>

		<meta name="robots" content="noindex, nofollow">
		<link rel="icon" href="img/stklogo2.png">
		
        <?php include("css-scripts.php"); ?>
	</head>
	<body>
        <?php include("header.php"); ?>

        <?php
        if($_SERVER['REMOTE_ADDR'] != "127.0.0.1")
        {
            echo("<section><p>Section under development.</p></section>");
            die();
        }
        ?>

        <?php
        if(isset($_POST['submit-logs']))
        {
            echo("<section class=\"log-quick\">");

            //echo("<pre>");
            //print_r($problems);
            //echo("</pre>");

            $msg = "<p>Inserted logs: (click to search the respective ticker logs)</p>";

            foreach($submited as $key => $item)
            {
                $class = ($problems[$key] == true) ? "inserted-log-red" : "inserted-log-green";

                $msg .= "<p class=\"$class\"><a href=\"research.logs.php?ticker={$item['ticker']}\" target=\"_blank\">[$key]: {$item['ticker']} | {$item['analyst']} | {$item['code']} | {$item['access']} | {$item['message']} | {$item['sponsor']} | {$item['quality']} | {$item['contact']}</a></p>";
            }

            echo($msg);

            echo("</section>");
        }
        ?>

        <section class="log-quick">

            <h3>Replicate</h3>

            <p>Here you can replicate data into any field. For example, you enter "PETR4", select "Ticker" and 5 logs, the system will replicate PETR4 on 5 logs below.</p>

            <input type="text" name="replicate-value" id="replicate-value" placeholder="TEXT TO REPLICATE">
            <select name="replicate-field" id="replicate-field">
                <option value="ticker">Ticker</option>
                <option value="sector">Sector / Company</option>
                <option value="code">Code</option>
                <option value="access">Access Type</option>
                <option value="message">Message Type</option>
                <option value="sponsor">Sponsor</option>
                <option value="quality">Quality Score</option>
                <option value="contact">Contact</option>
                <option value="where">Where</option>
                <option value="comment">Comment</option>
            </select>
            <input type="text" name="replicate-quantity" id="replicate-quantity" placeholder="NUMBER OF LOGS">

            <a href="#" class="replicate-button" id="replicate-button">Replicate</a>

            <h3>Paste from Excel</h3>

            <p>Here you can paste data from Excel.</p>
            <p>Your sheet must be in this order: Ticker | Sector/Company | Code | Access Type | Mesage Type | Sponsor | Quality Score | Contact | Where | Comment</p>
            <p>The system will read every column by detecting a special character representing a "tab".</p>
            <p>Do not copy the titles/headers, only proper info.</p>
            <p>After pasting, double check if everything was detected correctly.</p>
            <textarea id="excel-paste" rows="5" placeholder="PASTE HERE (MAX 16 ENTRIES)"></textarea>
            <p id="excel-loading"></p>

            <h3>General Info</h3>
            <p>Informing the analyst isn't needed because the system already knows who is logged.</p>
            <p>You can press TAB to jump forward and SHIFT+TAB to jump backwards.</p>
            <p>You can also press SPACE to mark/unmark a selected checkbox.</p>
            <p>Some fields have an autocomplete feature, just start typing and select by using the arrows and then pressing tab.</p>
            <p>The ticker autocomplete loads every known ticker in our research control database.</p>

        </section>
    
        <form method="POST" action="" onsubmit="return checkLogs()">
            <input type="submit" class="submit-float" name="submit-logs" value="Send all logs">
            <?php

            $i = 0;
            for($i = 0; $i <= 15; $i++)
            {
                // a variavel b é só para exibir a partir de 1, não 0 (visualmente)
                //
                // a ideia por tras de usar um ID (que é a variável i) em todos os nomes e IDs de cada section e input é que assim podemos fazer loops numéricos simples tanto em javascript quanto em PHP
                // assim o JS consegue pegar o ID daquela respectiva <section>
                // e cada <section> corresponde a um log diferente
                // o PHP também consegue ver isso usando os names dos <inputs>
                // já que "ticker1", "sector1", "access1" tem o mesmo ID, basta fazer um loop pra cada ID
                //
                // em resumo, essa é a melhor forma que encontrei de criar formulários dinâmicos e repetitivos
                // 
                // lembrando que o <form> em si engloba todas as sections e inputs, tudo vai em um único POST
                // a diferença está no ID
                $b = $i + 1;
                echo("
                
                    <section class=\"log-section\" id=\"log-section$i\" data-num=\"$i\">
                        <h1 id=\"anchor$i\">Log $b</h1>
                            <div class=\"log-grid\">
                            
                                <div>
                                    <p>Ticker</p>
                                    <input type=\"text\" class=\"input$i ticker\" id=\"ticker$i\" name=\"ticker$i\">
                                </div>
                                <div>
                                    <p>Sector / Company</p>
                                    <input type=\"text\" class=\"input$i sector\" id=\"sector$i\" name=\"sector$i\">
                                </div>
                                <div>
                                    <p>Code</p>
                                    <input type=\"text\" class=\"input$i code\" id=\"code$i\" name=\"code$i\">
                                </div>
                                <div>
                                    <p>Access Type</p>
                                    <input type=\"text\" class=\"input$i access\" id=\"access$i\" name=\"access$i\">
                                </div>
                                <div>
                                    <p>Message Type</p>
                                    <input type=\"text\" class=\"input$i message\" id=\"message$i\" name=\"message$i\">
                                </div>
                                <div>
                                    <p>Sponsor</p>
                                    <input type=\"text\" class=\"input$i sponsor\" id=\"sponsor$i\" name=\"sponsor$i\">
                                </div>
                                <div>
                                    <p>Quality Score</p>
                                    <input type=\"text\" class=\"input$i quality\" id=\"quality$i\" name=\"quality$i\">
                                </div>
                                <div>
                                    <p>Contact</p>
                                    <input type=\"text\" class=\"input$i contact\" id=\"contact$i\" name=\"contact$i\">
                                </div>
                                <div>
                                    <p>Where</p>
                                    <input type=\"text\" class=\"input$i where\" id=\"where$i\" name=\"where$i\">
                                </div>
                                <div class=\"log-add-span2\">
                                    <p>Comment</p>
                                    <input type=\"text\" class=\"input$i comment\" id=\"comment$i\" name=\"comment$i\">
                                </div>
                                <div>
                                    <p>Send mail?</p>
                                    <input type=\"checkbox\" name=\"mail$i\" checked>
                                </div>

                            </div>
                    </section>

                ");
            }

            ?>
        </form>

        <script>
        // função que checa todos os inputs antes de enviar
        // retornar false significa que o formulário não vai ser enviado e a página não vai ser recarregada
        function checkLogs()
        {
            var foundError = false;
            var hasText = false;
            var allEmpty = true;

            // entra em cada set de inputs
            $('.log-section').each(function() {
                $(this).removeClass('log-section-error');
                // pega o seu ID
                var i = $(this).data('num');

                // para cada <input> com o ID da sua respectiva section, verifica se existe algum vazio
                $('.input'+i).each(function() {
                    if($(this).val().length > 0)
                        allEmpty = false;
                });
            });

            // se todos estiverem vazios, avisa e retorna falso
            if(allEmpty)
            {
                alert("All fields are empty.");
                return false;
            }

            // repete o processo de passar por todas as sections
            $('.log-section').each(function() {
                if(!foundError)
                {
                    // pega o ID
                    var i = $(this).data('num');

                    // repete o processo de passar por cada <input> da respectiva section
                    // mas dessa vez o objetivo não é verificar se TODOS estão vazios
                    // o objetivo é ver se existe algum campo preenchido && outro campo vazio no mesmo ID
                    hasText = false;
                    foundError = false;
                    $('.input'+i).each(function() {
                        // se algum campo/input dessa area (ID da section) estiver preenchido
                        if($(this).val().length > 0)
                            hasText = true;
                    });

                    // passa novamente pelos inputs para verificar se, quando houver texto, existe algum outro vazio (que não pode acontecer, tudo deve estar preenchido)
                    $('.input'+i).each(function() {
                        if(hasText)
                        {
                            if($(this).val().length <= 0)
                            {
                                // aplica uma classe de erro (borda e bg vermelhos)
                                $('#log-section'+i).addClass('log-section-error');
                                // muda o scroll para aquela área
                                var distance = $('#log-section'+i).offset().top;
                                $(document).scrollTop(distance);
                                foundError = true;
                            }
                        }
                    });

                    if(!foundError)
                        $('#log-section'+i).removeClass('log-section-error');
                }
            });

            // impede o envio do formulário se encontrar problemas
            if(foundError)
                return false;

            return true;
        }
        $(function() {
            // todos os tickers conhecidos pela tabela research_control
            // usado na função de autocomplete do jQuery
            var tickers = [
                <?php
                $max = count($tickers);
                $j = 1;
                foreach($tickers as $ticker)
                {
                    if($j == $max)
                        echo('"'.$ticker['Ticker_Referencia'].'"');
                    else
                        echo('"'.$ticker['Ticker_Referencia'].'", ');
                    $j++;
                }
                ?>
            ];
            var access = [
                "N/A",
                "Presentation",
                "Meeting",
                "Conf Call"
            ];
            var message = [
                "N/A",
                "Key Events",
                "New Findings",
                "Recommendation"
            ];
            var sponsors = [
                <?php
                $max = count($sponsors);
                $j = 1;
                foreach($sponsors as $sponsor)
                {
                    if($j == $max)
                        echo('"'.$sponsor['Sponsor'].'"');
                    else
                        echo('"'.$sponsor['Sponsor'].'", ');
                    $j++;
                }
                ?>
            ];
            var where = [
                <?php
                $max = count($locations);
                $j = 1;
                foreach($locations as $location)
                {
                    if($j == $max)
                        echo('"'.$location['Location'].'"');
                    else
                        echo('"'.$location['Location'].'", ');
                    $j++;
                }
                ?>
            ];
            var codes = [
                "N/A",
                "BOD",
                "BS",
                "CLIENT",
                "CONS",
                "DRIV",
                "MGMT",
                "PEER",
                "PLANT",
                "REG",
                "SS",
                "SUPP"
            ];
            $('.ticker').autocomplete({
                source: tickers
            });
            $('.access').autocomplete({
                source: access
            });
            $('.message').autocomplete({
                source: message
            });
            $('.sponsor').autocomplete({
                source: sponsors
            });
            $('.where').autocomplete({
                source: where
            });
            $('.code').autocomplete({
                source: codes
            });
            // botão de replicar valores nos inputs
            $('#replicate-button').click(function() {
                var value = $('#replicate-value').val();
                var field = $('#replicate-field option:selected').val();
                var quantity = $('#replicate-quantity').val();

                if(quantity == "" || quantity <= 0)
                {
                    alert("You need to enter a valid quantity > 0.");
                    return false;
                }

                // insere o "value" em "quantity" inputs do tipo "field"
                var i = 0;
                $('.'+field).each(function() {
                    if(i < quantity)
                    {
                        $(this).val(value);
                        i++;
                    }
                });
            });
            // processamento do paste from excel
            //$('#excel-paste').keyup(function(event) {
            $('#excel-paste').bind('paste', function(e) {
                
                setTimeout(function() {
                    processPaste();
                }, 100);
                
            });

        });
        function processPaste() {
            
            $('#excel-loading').text("Identifying...");

            var text = $('#excel-paste').val();

            console.log(text);

            // tamanho do texto
            var len = text.length;
            console.log(len);
            // array para salvar os logs
            var data = [];
            // id e contagem dos logs
            var log = 0;
            // id e contagem dos campos inseridos (ticker, sector, code, etc)
            var field = 0;
            // inicializa a array "data" com uma nova array dentro dela vazia []
            data.push([]);
            // inicializa a array vazia que foi inserida acima com uma nova array vazia
            data[0].push([]);
            // resultado: data[0][0]
            for(var i = 0; i < len; i++)
            {
                // se encontrar um TAB, é um novo campo, então insere um novo campo e soma os valores
                // i é incrementado para pular o tab na hora de inserir na string
                if(text[i] == '\t' || text[i] == '|')
                {
                    field++;
                    data[log].push([]);
                    i++;
                }
                // se encontrar uma nova linha, é um novo log, então insere uma nova array na "data"
                if(text[i] == '\n')
                {
                    console.log("New line detected. Logs: "+log+" | i: "+i+" | Max: "+len);
                    log++;
                    field = 0;
                    // se for o final do texto, encerra o loop
                    if(i + 1 >= len)
                        break;
                    data.push([]);
                    // quando for encontrada uma nova linha, encerramos essa execução do loop, voltando ao início do for com o i incrementado
                    continue;
                }

                // insere a letra na string correspondente ao seu log/field
                data[log][field] += text[i];
            }
            
            // por algum motivo completamente aleatório a partir do segundo log, a primeira e somente a primeira string fica com um "undefined" na frente do ticker (e somente do ticker)
            // então é feito um replace em todos tickers de todos os logs reconhecidos para retirar a palavra
            for(var j = 0; j < log; j++)
            {
                data[j][0] = data[j][0].replace("undefined", "");
            }

            // é possível ver tudo que foi lido no console do navegador, em caso de erro/debug
            console.log(data);
            console.log("Logs: "+log);

            // insere os dados nos inputs e exibe na tela quais tickers foram reconhecidos
            var done = "Logs: " + log + "<br>Tickers: ";
            for(var j = 0; j < log; j++)
            {
                console.log("Inserting a log: "+data[j][0]);
                
                $('#ticker'+j).val( data[j][0] );
                $('#sector'+j).val( data[j][1] );
                $('#code'+j).val( data[j][2] );
                $('#access'+j).val( data[j][3] );
                $('#message'+j).val( data[j][4] );
                $('#sponsor'+j).val( data[j][5] );
                $('#quality'+j).val( data[j][6] );
                $('#contact'+j).val( data[j][7] );
                $('#where'+j).val( data[j][8] );
                $('#comment'+j).val( data[j][9] );

                done += "&nbsp;&nbsp;" + data[j][0] + ", &nbsp;";
            }

            $('#excel-loading').html(done);
        }
        </script>

    </body>
</html>
<?php
ob_end_flush();
?>