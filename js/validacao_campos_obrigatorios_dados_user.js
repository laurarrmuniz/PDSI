document.getElementById('editaDadosUser').addEventListener('submit', function(event) {
    const requiredFields = ['nome', 'email', 'telefone', 'endereco', 'data_nasc'];
    let formIsValid = true;

    requiredFields.forEach(function(fieldId) {
        const field = document.getElementById(fieldId);

        // Verifica se o campo está vazio
        if (!field.value.trim()) {
            formIsValid = false;
            field.classList.add('is-invalid'); // Adiciona classe de erro
        } else {
            field.classList.remove('is-invalid'); // Remove classe de erro se o campo estiver preenchido
        }
    });

    // Se o formulário não for válido, exibe o modal e impede o envio
    if (!formIsValid) {
        event.preventDefault(); // Impede o envio do formulário
        var myModal = new bootstrap.Modal(document.getElementById('modalCamposObrigatorios'));
        myModal.show(); // Exibe o modal
    }
});
