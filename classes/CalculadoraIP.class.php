<?php
    //calculadora de ipv4
    class CalculadoraIP{
    // Endereço IP
    public $endereco;
    
    // Cidr
    public $cidr;
    
    // Endereço IP completo
    public $endereco_completo;

    /**
     * O construtor para criar a classe
    */
    public function __construct($endereco_completo) {
        $this->endereco_completo = $endereco_completo;
        $this->valida_endereco();
    }
    /**
     * Valida o endereço IPv4
     */
    public function valida_endereco() {
        // Expressão regular
        $regexp = '/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\/[0-9]{1,2}$/';
        
        // Verifica o IP/CIDR
        if ( ! preg_match( $regexp, $this->endereco_completo ) ) {
            return false;
        }
        
        // Separa o IP do prefixo CIDR
        $endereco = explode( '/', $this->endereco_completo );
        
        // CIDR
        $this->cidr = (int) $endereco[1];
        
        // Endereço IPv4
        $this->endereco = $endereco[0];
        
        // Verifica o prefixo
        if ( $this->cidr > 32 ) {
            return false;
        }
        
        // Faz um loop e verifica cada número do IP
        foreach( explode( '.', $this->endereco ) as $numero ) {
        
            // Garante que é um número
            $numero = (int) $numero;
            
            // Não pode ser maior que 255 nem menor que 0
            if ( $numero > 255 || $numero < 0 ) {
                return false;
            }
        }
        
        // IP "válido" (correto)
        return true;
    }

    /* Retorna o endereço IPv4/CIDR */
    public function endereco_completo() { 
        return ( $this->endereco_completo ); 
    }

    /* Retorna o endereço IPv4 */
    public function endereco() { 
        return ( $this->endereco ); 
    }

    /* Retorna o prefixo CIDR */
    public function cidr() { 
        return ( $this->cidr ); 
    }

    /* Retorna a máscara de sub-rede */
    public function mascara() {
        if ( $this->cidr() == 0 ) {
            return '0.0.0.0';
        }

        return ( 
            long2ip(
                ip2long("255.255.255.255") << ( 32 - $this->cidr ) 
            )
        );
    }

    /* Retorna a rede na qual o IP está */
    public function rede() {
        if ( $this->cidr() == 0 ) {
            return '0.0.0.0';
        }

        return (
            long2ip( 
                ( ip2long( $this->endereco ) ) & ( ip2long( $this->mascara() ) )
            )
        );
    }

    /* Retorna o IP de broadcast da rede */
    public function broadcast() {
        if ( $this->cidr() == 0 ) {
            return '255.255.255.255';
        }
        
        return (
            long2ip( ip2long($this->rede() ) | ( ~ ( ip2long( $this->mascara() ) ) ) )
        );
    }
    
    /* Retorna o número total de IPs (com a rede e o broadcast) */
    public function total_ips() {
        return( pow(2, ( 32 - $this->cidr() ) ) );
    }
    
    /* Retorna os número de IPs que podem ser utilizados na rede */
    public function ips_rede() {
        if ( $this->cidr() == 32 ) {
            return 0;
        } elseif ( $this->cidr() == 31 ) {
            return 0;
        }
        
        return( abs( $this->total_ips() - 2 ) );
    }
    
    /* Retorna os número de IPs que podem ser utilizados na rede */
    public function primeiro_ip() {
        if ( $this->cidr() == 32 ) {
            return null;
        } elseif ( $this->cidr() == 31 ) {
            return null;
        } elseif ( $this->cidr() == 0 ) {
            return '0.0.0.1';
        }
        
        return (
            long2ip( ip2long( $this->rede() ) | 1 )
        );
    }
    
    /* Retorna os número de IPs que podem ser utilizados na rede */
    public function ultimo_ip() {
        if ( $this->cidr() == 32 ) {
            return null;
        } elseif ( $this->cidr() == 31 ) {
            return null;
        }
    
        return (
            long2ip( ip2long( $this->rede() ) | ( ( ~ ( ip2long( $this->mascara() ) ) ) - 1 ) )
        );
    }

    public static function CatchIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if($ip = "::1") {
            $ip = "Localhost"; 
        }


        return $ip;
    }
    public function __toString() {
        $str = "<h2>Configurações de rede para <span>" . $this->endereco_completo . "</span></h2>
                <pre>
                    <b>Endereço/Rede: </b>  ".$this->endereco_completo()."    <br>
                    <b>Endereço: </b>   ".$this->endereco()." <br>
                    <b>Prefixo CIDR: </b>   ".$this->cidr()." <br>
                    <b>Máscara de sub-rede: </b>    ".$this->mascara()."    <br>
                    <b>IP da Rede: </b> ".$this->rede()."'/'".$this->cidr()."   <br>
                    <b>Broadcast da Rede: </b>  ".$this->broadcast()."    <br>
                    <b>Primeiro Host: </b>  ".$this->primeiro_ip()."  <br>
                    <b>Último Host: </b>    ".$this->ultimo_ip()."    <br>
                    <b>Total de IPs:  </b>  ".$this->total_ips()."   <br>
                    <b>Hosts: </b>  ".$this->ips_rede() . "
                </pre>";
        return $str;
        
    }
}      
    
    
?>