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
        // Redireciona para o formulário de cadastro novamente ou exibe uma mensagem de erro
        echo "Email já está em uso.";
    } else {
        // Hash da senha
        $hashDaSenha = password_hash($senha, PASSWORD_DEFAULT);

        // Insere o novo usuário no banco de dados
        $sqlInsert = "INSERT INTO usuarios (email, senha) VALUES (:email, :senha)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bindParam(':email', $email, PDO::PARAM_STR);
        $stmtInsert->bindParam(':senha', $hashDaSenha, PDO::PARAM_STR);

        try {
            //Sucesso em cadastrar
            $stmtInsert->execute();
            echo "Cadastro realizado com sucesso!\n$email";
        } catch (PDOException $e) {
            //Fala em cadastrar
            echo "Erro ao cadastrar usuário: " . $e->getMessage();
        }
    }
} else {
    echo "Por favor, preencha todos os campos!";
}
?>
