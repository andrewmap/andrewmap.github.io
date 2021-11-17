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

    $sql = "SELECT * FROM Empresas_Research_Control;";
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
    $f = fopen("H:/Backoffice/Hostgator STK Website/star/json/research_control/research_control-$date.json", "w");
    fwrite($f, json_encode($rows));
    fclose($f);

?>