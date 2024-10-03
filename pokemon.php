<?php

class Pokemon 
{
    private $name;
    private $hp;
    private $attack;
    private $defense;
    private $type;
    private $imageUrl; // Atributo para armazenar a URL da imagem

    public function __construct($name, $hp, $attack, $defense, $type, $imageUrl)
    {
        $this->name = $name;
        $this->hp = $hp;
        $this->attack = $attack;
        $this->defense = $defense;
        $this->type = $type;
        $this->imageUrl = $imageUrl; // Inicializa a URL da imagem
    }

    public function getName(){
        return $this->name;
    }
    public function getHp(){
        return $this->hp;
    }
    public function getAttack(){
        return $this->attack;
    }
    public function getDefense(){
        return $this->defense;
    }
    public function getType(){
        return $this->type;
    }
    public function getImageUrl() {
        return $this->imageUrl; // Método para obter a URL da imagem
    }

    public function displayInfo(){
        echo "<div style='text-align: center;'>";
        echo "Nome: $this->name <br>";
        echo "Tipo: $this->type <br>";
        echo "HP: $this->hp <br>";
        echo "Ataque: $this->attack <br>";
        echo "Defesa: $this->defense <br>";
        echo "<img src='" . $this->imageUrl . "' alt='" . $this->name . "' width='200'><br>"; // Exibe a imagem
        echo "</div>";
    }

    public function attack(Pokemon $opponent){
        $damage = $this->attack - $opponent->getDefense();

        if ($damage > 0){
            echo "{$this->name} atacou {$opponent->getName()} causando {$damage} de dano! <br>";
            $opponent->takeDamage($damage);
        } else {
            echo "{$this->name} atacou {$opponent->getName()} mas não causou nenhum dano! <br>";
        }
    }

    public function takeDamage($damage) {
        $this->hp -= $damage;

        if ($this->hp <= 0) {
            $this->hp = 0;
            echo "{$this->name} foi derrotado! <br>";
        } else {
            echo "{$this->name} tem {$this->hp} de HP restante <br>";
        }
    }

    public function isAlive(){
        return $this->hp > 0;
    }
}

function getPokemonData($name) {
    $url = "https://pokeapi.co/api/v2/pokemon/$name";
    $response = file_get_contents($url);
    
    if ($response === FALSE) {
        return NULL;
    }

    return json_decode($response, true);
}

function createPokemon($name) {
    $data = getPokemonData($name);
    
    if ($data === NULL) {
        return NULL;
    }

    $hp = isset($data['stats'][0]['base_stat']) ? $data['stats'][0]['base_stat'] : 0;
    $attack = isset($data['stats'][1]['base_stat']) ? $data['stats'][1]['base_stat'] : 0;
    $defense = isset($data['stats'][2]['base_stat']) ? $data['stats'][2]['base_stat'] : 0;
    $type = isset($data['types'][0]['type']['name']) ? $data['types'][0]['type']['name'] : 'unknown';

    // URL do GIF do Pokémon
    $imageUrl = isset($data['sprites']['other']['showdown']['front_default']) ? $data['sprites']['other']['showdown']['front_default'] : '';

    return new Pokemon($name, $hp, $attack, $defense, $type, $imageUrl);
}

// Recebendo os nomes dos Pokémons e treinadores do formulário
$pokemon1Name = $_POST['pokemon1'] ?? '';
$pokemon2Name = $_POST['pokemon2'] ?? '';
$trainer1Name = $_POST['trainer1'] ?? '';
$trainer2Name = $_POST['trainer2'] ?? '';

$pokemon1 = createPokemon($pokemon1Name);
$pokemon2 = createPokemon($pokemon2Name);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batalha Pokémon</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin: 0;
            height: 100vh;
            background-color: #f0f0f0;
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        .pokemon-info {
            text-align: center;
        }
        .pokemon-info img {
            display: block;
            margin: 0 auto;
        }
        .winner-message {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            color: #007bff;
        }
    </style>
</head>
<body>

<h1>Batalha Pokémon</h1>

<form method="POST" action="">
    <label for="pokemon1">Nome do Pokémon 1:</label>
    <input type="text" id="pokemon1" name="pokemon1" required><br><br>
    <label for="trainer1">Nome do Treinador 1:</label>
    <input type="text" id="trainer1" name="trainer1" required><br><br>
    <label for="pokemon2">Nome do Pokémon 2:</label>
    <input type="text" id="pokemon2" name="pokemon2" required><br><br>
    <label for="trainer2">Nome do Treinador 2:</label>
    <input type="text" id="trainer2" name="trainer2" required><br><br>
    <input type="submit" value="Iniciar Batalha">
</form>

<?php
if ($pokemon1 === NULL && $pokemon2 === NULL) {
    echo "Por favor, insira os nomes dos Pokémons e inicie a batalha.<br>";
} else {
    if ($pokemon1 === NULL) {
        echo "Pokémon 1 inválido!<br>";
    } else {
        echo "<div class='pokemon-info'>";
        echo "Informações do Pokémon 1 (Treinador: $trainer1Name):<br>";
        $pokemon1->displayInfo();
        echo "</div>";
    }

    if ($pokemon2 === NULL) {
        echo "Pokémon 2 inválido!<br>";
    } else {
        echo "<div class='pokemon-info'>";
        echo "Informações do Pokémon 2 (Treinador: $trainer2Name):<br>";
        $pokemon2->displayInfo();
        echo "</div>";
    }

    if ($pokemon1 !== NULL && $pokemon2 !== NULL) {
        echo "<hr>";
        echo "Início do duelo: " . $pokemon1->getName() . " (Treinador: $trainer1Name) x " . $pokemon2->getName() . " (Treinador: $trainer2Name)";
        echo "<hr>";

        while ($pokemon1->isAlive() && $pokemon2->isAlive()) {
            $pokemon1->attack($pokemon2);

            if ($pokemon2->isAlive()) {
                $pokemon2->attack($pokemon1);
            }
            echo "<br>";
        }

        echo "<br>";
        echo "FIM DO DUELO";

        // Determinar o vencedor
        if ($pokemon1->isAlive()) {
            $winner = $pokemon1;
            $winnerTrainer = $trainer1Name;
        } else {
            $winner = $pokemon2;
            $winnerTrainer = $trainer2Name;
        }

        // Exibir o Pokémon vencedor
        echo "<div class='winner-message'>";
        echo "{$winnerTrainer} e seu Pokemon {$winner->getName()} venceram o duelo!<br>";
        $winner->displayInfo();
        echo "</div>";
    }
}
?>

</body>
</html>
