<!DOCTYPE html>
<?php
    $tituloPagina = "PA PG - V4";
    $titulo = "Gerador de Progressões Aritméticas e Geométricas";

    $subtitulo1 = "Gerador de Progressões";
    $subtitulo2 = "Upload de Progressões";
    $subtitulo3 = "Verificação de Alterações";

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
        $tipo = strtoupper(trim($_GET['tipo']));
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

    function dados_json() {
        $arquivo = file_get_contents($GLOBALS['arquivoUpload']);
        $dados_arquivo = json_decode($arquivo);
        return $dados_arquivo;
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

    <h2><?php echo $subtitulo2 ?></h2>

    <form action="" method="get">
        Nome do arquivo <input type="text" name="arquivoUpload" id="arquivoUpload" value="" required></input>
        <input type="submit" name="button2" id="button2" value="Upload!"/>
    </form>
    
    <?php
        if(file_exists($arquivoUpload)) {
            $dados_arquivo = dados_json();
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

    <h3><?php echo $subtitulo3 ?></h3>

    <?php 
        if(file_exists($arquivoUpload)) {
            $dados_arquivo = dados_json();
            $progressaoAux = array();
            
            if($dados_arquivo->tipo == "PA") {
                for($i = 0; $i < $dados_arquivo->qtd; $i++) {
                    $progressaoAux[$i] = $dados_arquivo->a1 + $i * $dados_arquivo->razao; 
                }
            } else if($dados_arquivo->tipo == "PG") {
                for($i = 0; $i < $dados_arquivo->qtd; $i++) {
                    $progressaoAux[$i] = $dados_arquivo->a1 * pow($dados_arquivo->razao, $i); 
                }
            }

            $valoresAlterados = array();
            for($i = 0; $i < $dados_arquivo->qtd; $i++) {
                if($dados_arquivo->progressao[$i] <> $progressaoAux[$i]) {
                    $alteracao = array(
                        "original" => $progressaoAux[$i],
                        "alterado" => $dados_arquivo->progressao[$i]
                    );
                    $valoresAlterados[$i] = $alteracao;
                }
            }

            $qtdAlteracao = sizeof($valoresAlterados);
            if($qtdAlteracao > 0) {
                echo "Elementos Alterados: ";
                foreach($valoresAlterados as $elem) {
                    echo "<br>  Valor Original: ".$elem['original']." Alteração: ".$elem['alterado'];
                }
                $diferencaPercentual = number_format(($qtdAlteracao / $dados_arquivo->qtd) * 100, 2, ',');
                $percentualIntacto = number_format((100.0 - (float)$diferencaPercentual), 2, ',');
                echo "<br>".$diferencaPercentual."% do arquivo foi alterado. ";
                echo $percentualIntacto."% ainda permanece como ".$dados_arquivo->tipo;
            } else {
                echo "Nenhuma alteração encontrada.";
            }
        }
    ?>

    <script type="text/javascript" src="t2.json"></script>
    
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['X', 'Y'],
                <?php 
                    $dados_arquivo = dados_json();
                    for($i = 0; $i < $dados_arquivo->qtd; $i++) {
                        $valor = $dados_arquivo->progressao[$i];
                        echo "['$i', $valor],\n";
                    }
                ?>
            ]);

            var options = {
                title: 'Progressão',
                curveType: 'function',
                legend: { position: 'bottom' }
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

            chart.draw(data, options);
        }
    </script>

    <div id="curve_chart" style="width: 900px; height: 500px"></div>
</body>
</html>