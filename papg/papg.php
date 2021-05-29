<!DOCTYPE html>
<?php
    $tituloPagina = "PA PG - V1";
    $titulo = "Gerador de Progressões Aritméticas e Geométricas";

    $a1 = 0;
    if(isset($_GET['a1'])) {
        $a1 = $_GET['a1'];
    }

    $razao = 0;
    if(isset($_GET['razao'])) {
        $razao = $_GET['razao'];
    }

    $qtd = 0;
    if(isset($_GET['qtd'])) {
        $qtd = $_GET['qtd'];
    }

    $tipo = "";
    if(isset($_GET['tipo'])) {
        $tipo = $_GET['tipo'];
    }

    $file = "";
    if(isset($_GET['file'])) {
        $file = $_GET['file'];
    }

    $progressao = array();
    if($tipo == "PA") {
        for($i = 0; $i < $qtd; $i++) {
            $progressao[$i] = $a1 + $i * $razao; 
        }
    } else if($tipo == "PG") {
        for($i = 0; $i < $qtd; $i++) {
            $progressao[$i] = $a1 * pow($razao, $i); 
        }
    }

    $dados_json = json_encode($progressao);
    $fp = fopen($file.".json", "w");
    fwrite($fp, $dados_json);
    fclose($fp);
?>
<html>
<head>
    <meta charset="UTF-8"/>
    <title><?php echo $tituloPagina ?></title>
</head>
<body>
    <h1><?php echo $titulo ?></h1>
        
    <form action="" method="get">
        Primeiro termo (a1) <input type="text" name="a1" id="a1" value=""></input><br>
        Razão <input type="text" name="razao" id="razao" value=""></input><br>
        Qtd elementos <input type="text" name="qtd" id="qtd" value=""></input><br>
        PA ou PG? <input type="text" name="tipo" id="tipo" value=""></input><br>
        Nome do arquivo <input type="text" name="file" id="file" value=""></input><br><br>
        <input type="submit" name="button1" id="button1" value="Gerar Progressão!"/>
    </form>

    <?php
        foreach($progressao as $elem) {
            echo "Elemento: ".$elem."<br>";
        }
    ?>

    <h1><?php echo $a1 ?></h1>

</body>
</html>