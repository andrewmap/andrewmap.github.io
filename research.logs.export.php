<?php
    include("db.php");

    $sql = "SELECT * FROM research_logs;";
    $stmt = $db->prepare($sql);

    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // echo("<pre>");
    // print_r($rows);
    // echo("</pre>");

    //echo("<pre>");
    //echo(json_encode($rows, JSON_PRETTY_PRINT));
    //echo("</pre>");

    $date = date("d.m.Y");
    $f = fopen("json/research_logs/export/research_logs_export-$date.json", "w");
    fwrite($f, json_encode($rows));
    fclose($f);

    echo("Research Logs exportado - ".date("d/m/Y G:i:s"));

?>