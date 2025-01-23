// Modal Perfil do Usuario

document.addEventListener('DOMContentLoaded', function () {
    const confirmDeletar = document.getElementById('confirmDeletar');

    confirmDeletar.addEventListener('click', function (event) {
        event.preventDefault();

        const idUsuario = confirmDeletar.getAttribute('data-id');

        console.log("ID do usuário:", idUsuario);

        if (!idUsuario) {
            alert('ID do usuário não encontrado.');
            return;
        }

        fetch('deleta_conta.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({ id: idUsuario })
        })
        .then(response => {
            console.log('Status da resposta:', response.status);
            return response.text(); // Capturar o texto da resposta
        })
        .then(text => {
            console.log('Resposta do servidor:', text);
            if (text.includes("Conta deletada com sucesso.")) {
                // Redireciona após a deleção bem-sucedida
                window.location.href = '../login.html';
            } else {
                alert('Erro ao deletar conta. Tente novamente.');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao deletar conta. Tente novamente.');
        });
    });
});


// Link Deleta Conta - impede que o link "Deletar Conta" fique azul após clicar pela primeira vez

// Aguarda o carregamento completo do DOM
document.addEventListener('DOMContentLoaded', function () {
    // Seleciona o link de deletar conta
    const deletaContaLink = document.getElementById('deletaConta');

    // Adiciona um evento de clique ao link
    deletaContaLink.addEventListener('click', function (event) {
        // Previne o comportamento padrão do link
        event.preventDefault();

        // Define a cor do link manualmente para evitar a cor de link visitado
        this.style.color = '#592e0d';

        // Aqui você pode adicionar lógica para abrir o modal, se necessário
        // Exemplo: $('#modalDeletarConta').modal('show');
    });
});
