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

    $sql = "SELECT Research_Log.ID, Research_Log.Data, Research_Log.Analyst, Research_Log.Ticker, Research_Log.Setor_Empresa, Research_Log.Code_Type, Research_Log.Access_Type, Research_Log.Message_Type, Research_Log.Sponsor, Research_Log.Quality_Score, Research_Log.Contact, Research_Log.Location, Research_Log.Comment, Research_Log.Data_Inclusao, Research_Log.Data_Atualizacao FROM Research_Log WHERE Research_log.Ticker <> NULL AND Research_Log.Data <> NULL ORDER BY Research_log.Data DESC;";
    $stmt = $db->prepare($sql);

    $stmt->execute();

    // Coleta todas as linhas em uma array associativa
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

    // Para debug:
    echo("<pre>");
    echo(json_encode($rows, JSON_PRETTY_PRINT));
    echo("</pre>");

    // Salva o json
    $date = date("d.m.Y");
    $f = fopen("H:/Backoffice/Hostgator STK Website/star/json/research_logs/research_logs-$date.json", "w");
    fwrite($f, json_encode($rows));
    fclose($f);

?>