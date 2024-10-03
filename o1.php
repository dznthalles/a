<?php
class ContaBancaria {
    
    private $saldo = 0;

    public function sacar($valor)
    {
     if ($this->saldo >= $valor) {
     $this->saldo -= $valor;
    } else {
        echo "Saldo Insuficiente";
        return;
     }
    }
public function depositar ($valor){
     $this->saldo += $valor;
}
public function getSaldo(){
   return $this->saldo;

}
}

$conta01 = new ContaBancaria();
$conta01->depositar(50);
$conta01->sacar(2000);

?>