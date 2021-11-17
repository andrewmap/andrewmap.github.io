<?php
    ini_set('max_execution_time', 300);
    $date = date("d.m.Y");

    $file = "json/research_logs/research_logs-".$date.".json";

    $contents = file_get_contents($file);

    if($contents === false)
    {
        echo("Arquivo não encontrado: /star/json/research_logs/research_logs-".$date.".json");
        die();
    }

    // Transforma o json em uma array associativa
    $json = json_decode($contents, true);

    // Para visualizar o resultado da leitura do json
    // echo("<pre>");
    // print_r($json);
    // echo("</pre>");

    include("db.php");

    try {
        $del = "DELETE FROM research_logs";

        $stmt2 = $db->prepare($del);
        $stmt2->execute();
        
        $reset = "ALTER TABLE research_control AUTO_INCREMENT = 1;";
        $stmt2 = $db->prepare($reset);
        $stmt2->execute();
    }
    catch(PDOException $e)
    {
        echo("Erro no banco: ".$e->getMessage());
        die();
    }

    // Para cada registro no json onde:
    // $key = numero de 0 a N-1
    // $row = array com os dados daquele registro (colunas)
    //
    // O algoritmo cria a query independente de quantas colunas e quais são seus nomes, com uma restrição:
    // As duas últimas colunas são "inserted" e "stk_id", 
    // representando a data em timestamp que aquela linha foi inserida no banco e o ID no BancoSTK01 do Access, respectivamente
    foreach($json as $key => $row)
    {
        // Inicio da query para inserir no mysql
        $sql = "INSERT INTO research_logs (";

        $ticker = "";
        $analyst = "";

        // Passa por todas as colunas presentes no presente registro do json
        foreach($row as $k => $info)
        {
            // Não precisamos da coluna ID do Access na query de insert
            if(strpos($k, "ID") !== false)
                continue;
            
            // Debug para ver o que está sendo lido no momento
            //echo("Key: $key | K: $k | Info: $info (".gettype($info).")<br>");

            // Salva o analista e a empresa
            // É necessário usar o strpos pois por algum motivo os dados vem com caracteres ocultos na string que impede uma comparação com "=="
            if(strpos($k, "Analyst") !== false)
                $analyst = $info;
            if(strpos($k, "Ticker") !== false)
                $ticker = $info;

            $sql .= $k.", ";
        }

        $sql .= "stk_id) VALUES (";

        // Passa de novo por cada coluna para a parte VALUES da query
        foreach($row as $k => $info)
        {
            if(strpos($k, "ID") !== false)
                continue;
            
            $sql .= ":".$k.", ";
        }

        $sql .= ":stk_id)";

        $stmt = $db->prepare($sql);

        // Passa mais uma vez por cada coluna para bindar os parâmetros da query
        foreach($row as $k => $info)
        {
            if(strpos($k, "ID") !== false)
            {
                $stmt->bindParam(":stk_id", $json[$key][$k]);
                $stk_id = $json[$key][$k];
                continue;
            }

            if(is_null($info))
                $info = "";

            // O bind é feito com, por exemplo, :Empresa, então usa o $k
            $name = ":".$k;

            // O valor do bind é $json[$key][$k]
            // Exemplo: $json[0]["Empresa"]
            $stmt->bindParam($name, $json[$key][$k]);

            //echo($json[$key][$k]."<br>");
        }

        //echo($sql);

        try
        {
            $stmt->execute();

            echo("Research Log Inserido - ".date("d/m/Y - G:i:s")."<br>");
            echo("Ticker: $ticker | Analyst: $analyst<br><br>");

        }
        catch(PDOException $e)
        {
            echo("Ocorreu um erro no banco de dados: ".$e->getMessage());
            die();
        }
    }
?>

