<?php

use PHPUnit\Framework\TestCase;

class testEditaUser extends TestCase
{
    protected $pdo;
    protected $usuario_id;

    protected function setUp(): void
    {
        // Conecta ao banco de dados
        $this->pdo = new PDO('mysql:host=localhost;dbname=encontrapets', 'root', '');

        // Cria um usuário de teste
        $stmt = $this->pdo->prepare("INSERT INTO usuario (nome, email, telefone, endereco, data_nasc, senha) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            'Teste Usuário', 
            'teste@exemplo.com', 
            '123456789', 
            'Endereço Teste', 
            '2000-01-01', 
            password_hash('senha123', PASSWORD_DEFAULT)
        ]);

        // Pega o ID do usuário recém-criado
        $this->usuario_id = $this->pdo->lastInsertId();
    }

    public function testUserEditDataUpdate()
    {
        // Simula o método de requisição como POST
        $_SERVER['REQUEST_METHOD'] = 'POST';

        // Simula dados do formulário de edição com os novos dados para o usuário de teste
        $_POST['id'] = $this->usuario_id;
        $_POST['nome'] = 'Nome Editado';
        $_POST['email'] = 'editado@exemplo.com';
        $_POST['telefone'] = '987654321';
        $_POST['endereco'] = 'Endereço Editado';
        $_POST['data_nasc'] = '1999-12-31';
        $_POST['senha'] = 'novaSenha123';

        // Inclui o script de atualização de dados
        require_once __DIR__ . '/../php/atualiza_dados_user.php';

        // Verifica se os dados foram atualizados no banco
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE id = ?");
        $stmt->execute([$this->usuario_id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Asserts para verificar se os dados foram realmente alterados
        $this->assertEquals('Nome Editado', $usuario['nome']);
        $this->assertEquals('editado@exemplo.com', $usuario['email']);
        $this->assertEquals('987654321', $usuario['telefone']);
        $this->assertEquals('Endereço Editado', $usuario['endereco']);
        $this->assertEquals('1999-12-31', $usuario['data_nasc']);
        
        // Verifica se a senha foi corretamente alterada
        $this->assertTrue(password_verify('novaSenha123', $usuario['senha']));
    }

    protected function tearDown(): void
    {
        // Exclui o usuário de teste após o teste ser finalizado
        $this->pdo->exec("DELETE FROM usuario WHERE id = " . $this->usuario_id);
    }
}
