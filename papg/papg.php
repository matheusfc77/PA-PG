<!DOCTYPE html>
<?php
    $tituloPagina = "PA PG - V2";
    $titulo = "Gerador de Progressões Aritméticas e Geométricas";

    $subtitulo1 = "Gerador de Progressões";
    $subtitulo2 = "Upload de Progressões";

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

    $dados_json = array(
        "a1" => $a1,
        "razao" => $razao,
        "qtd" => $qtd,
        "progressao" => $progressao,
        "tipo" => $tipo
    );
    $json = json_encode($dados_json);
    $fp = fopen($file.".json", "w");
    fwrite($fp, $json);
    fclose($fp);

    $arquivoUpload = "";
    if(isset($_GET['arquivoUpload'])) {
        $arquivoUpload= $_GET['arquivoUpload'].".json";
    }
?>
<html>
<head>
    <meta charset="UTF-8"/>
    <title><?php echo $tituloPagina ?></title>
</head>
<body>
    <h1><?php echo $titulo ?></h1>
    <h2><?php echo $subtitulo1 ?></h2>
        
    <form action="" method="get">
        Primeiro termo (a1) <input type="text" name="a1" id="a1" value="" required></input><br>
        Razão <input type="text" name="razao" id="razao" value="" required></input><br>
        Qtd elementos <input type="text" name="qtd" id="qtd" value="" required></input><br>
        PA ou PG? <input type="text" name="tipo" id="tipo" value="" required></input><br>
        Nome do arquivo <input type="text" name="file" id="file" value="" required></input><br><br>
        <input type="submit" name="button1" id="button1" value="Gerar Progressão!"/>
    </form>

    <?php
        foreach($progressao as $elem) {
            echo "Elemento: ".$elem."<br>";
        }
    ?>

    <h2><?php echo $subtitulo2 ?></h2>

    <form action="" method="get">
        Nome do arquivo <input type="text" name="arquivoUpload" id="arquivoUpload" value=""></input>
        <input type="submit" name="button2" id="button2" value="Upload!"/>
    </form>
    
    <?php
        if(file_exists($arquivoUpload)) {
            $arquivo = file_get_contents($arquivoUpload);
            $dados_arquivo = json_decode($arquivo);
            echo "Elementos: ";
            foreach($dados_arquivo->progressao as $elem) {
                echo $elem." ";
            }
            
            echo "<br>A1: ".$dados_arquivo->a1;
            echo "<br>Razão: ".$dados_arquivo->razao;
            echo "<br>PA ou PG? ".$dados_arquivo->tipo;
            echo "<br>Quantidade de elementos: ".$dados_arquivo->qtd;

            $soma = 0;
            foreach($dados_arquivo->progressao as $elem) {
                $soma += $elem;
            }
            
            $media = $soma / $dados_arquivo->qtd;
            $valoresOrdenados = sort($dados_arquivo->progressao);
            $mediana = 0;
            if ($dados_arquivo->qtd % 2 == 0) {
                $mediana = ($dados_arquivo->progressao[$dados_arquivo->qtd / 2 - 1] + $dados_arquivo->progressao[$dados_arquivo->qtd / 2]) / 2;
            } else {
                $mediana = $dados_arquivo->progressao[intdiv($dados_arquivo->qtd, 2)];
            }

            echo "<br>Somatória: ".$soma;
            echo "<br>Média: ".$media;
            echo "<br>Mediana: ".$mediana;

        } else {
            echo "<br>Arquivo não encontrado";
        }
    ?>

</body>
</html>