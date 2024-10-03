<?php
class Funcionario{
   private $nome;
   private $funcao;

   public function __construct($nome, $funcao)
   {
    $this->nome = $nome;
    $this->$funcao = $funcao;
   }
   public function getNome(){
   return $this->nome; 
}
}
   
   


$func01 = new Funcionario ("Joao","TI");
$func02 = new Funcionario("Lucas","TI");
echo $func01-> getNome();
echo $func01-> getFuncao();
$func01->("ADM");

?>