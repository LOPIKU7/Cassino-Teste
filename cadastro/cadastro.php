<?php
require_once "../Sql.php";

if (isset($_POST['inputEmailCadastro'], $_POST['inputSenhaCadastro']) && !empty($_POST['inputSenhaCadastro'])) {
    $email = $_POST['inputEmailCadastro'];
    $senha = $_POST['inputSenhaCadastro'];

    // Define o modo de erro para exceções
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta SQL para verificar se o email já foi cadastrado
    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    // Verifica se a consulta retornou algum resultado
    if ($stmt->rowCount() > 0) {
        echo "Email já está em uso.";
        // Redireciona para o formulário de cadastro novamente ou exibe uma mensagem de erro
    } else {
        // Hash da senha
        $hashDaSenha = password_hash($senha, PASSWORD_DEFAULT);

        // Insere o novo usuário no banco de dados
        $sqlInsert = "INSERT INTO usuarios (email, senha) VALUES (:email, :senha)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bindParam(':email', $email, PDO::PARAM_STR);
        $stmtInsert->bindParam(':senha', $hashDaSenha, PDO::PARAM_STR);

        try {
            $stmtInsert->execute();
            echo "Cadastro realizado com sucesso!\n$email";
            // Aqui você pode redirecionar o usuário para uma página de sucesso ou para outra página que desejar
        } catch (PDOException $e) {
            echo "Erro ao cadastrar usuário: " . $e->getMessage();
            // Se houver um erro ao inserir o usuário, você pode redirecionar de volta ao formulário ou lidar com o erro de outra forma
        }
    }
} else {
    echo "Por favor, preencha todos os campos!";
}
?>
