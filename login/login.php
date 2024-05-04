<?php 

require_once"../Sql.php";

if ( isset( $_POST['inputEmailLogin'] , $_POST['inputSenhaLogin'] ) && $_POST['inputSenhaLogin'] !=0 ) {

    $email = $_POST['inputEmailLogin'];
    $senha = $_POST['inputSenhaLogin'];
    
    try {
    
        // Define o modo de erro para exceções
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = '$email'");
        $stmt->execute();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {

            //verifica a veracidade da senha
            if(password_verify($senha, $row['senha'])){
                
                // echo "Correta! <br>";
                require_once'../jogo/jogo.php';

            //caso onde a senha esta errada
            } else {

                echo "Errada!";
                echo "<a href="."../cadastro/cadastro.html"." rel="."noopener noreferrer".">Cadastro</a>";
                

            }

        }

    } catch (PDOException $e) {

        echo "Erro de conexão com o banco de dados: " . $e->getMessage();
    }
    
}

