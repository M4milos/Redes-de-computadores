<?php
    class CalculadoraIP{
        public $ip;
        public $cidr;
        public $endereco;

        public function __construct($ip, $cidr){ // Construtor
            $this->ip = $ip; // Recebe o IP
            $this->cidr = $cidr; // Recebe o CIDR
            $this->endereco = $this->endereco(); // Recebe o endereço
        }

        public function endereco(){
            return long2ip(ip2long($this->ip)); // Converte o IP para o formato decimal
        } 

        public function mascara(){
            return long2ip(-1 << (32 - (int)$this->cidr)); // Converte o CIDR para o formato decimal
        }

        public function valida_endereco(){
            if (filter_var($this->endereco, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) { // Valida o IP
                return true; // IP "válido" (correto)
            } else { 
                return false; // IP "inválido"
            }
        }

        public function valida_cidr(){ 
            if (filter_var($this->cidr, FILTER_VALIDATE_INT, array("options" => array("min_range"=>0, "max_range"=>32)))) { // Valida o CIDR
                return true; // CIDR "válido" (correto)
            } else {
                return false; // CIDR "inválido"
            }
        }

        public function valida_ip(){
            if ($this->valida_endereco() && $this->valida_cidr()) { // Valida o IP e o CIDR
                return true; // IP "válido" (correto)
            } else {
                return false; // IP "inválido"
            }
        }

        public function rede(){
            return long2ip(ip2long($this->endereco) & ip2long($this->mascara())); // Converte o IP e a máscara para o formato decimal
        }

        public function broadcast(){
            return long2ip(ip2long($this->rede()) | ~ip2long($this->mascara())); // Converte o IP e a máscara para o formato decimal
        }

        public function endereco_completo(){
            return $this->endereco . "/" . $this->cidr; // Retorna o endereço completo
        }

        public function cidr(){
            return $this->cidr; // Retorna o CIDR
        }

    public function primeiro_ip() {
        if ( $this->cidr() == 32 ) { // Se o CIDR for 32
            return null;
        } elseif ( $this->cidr() == 31 ) { // Se o CIDR for 31
            return null;
        } elseif ( $this->cidr() == 0 ) { // Se o CIDR for 0
            return '0.0.0.1';
        }return ( 
            long2ip(ip2long($this->rede())|1)); // Converte o IP para o formato decimal
    }
    
    public function ultimo_ip() {
        if ( $this->cidr() == 32 ) { // Se o CIDR for 32
            return null;
        } elseif ( $this->cidr() == 31 ) { // Se o CIDR for 31 
            return null; 
        }return (
            long2ip(ip2long($this->rede())|((~(ip2long($this->mascara())))-1))); // Converte o IP para o formato decimal
    } 

    public function redebinario(){
        $binario = ""; // Inicializa a variável
        $octetos = explode(".", $this->endereco);  // Separa os octetos do IP
        foreach ($octetos as $octeto) { // Percorre os octetos 
            $binario .= str_pad(decbin($octeto), 8, "0", STR_PAD_LEFT); // Converte os octetos para binário
        }
        return implode(".", str_split($binario, 8)); // Retorna o IP em binário
    }

    public function mascarabinario(){
        $binario = ""; // ""
        $octetos = explode(".", $this->mascara()); // ""
        foreach ($octetos as $octeto) {
            $binario .= str_pad(decbin($octeto), 8, "0", STR_PAD_LEFT); // ""
        }
        return implode(".", str_split($binario, 8));  // ""
    }

    public function broadcastbinario(){
        $binario = ""; // ""
        $octetos = explode(".", $this->broadcast()); // ""
        foreach ($octetos as $octeto) { // ""
            $binario .= str_pad(decbin($octeto), 8, "0", STR_PAD_LEFT); // ""
        }
        return implode(".", str_split($binario, 8)); // ""
    }

    public function primeiropbinario(){
        $binario = ""; // ""
        $octetos = explode(".", $this->primeiro_ip()); // ""
        foreach ($octetos as $octeto) { // ""
            $binario .= str_pad(decbin($octeto), 8, "0", STR_PAD_LEFT); // ""
        } 
        return implode(".", str_split($binario, 8)); // ""
    }

    public function ultimopbinario(){
        $binario = ""; // ""
        $octetos = explode(".", $this->ultimo_ip()); // ""
        foreach ($octetos as $octeto) { // ""
            $binario .= str_pad(decbin($octeto), 8, "0", STR_PAD_LEFT); // ""
        }
        return implode(".", str_split($binario, 8)); // ""
    }
    
    public function cidrbinario(){
        $binario = ""; // ""
        $octetos = explode(".", $this->cidr()); // ""
        foreach ($octetos as $octeto) { // ""
            $binario .= str_pad(decbin($octeto), 8, "0", STR_PAD_LEFT); // ""
        }
        return implode(".", str_split($binario, 8)); // ""
    }

    /* a
            Desenvolver software para calcular os endereços de Rede, Broadcast, 
            primeiro IP util e ultimo IP util sobre endereço qualquer e mascara de rede

            Lembrando que teremos como entrada "endereço de IP e Mascara de rede"

            Obs. O resultado deve ser mostrado em base decimal e binária.

            Att
        */

        public function __toString(){ // Método mágico para exibir o objeto
            return "Endereço: " . $this->endereco_completo() . "<br>
                    Rede: " . $this->rede() . "<br>
                    Rede binária: " . $this->redebinario() . "<br>
                    Broadcast: " . $this->broadcast() . "<br>
                    Broadcast binário: " . $this->broadcastbinario() . "<br>
                    Primeiro IP: " . $this->primeiro_ip() . "<br>
                    Primeiro IP binário: " . $this->primeiropbinario() . "<br>
                    Ultimo IP: " . $this->ultimo_ip() . "<br>
                    Ultimo IP binário: " . $this->ultimopbinario() . "<br>
                    Máscara: " . $this->mascara() . "<br>
                    Máscara binária: " . $this->mascarabinario() . "<br>
                    Cidr: " . $this->cidr() . "<br>
                    Cidr binário: " . $this->cidrbinario() . "<br>";
        }
    }
?>