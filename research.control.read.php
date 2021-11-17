<?php
    $date = date("d.m.Y");

    $file = "json/research_control/research_control-".$date.".json";

    $contents = file_get_contents($file);

    if($contents === false)
    {
        echo("Arquivo não encontrado: /star/json/research_control/research_control-".$date.".json");
        die();
    }

    // Transforma o json em uma array associativa
    $json = json_decode($contents, true);

    // Para visualizar o resultado da leitura do json
    // echo("<pre>");
    // print_r($json);
    // echo("</pre>");

    include("db.php");

    // Para cada registro no json onde:
    // $key = numero de 0 a N-1
    // $row = array com os dados daquele registro (colunas)
    //
    // O algoritmo cria a query independente de quantas colunas e quais são seus nomes, com uma restrição:
    // As duas últimas colunas são "inserted" e "stk_id", 
    // representando a data em timestamp que aquela linha foi inserida no banco e o ID no BancoSTK01 do Access, respectivamente
    // 
    // Existem algumas colunas que são hardcoded em algumas partes, como Analyst e Analyst_Sources, então é necessário fazer alterações caso essas mudem
    foreach($json as $key => $row)
    {
        // Inicio da query para inserir no mysql
        $sql = "INSERT INTO research_control (";

        $empresa = "";
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

            if(strpos($k, "Analyst") !== false && strpos($k, "Analyst_Sources") === false && strpos($k, "CO_Analyst") === false)
                $analyst = $info;
            if(strpos($k, "Empresa") !== false && strpos($k, "STK_Empresa_Name") === false)
                $empresa = $info;

            $sql .= $k.", ";
        }

        $sql .= "inserted, stk_id) VALUES (";

        // Passa de novo por cada coluna para a parte VALUES da query
        foreach($row as $k => $info)
        {
            if(strpos($k, "ID") !== false)
                continue;
            
            $sql .= ":".$k.", ";
        }

        $sql .= ":inserted, :stk_id)";

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
        $time = time();
        $stmt->bindParam(":inserted", $time);

        //echo($sql);

        try
        {
            // Deleta o registro com mesmo stk_id anterior

            $del = "DELETE FROM research_control WHERE stk_id = :stk_id";

            $stmt2 = $db->prepare($del);
            $stmt2->bindParam(":stk_id", $stk_id);
            $stmt2->execute();

            $stmt->execute();

            echo("Research Control Inserido - ".date("d/m/Y - G:i:s")."<br>");
            echo("Empresa: $empresa | Analyst: $analyst<br><br>");

        }
        catch(PDOException $e)
        {
            echo("Ocorreu um erro no banco de dados: ".$e->getMessage());
            die();
        }
    }
?>

