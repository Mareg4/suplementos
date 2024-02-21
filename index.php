<?php

/*
$pid_zumub = pcntl_fork();

if ($pid_zumub == -1) {
    die('Erro ao criar processo para parserZUMUB');
} elseif ($pid_zumub) {
    // Processo pai
    $pid_bulk = pcntl_fork();
    
    if ($pid_bulk == -1) {
        die('Erro ao criar processo para parserBULK');
    } elseif ($pid_bulk) {
        // Processo pai
        $pid_myprotein = pcntl_fork();

        if ($pid_myprotein == -1) {
            die('Erro ao criar processo para parserMYPROTEIN');
        } elseif ($pid_myprotein) {
            // Processo pai
            pcntl_wait($status_bulk); // Aguarda o término do processo filho
            pcntl_wait($status_zumub); // Aguarda o término do processo filho
            pcntl_wait($status_myprotein); // Aguarda o término do processo filho
        } else {
            // Processo filho para parserMYPROTEIN
            $myProtein = shell_exec('node parsers/parserMYPROTEIN.js');
            exit(); // Importante: encerrar o processo filho após a execução
        }
    } else {
        // Processo filho para parserBULK
        $bulkLocal = shell_exec('node --max-http-header-size 8500 parsers/parserBULK.js');
        file_put_contents('pipes/bulk_result.pipe', $bulkLocal);
        exit(); // Importante: encerrar o processo filho após a execução
    }
    
} else {
    // Processo filho para parserZUMUB
    $zumub = shell_exec('python3 parsers/parserZUMUB.py');   
    exit(); // Importante: encerrar o processo filho após a execução
}*/


$zumub = shell_exec('python3 parsers/parserZUMUB.py');
$bulk = shell_exec('node --max-http-header-size 8500 parsers/parserBULK.js');
$myProtein = shell_exec('node parsers/parserMYPROTEIN.js');


//$bulk = file_get_contents('pipes/bulk_result.pipe');


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>suplementosPT</title>
</head>
<body>

    <h1>suplementosPT</h1>

    <div id="output-container">
        <!-- O conteúdo será inserido aqui -->
        <p> <?php echo $zumub;?> </p> 
         
        <p> <?php echo $bulk;?> </p> 
       
        <p> <?php echo $myProtein;?> </p> 
        
    </div>

    <script>
        

    </script>

</body>
</html>