<?php
    require_once('classes/autoload.class.php');
?>
<!DOCTYPE html>
<html lang="PT-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP</title>
</head>
<body>
    <section>
        <h1>Calcular máscara de sub-rede IPv4</h1>
    </section>

    <form method="POST">
        <b>IP/CIDR</b> <small>(Ex.: 192.168.0.1/24)</small> <br> 
        <input  type="text" name="ip" value="<?php echo @$_POST['ip'];?>">
        <input  type="text" name="cidr" value="<?php echo @$_POST['cidr'];?>">
        <input style="cursor: pointer;" type="submit" value="Calcular">
    </form>

    <?php
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' && ! empty( $_POST['ip'] ) ) { //Verifica se o formulário enviado
            //previne erros do F5 resubmit
            $ip = new CalculadoraIP($_POST['ip'], $_POST['cidr']);
            if( $ip->valida_endereco() ) {
                echo $ip;
            }
                else {
                    echo 'Endereço IPv4 inválido!';
                }
            } 
    ?>
</body>
</html>