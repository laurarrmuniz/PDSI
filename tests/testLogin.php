<?php

use PHPUnit\Framework\TestCase;

class testLogin extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        // Configuração do banco de dados de teste
        $this->pdo = new PDO('mysql:host=localhost;dbname=encontrapets', 'root', '');
        // Inserir um usuário de teste com senha hash
        $senhaHash = password_hash('senha_teste', PASSWORD_DEFAULT);
        $this->pdo->exec("INSERT INTO usuario (email, senha) VALUES ('teste@exemplo.com', '$senhaHash')");
    }

    public function testLogin()
    {
        $_POST['email'] = 'teste@exemplo.com';
        $_POST['senha'] = 'senha_teste';

        // Inclua o caminho correto para o seu script de login
        require 'php/login.php';  

        // Verifica se a sessão foi configurada corretamente
        $this->assertEquals($_SESSION['login'], '1');
        $this->assertEquals($_SESSION['email'], 'teste@exemplo.com');
    }

    protected function tearDown(): void
    {
        // Limpar o banco de dados de teste
        $this->pdo->exec("DELETE FROM usuario WHERE email = 'teste@exemplo.com'");
    }
}
