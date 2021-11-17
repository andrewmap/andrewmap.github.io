<?php
    include("db.php");

    $sql = "SELECT * FROM research_control;";
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
    $f = fopen("json/research_control/export/research_control_export-$date.json", "w");
    fwrite($f, json_encode($rows));
    fclose($f);

    echo("Research Control exportado - ".date("d/m/Y G:i:s"));

?>