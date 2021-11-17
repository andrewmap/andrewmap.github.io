<?php

    ini_set('max_execution_time', 3600);
    //
    // A explicação/documentação desse algoritmo está no arquivo research.control.read.php, que é igual
    //

    if($_SERVER['REMOTE_ADDR'] != "127.0.0.1")
    {
        echo("Essa rotina deve ser executada localmente, depois da exportação.");
        die();
    }

    $conn = "odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=H:\Backoffice\Funds Control\Base de Dados\BancoSTK01.accdb;";

    $date = date("d.m.Y");
    $file = file_get_contents("http://stkcapital.com.br/star/json/research_logs/export/research_logs_export-$date.json");

    if($file === false)
    {
        echo("Arquivo não encontrado: http://stkcapital.com.br/star/json/research_logs/export/research_logs_export-".$date.".json");
        die();
    }

    $json = json_decode($file, true);

    //echo("<pre>");
    //print_r($json);
    //echo("</pre>");

    $db = new PDO($conn, '', '', array("charset"=>"utf8"));

    $max = count($json);
    $i = 0;
    foreach($json as $key => $row)
    {
        $sql = "UPDATE Research_Log SET ";

        $ticker = "";
        $analyst = "";
        $stk_id = "";

        foreach($row as $k => $info)
        {
            if(strpos($k, "id") !== false && strpos($k, "stk_id") === false)
                continue;
            if(strpos($k, "inserted") !== false)
                continue;
            if(strpos($k, "stk_id") !== false)
                continue;
            
            //echo("Key: $key | K: $k | Info: $info (".gettype($info).")<br>");

            if(strpos($k, "Analyst") !== false)
                $analyst = $info;
            if(strpos($k, "Ticker") !== false)
                $ticker = $info;

            // Último registro da linha do json
            if(strpos($k, "Data_Atualizacao") !== false)
            {
                $sql .= $k."=:".$k." ";
            }
            else
            {
                $sql .= $k."=:".$k.", ";
            }
        }

        $sql .= "WHERE ID=:stk_id";

        $stmt = $db->prepare($sql);

        foreach($row as $k => $info)
        {
            if(strpos($k, "stk_id") !== false)
            {
                $stmt->bindParam(":stk_id", $json[$key][$k]);
                $stk_id = $json[$key][$k];
                continue;
            }

            if(strpos($k, "id") !== false && strpos($k, "stk_id") === false)
                continue;
            if(strpos($k, "inserted") !== false)
                continue;
            if(strpos($k, "stk_id") !== false)
                continue;


            if(is_null($info))
                $info = "";

            $name = ":".$k;

            $stmt->bindParam($name, $json[$key][$k]);

            //echo($json[$key][$k]."<br>");
        }

        //echo($sql);

        try
        {
            $stmt->execute();

            echo("Research Logs Atualizado no Access - ".date("d/m/Y - G:i:s")." - ".$i."/".$max."<br>");
            echo("Ticker: $ticker | Analyst: $analyst | stk_id: $stk_id<br><br>");

        }
        catch(PDOException $e)
        {
            echo("Ocorreu um erro no banco de dados: ".$e->getMessage());
            die();
        }
        $i++;
    }
?>