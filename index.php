<?php
    require_once('classes/autoload.class.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP</title>
</head>
<body>
    <header>
        <h1>Cálculo de máscara de sub-rede IPv4</h1>
    </header>

    <section>
        <h1>Calcular máscara de sub-rede IPv4</h1>
    </section>

    <form method="POST">
        <b>IP/CIDR</b> <small>(Ex.: 192.168.0.1/24)</small> <br> 
        <input  type="text" name="ip" value="<?php echo @$_POST['ip'];?>">
        <input style="cursor: pointer;" type="submit" value="Calcular">
    </form>

    <?php
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' && ! empty( $_POST['ip'] ) ) { //Verifica se o formulário enviado
            //previne erros do F5 resubmit
            $ip = new CalculadoraIP($_POST['ip']);

            if( $ip->valida_endereco() ) {
                echo $ip;
            }
                else {
                    echo 'Endereço IPv4 inválido!';
                }
                
                $ip = CalculadoraIP::CatchIP();

                echo "<b>IP da máquina:  </b>". $ip . '<br>';

            } 

        

    ?>
</body>
</html>