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
    $file = file_get_contents("json/research_control/research_control-18.01.2019.json");

    if($file === false)
    {
        echo("Arquivo não encontrado: http://stkcapital.com.br/star/json/research_control/export/research_control_export-".$date.".json");
        die();
    }

    $json = json_decode($file, true);

    // echo("<pre>");
    // print_r($json);
    // echo("</pre>");

    // die();

    $db = new PDO($conn, '', '', array("charset"=>"utf8"));

    foreach($json as $key => $row)
    {
        $sql = "UPDATE Empresas_Research_Control SET ";

        $stk_id = "";

        $sql .= "North_America=:north_america, Canada=:canada, Europe=:europe, Japan=:japan, Latam=:latam, Brazil=:brazil, Asia_Africa=:asia, UK=:uk ";

        $sql .= "WHERE ID=:stk_id";

        $stmt = $db->prepare($sql);

        $north_america = str_replace(".", ",", $row['North_America']);
        $canada = str_replace(".", ",", $row['Canada']);
        $europe = str_replace(".", ",", $row['Europe']);
        $japan = str_replace(".", ",", $row['Japan']);
        $latam = str_replace(".", ",", $row['Latam']);
        $brazil = str_replace(".", ",", $row['Brazil']);
        
        echo("<br>Empresa: ".$row['STK_Empresa_Name']." - ".$brazil."<br>");

        $asia = str_replace(".", ",", $row['Asia_Africa']);
        $uk = str_replace(".", ",", $row['UK']);

        $stmt->bindParam(":north_america", $north_america);
        $stmt->bindParam(":canada", $canada);
        $stmt->bindParam(":europe", $europe);
        $stmt->bindParam(":japan", $japan);
        $stmt->bindParam(":latam", $latam);
        $stmt->bindParam(":brazil", $brazil);
        $stmt->bindParam(":asia", $asia);
        $stmt->bindParam(":uk", $uk);
        $stmt->bindParam(":stk_id", $row['ID']);

        //echo($sql);

        try
        {
            $stmt->execute();

            echo("Research Control Atualizado - ".date("d/m/Y - G:i:s")."<br>");
            echo("stk_id: $stk_id<br><br>");

        }
        catch(PDOException $e)
        {
            echo("Ocorreu um erro no banco de dados: ".$e->getMessage());
            die();
        }
    }
?>