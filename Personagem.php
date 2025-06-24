<?php
class Raca {
    public $nome;
    public $bonus;

    public function __construct($nome, $bonus) {
        $this->nome = $nome;
        $this->bonus = $bonus;
    }
}

class Classe {
    public $nome;

    public function __construct($nome) {
        $this->nome = $nome;
    }
}

class Personagem {
    public $raca;
    public $classe;
    public $atributos;
    public $pontosDeVida;

    public function __construct($raca, $classe) {
        $this->raca = $raca;
        $this->classe = $classe;
        $this->atributos = [
            'forca' => 8, 'inteligencia' => 8, 'destreza' => 8,
            'constituicao' => 8, 'sabedoria' => 8, 'carisma' => 8
        ];
    }

    public function distribuirBonus($bonus) {
        $totalBonus = array_sum($bonus);
        if ($totalBonus > 27) {
            echo "<div class='error'>Erro: Você distribuiu mais de 27 pontos de bônus.</div>";
            return false;
        }

        foreach ($bonus as $atributo => $valor) {
            if ($valor >= 0 && $valor <= 7) {
                $this->atributos[$atributo] += $valor;
            } else {
                echo "<div class='error'>Erro: Valor inválido para $atributo. Use entre 0 e 7.</div>";
                return false;
            }
        }

        return true;
    }

    public function aplicarBonusRacial() {
        foreach ($this->raca->bonus as $atributo => $bonus) {
            $this->atributos[$atributo] += $bonus;
        }
    }

    public function calcularPontosDeVida() {
        $modCon = floor(($this->atributos['constituicao'] - 10) / 2);
        $this->pontosDeVida = 10 + $modCon;
    }

    public function mostrarStatus() {
        echo "<div class='status'>";
        echo "<h2>Status do Personagem</h2>";
        echo "<p><strong>Raça:</strong> {$this->raca->nome}</p>";
        echo "<p><strong>Classe:</strong> {$this->classe->nome}</p>";
        echo "<p><strong>Pontos de Vida:</strong> {$this->pontosDeVida}</p>";
        echo "<h3>Atributos:</h3><ul>";
        foreach ($this->atributos as $nome => $valor) {
            echo "<li><strong>" . ucfirst($nome) . ":</strong> $valor</li>";
        }
        echo "</ul></div>";
    }
}

$racas = [
    1 => new Raca('Humano', ['forca' => 1, 'destreza' => 1]),
    2 => new Raca('Elfo', ['destreza' => 2, 'sabedoria' => 1]),
    3 => new Raca('Anão', ['constituicao' => 2, 'forca' => 1])
];

$classes = [
    1 => new Classe('Guerreiro'),
    2 => new Classe('Mago'),
    3 => new Classe('Ladino')
];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criação de Personagem</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 20px;
        }

        h1, h2, h3 {
            color: #333;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        label, select, input {
            display: block;
            margin-bottom: 10px;
            width: 100%;
        }

        input[type=number] {
            padding: 5px;
        }

        button {
            padding: 10px;
            width: 100%;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            background: #ffe0e0;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
        }

        .status {
            background: #dff0d8;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            width: 400px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
<h1>Criação de Personagem</h1>

<form method="post">
    <label>Raça:</label>
    <select name="raca">
        <option value="1">Humano</option>
        <option value="2">Elfo</option>
        <option value="3">Anão</option>
    </select>

    <label>Classe:</label>
    <select name="classe">
        <option value="1">Guerreiro</option>
        <option value="2">Mago</option>
        <option value="3">Ladino</option>
    </select>

    <h3>Distribua 27 pontos bônus (cada atributo começa com 8)</h3>

    <?php
    $atributos = ['forca', 'inteligencia', 'destreza', 'constituicao', 'sabedoria', 'carisma'];
    foreach ($atributos as $atributo) {
        echo ucfirst($atributo) . ": <input type='number' name='bonus[$atributo]' min='0' max='7' required><br>";
    }
    ?>

    <button type="submit">Criar Personagem</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $racaId = (int) $_POST['raca'];
    $classeId = (int) $_POST['classe'];
    $bonus = $_POST['bonus'];

    if (!isset($racas[$racaId]) || !isset($classes[$classeId])) {
        echo "<div class='error'>Raça ou Classe inválida.</div>";
    } else {
        $personagem = new Personagem($racas[$racaId], $classes[$classeId]);
        if ($personagem->distribuirBonus($bonus)) {
            $personagem->aplicarBonusRacial();
            $personagem->calcularPontosDeVida();
            $personagem->mostrarStatus();
        }
    }
}
?>
</body>
</html>
