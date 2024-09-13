<?php
$host = 'localhost';
$dbname = 'cadastro_clientes';
$username = 'root'; // Altere conforme suas configurações
$password = ''; // Altere conforme suas configurações

// Conectar ao banco de dados
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Não foi possível conectar ao banco de dados: " . $e->getMessage());
}

// Processar o upload da foto
$fotoPath = '';
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($_FILES['foto']['name']);
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
        $fotoPath = basename($_FILES['foto']['name']);
    } else {
        die('Erro ao enviar o arquivo.');
    }
}

// Receber os dados do formulário
$nome_completo = $_POST['nome_completo'];
$cpf = $_POST['cpf'];
$data_nascimento = $_POST['data_nascimento'];
$nacionalidade = $_POST['nacionalidade'];
$estado_civil = $_POST['estado_civil'];
$email = $_POST['email'];

// Calcular a idade
$dateOfBirth = new DateTime($data_nascimento);
$today = new DateTime('today');
$idade = $dateOfBirth->diff($today)->y;

// Inserir os dados no banco de dados
$sql = "INSERT INTO clientes (nome_completo, cpf, data_nascimento, nacionalidade, idade, estado_civil, email, foto) 
        VALUES (:nome_completo, :cpf, :data_nascimento, :nacionalidade, :idade, :estado_civil, :email, :foto)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':nome_completo' => $nome_completo,
    ':cpf' => $cpf,
    ':data_nascimento' => $data_nascimento,
    ':nacionalidade' => $nacionalidade,
    ':idade' => $idade,
    ':estado_civil' => $estado_civil,
    ':email' => $email,
    ':foto' => $fotoPath
]);

echo "Cadastro realizado com sucesso!";
?>
