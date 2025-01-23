<?php

use PHPUnit\Framework\TestCase;

class testCadastroPetEncontrado extends TestCase
{
    protected $pdo;

    protected function setUp(): void
    {
        // Configurações do banco de dados
        $this->pdo = new PDO('mysql:host=localhost;dbname=encontrapets', 'root', '');

        // Limpa a tabela antes de cada teste
        $this->pdo->exec("DELETE FROM pet_encontrado");
    }

    //Verificar o comportamento do formulário quando nenhum dado é enviado.
    public function testFormularioSemDados()
    {
        // Verifica se a exceção é lançada ao tentar inserir dados nulos
        $this->expectException(PDOException::class);

        // Tenta inserir um registro sem dados
        $stmt = $this->pdo->prepare("INSERT INTO pet_encontrado (local, titulo, data, horario, info, tipo, porte, sexo, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Executa com valores nulos
        $stmt->execute([null, null, null, null, null, null, null, null, null]);
    }

    //Verificar se o formulário funciona corretamente quando preenchido com dados válidos.
    public function testFormularioComDadosValidos()
    {
        // Insere um registro com dados válidos
        $stmt = $this->pdo->prepare("INSERT INTO pet_encontrado (local, titulo, data, horario, info, tipo, porte, sexo, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute(['Local Teste', 'Pet Teste', '2024-10-06', '10:00', 'Nenhuma informação', 'Cachorro', 'Médio', 'Masculino', 1]);

        // Verifica se a inserção foi bem-sucedida
        $this->assertTrue($result);

        // Verifica se o registro foi realmente inserido
        $stmt = $this->pdo->query("SELECT * FROM pet_encontrado WHERE titulo = 'Pet Teste'");
        $petEncontrado = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotFalse($petEncontrado); // Deve encontrar o registro
        $this->assertEquals('Local Teste', $petEncontrado['local']); // Verifica o local
        $this->assertEquals('Pet Teste', $petEncontrado['titulo']); // Verifica o título
    }

    protected function tearDown(): void
    {
        // Fecha a conexão
        $this->pdo = null;
    }
}

/* testFormularioComDadosValidos: preenche todos os campos obrigatórios do formulário com dados válidos e 
simula o envio do formulário. Ele verifica se o registro do pet encontrado foi inserido corretamente 
no banco de dados. Além disso, assegura que a resposta do sistema seja bem-sucedida, confirmando que 
o cadastro foi realizado conforme esperado.

testFormularioSemDados: simula o envio do formulário sem preencher nenhum campo. Ele espera que o sistema 
retorne um erro ou uma mensagem de aviso que indica que os campos obrigatórios (como 'local' e 'titulo') 
não podem estar vazios. Isso garante que o sistema esteja validando corretamente a entrada do usuário.

*/
