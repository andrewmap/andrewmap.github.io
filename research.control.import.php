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
    $file = file_get_contents("http://stkcapital.com.br/star/json/research_control/export/research_control_export-$date.json");

    if($file === false)
    {
        echo("Arquivo não encontrado: http://stkcapital.com.br/star/json/research_control/export/research_control_export-".$date.".json");
        die();
    }

    $json = json_decode($file, true);

    //echo("<pre>");
    //print_r($json);
    //echo("</pre>");

    $db = new PDO($conn, '', '', array("charset"=>"utf8"));

    foreach($json as $key => $row)
    {
        $sql = "UPDATE Empresas_Research_Control SET ";

        $empresa = "";
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

            if(strpos($k, "Analyst") !== false && strpos($k, "Analyst_Sources") === false && strpos($k, "CO_Analyst") === false)
                $analyst = $info;
            if(strpos($k, "Empresa") !== false && strpos($k, "STK_Empresa_Name") === false)
                $empresa = $info;

            // Último registro da linha do json
            if(strpos($k, "STK_Sector") !== false)
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

            if(strpos($k, "North_America") !== false || strpos($k, "Canada") !== false || strpos($k, "Europe") !== false || strpos($k, "Japan") !== false || strpos($k, "Latam") !== false || strpos($k, "Brazil") !== false || strpos($k, "Asia_Africa") !== false || strpos($k, "UK") !== false)
            {
                $json[$key][$k] = str_replace(".", ",", $json[$key][$k]);
            }

            $stmt->bindParam($name, $json[$key][$k]);

            //echo($json[$key][$k]."<br>");
        }

        //echo($sql);

        try
        {
            //$stmt->execute();

            echo("Research Control Atualizado - ".date("d/m/Y - G:i:s")."<br>");
            echo("Empresa: $empresa | Analyst: $analyst | stk_id: $stk_id<br><br>");

        }
        catch(PDOException $e)
        {
            echo("Ocorreu um erro no banco de dados: ".$e->getMessage());
            die();
        }
    }
?>