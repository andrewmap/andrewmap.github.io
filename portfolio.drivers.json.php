<?php
    if($_SERVER['REMOTE_ADDR'] != "127.0.0.1")
    {
        echo("Essa rotina deve ser executada localmente.");
        die();
    }

    // Define a conexão com o Access local
    $conn = "odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=H:\Backoffice\Funds Control\Base de Dados\BancoSTK01.accdb;";

    // Abre a conexão com o banco
    $db = new PDO($conn, '', '', array("charset"=>"utf8"));

    // Informações principais que não exigem cálculos
    //
    // Empresa, Analista, País, Setor, Prob. Upside, Target Price, Downside Price, Beta, 
    // Alpha 1M 3M 6M, Next Report, Ticker Referencia, Downside Assumptions, Preço Fechamento Ajustado

    $sql = "SELECT Empresas_Research_Control.STK_Empresa_Name, Empresas_Research_Control.Analyst, Empresas_Research_Control.Country, 
            Empresas_Research_Control.Sector, Targets_Downsides.Prob_Upside, Targets_Downsides.Target_Price, Targets_Downsides.Downside_Price, 
            Targets_Downsides.Beta, Preco_Ativo_Chg_1D.Alpha_1M, Preco_Ativo_Chg_1D.Alpha_3M, Preco_Ativo_Chg_1D.Alpha_6M, Analista_Dados.Next_Report_Dt, 
            Empresas_Research_Control.Ticker_Referencia, 
            Targets_Downsides.Target_Downside_Assumptions, Preco_Acoes.Preco_Fechamento_Dia_Ajustado
            FROM Preco_Acoes INNER JOIN (Preco_Ativo_Chg_1D INNER JOIN ((Targets_Downsides INNER JOIN Empresas_Research_Control ON 
            Targets_Downsides.Ticker = Empresas_Research_Control.Ticker_Referencia) INNER JOIN Analista_Dados ON 
            (Empresas_Research_Control.Ticker_Referencia = Analista_Dados.Codigo_Ativo) AND (Targets_Downsides.Ticker = Analista_Dados.Codigo_Ativo)) 
            ON Preco_Ativo_Chg_1D.Codigo_Ativo = Empresas_Research_Control.Ticker_Referencia) ON (Preco_Acoes.Codigo_Ativo = Preco_Ativo_Chg_1D.Codigo_Ativo) 
            AND (Preco_Acoes.Data = Preco_Ativo_Chg_1D.Data)
            WHERE (((Empresas_Research_Control.Rank) = 1) And ((Preco_Ativo_Chg_1D.data) = #1/16/2019#))
            ORDER BY Empresas_Research_Control.Empresa;";
    $stmt = $db->prepare($sql);

    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Coleta todas as datas de contato com pessoas registradas, por empresa e por cargo
    $sql = "SELECT Research_Log.Data, Empresas_Research_Control.STK_Empresa_Name, Research_Log.Code_Type
            FROM (Research_Log INNER JOIN Empresa_Ativo ON Research_Log.Ticker = Empresa_Ativo.Ticker) INNER JOIN Empresas_Research_Control ON Empresa_Ativo.Empresa = Empresas_Research_Control.Empresa
            WHERE (((Research_Log.Code_Type)<>'N/A') AND ((Empresas_Research_Control.Rank)=1));";
    
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $lastContacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo("<pre>");
    echo(json_encode($lastContacts[0], JSON_PRETTY_PRINT));
    echo("</pre>");

    $sql = "SELECT TOP 1 Patrimonio.Data
            FROM Patrimonio
            WHERE (((Patrimonio.Cliente) = 'STK Long Biased Master FIA'))
            ORDER BY Patrimonio.Data DESC;";

    $stmt = $db->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchColumn();

    // Coleta a exposição de cada empresa por fundo
    $sql = "SELECT Exposicao_Ativo.Nome_Fundo, Cadastro_Acoes.Empresa, Sum(Exposicao_Ativo.Exposicao) AS SumOfExposicao
            FROM Cadastro_Acoes INNER JOIN Exposicao_Ativo ON Cadastro_Acoes.[Codigo Ativo] = Exposicao_Ativo.Codigo_Ativo
            WHERE (((Exposicao_Ativo.Nome_Fundo) In ('STK Long Biased Master FIA','STK Long Only Institucional FIA','STK Global Equity')) AND ((Exposicao_Ativo.Data)=#".$data."#))
            GROUP BY Exposicao_Ativo.Nome_Fundo, Cadastro_Acoes.Empresa;";

    $stmt = $db->prepare($sql);
    $stmt->execute();

    // valores devem ser multiplicados por 100 para exibição
    $exposicoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo("<pre>");
    echo(json_encode($exposicoes[0], JSON_PRETTY_PRINT));
    echo("</pre>");

    // Para debug:
    // echo("<pre>");
    // print_r($rows);
    // echo("</pre>");

    // Arruma a codificação que vem errada do Access
    array_walk_recursive($rows, function (&$val) {
        if (is_string($val)) {
            $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8');
        }
    });

    foreach($rows as $key => $row)
    {
        // Procura a exposição da atual empresa do foreach no fundo informado
        $rows[$key]['Exposure_LB'] = getExposure($row, $exposicoes, "STK Long Biased Master FIA");
        $rows[$key]['Exposure_LO'] = getExposure($row, $exposicoes, "STK Long Only Institucional FIA");
        $rows[$key]['Exposure_Global'] = getExposure($row, $exposicoes, "STK Global Equity");

        // Procura a última data de contato com o tipo de pessoa (MGMT, SS, ...)
        $rows[$key]['LastDate_MGMT'] = getLastDate($row, $lastContacts, "MGMT");
        $rows[$key]['LastDate_SS'] = getLastDate($row, $lastContacts, "SS");
        $rows[$key]['LastDate_BS'] = getLastDate($row, $lastContacts, "BS");
        $rows[$key]['LastDate_PEER'] = getLastDate($row, $lastContacts, "PEER");
        $rows[$key]['LastDate_CLIENT'] = getLastDate($row, $lastContacts, "CLIENT");
        $rows[$key]['LastDate_SUPP'] = getLastDate($row, $lastContacts, "SUPP");
        $rows[$key]['LastDate_PLANT'] = getLastDate($row, $lastContacts, "PLANT");
        $rows[$key]['LastDate_REG'] = getLastDate($row, $lastContacts, "REG");
        $rows[$key]['LastDate_CONS'] = getLastDate($row, $lastContacts, "CONS");
        $rows[$key]['LastDate_BOD'] = getLastDate($row, $lastContacts, "BOD");
        $rows[$key]['LastDate_DRIV'] = getLastDate($row, $lastContacts, "DRIV");

        // Calcula valores que não vem do banco
        $rows[$key]['Upside_Pctg'] = $row['Target_Price'] / $row['Preco_Fechamento_Dia_Ajustado'] - 1;
        $rows[$key]['Downside_Pctg'] = $row['Downside_Price'] / $row['Preco_Fechamento_Dia_Ajustado'] - 1;
        $rows[$key]['Risk_Reward'] = abs($rows[$key]['Upside_Pctg']) / abs($rows[$key]['Downside_Pctg']);
    }

    echo("<br>".count($rows[0])."<br>");

    // Para debug:
    echo("<pre>");
    echo(json_encode($rows, JSON_PRETTY_PRINT));
    echo("</pre>");

    // Salva o json
    $date = date("d.m.Y");
    $f = fopen("H:/Backoffice/Hostgator STK Website/star/json/portfolio_drivers/pdrivers-$date.json", "w");
    fwrite($f, json_encode($rows));
    fclose($f);

    function getLastDate($row, $lastContacts, $type)
    {
        $lastDate = "2010-01-01 00:00:00";
        foreach($lastContacts as $lc)
        {
            if($lc['STK_Empresa_Name'] == $row['STK_Empresa_Name'] && $lc['Code_Type'] == $type)
            {
                $lcDate = $lc['Data'];
                if(strtotime($lcDate) > strtotime($lastDate))
                {
                    $lastDate = $lcDate;
                }
            }
        }
        return (strtotime($lastDate) > strtotime("2010-01-01 00:00:00")) ? $lastDate : "";
    }
    function getExposure($row, $exposicoes, $fund)
    {
        foreach($exposicoes as $exp)
        {
            if($exp['Nome_Fundo'] == $fund && strtolower($exp['Empresa']) == strtolower($row['STK_Empresa_Name']))
            {
                return $exp['SumOfExposicao'];
            }
        }
        return "0.0";
    }

?>