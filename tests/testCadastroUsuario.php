
<?php

use PHPUnit\Framework\TestCase;

class TestCadastroUsuario extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO('mysql:host=localhost;dbname=encontrapets', 'root', '');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Limpar dados existentes para garantir que os testes comecem com um banco de dados limpo
        $stmt = $this->pdo->prepare("DELETE FROM usuario WHERE email IN (?, ?)");
        $stmt->execute(['joao_teste@email.com', 'novoemail@email.com']);

        // Inserir um usuário de teste para verificar se o email já está cadastrado
        $senhaHash = password_hash('senha_teste', PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO usuario (nome, telefone, email, senha, endereco, data_nasc) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute(['João da Silva', '(34) 99825-6578', 'joao_teste@email.com', $senhaHash, 'Av. João Naves 202, Uberlândia - MG', '23/06/2000']);
    }

    public function testCadastroComSucesso()
    {
        $_POST['nome'] = 'Novo Usuario';
        $_POST['email'] = 'novoemail@email.com';
        $_POST['endereco'] = 'Rua da Alegria 202';
        $_POST['senha'] = '123';
        $_POST['datanasc'] = '23/06/2002';
        $_POST['telefone'] = '(34) 99825-6577';

        // Caminho para o seu script de cadastro
        ob_start();
        require 'php/cadastroUsuario.php';
        $output = ob_get_clean();

        // Mostrar a saída para depuração
        echo "Output do teste de cadastro com e-mail existente:\n$output\n";

        // Verifique a saída esperada
        $this->assertStringContainsString('Email já cadastrado.', $output);
    }

    public function testCadastroEmailExistente()
    {
        $_POST['nome'] = 'Maria';
        $_POST['email'] = 'maria@email.com'; // Email já existente no banco de dados
        $_POST['endereco'] = 'Bairro Santa Mônica, Uberlândia - MG';
        $_POST['senha'] = '123';
        $_POST['datanasc'] = '23/06/2002';
        $_POST['telefone'] = '(34) 95847-5698';

        // Caminho para o seu script de cadastro
        ob_start();
        require 'php/cadastroUsuario.php';
        $output = ob_get_clean();

        // Mostrar a saída para depuração
        echo "Output do teste de cadastro com e-mail existente:\n$output\n";

        // Verifique a saída esperada
        $this->assertStringContainsString('Email já cadastrado.', $output);
    }

    /*protected function tearDown(): void
    {
        // Adiciona uma mensagem de depuração antes de tentar deletar os registros
        echo "Limpando dados do banco de dados...\n";

        $stmt = $this->pdo->prepare("DELETE FROM usuario WHERE email IN (?, ?)");
        $stmt->execute(['novoemail@email.com', 'joao_teste@email.com']);

        // Verificar se a exclusão foi bem-sucedida
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM usuario WHERE email IN (?, ?)");
        $stmt->execute(['novoemail@email.com', 'joao_teste@email.com']);
        $count = $stmt->fetchColumn();
        echo "Número de registros restantes: $count\n";

        // Adicione uma verificação adicional para confirmar que todos os dados foram excluídos
        $this->assertEquals(0, $count, "Alguns registros ainda existem no banco de dados.");
    }*/
}

?>