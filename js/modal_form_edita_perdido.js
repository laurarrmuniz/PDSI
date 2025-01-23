document.getElementById('editaPetPerdidoForm').addEventListener('submit', function(event) {
    // Lista de IDs dos campos obrigatórios, incluindo grupos de rádio
    const requiredFields = ['titulo', 'local', 'dia', 'tipo', 'porte', 'sexo'];
    let formIsValid = true;

    requiredFields.forEach(function(fieldId) {
        const field = document.getElementById(fieldId);

        if (fieldId === 'tipo' || fieldId === 'porte' || fieldId === 'sexo') {
            // Verifica se pelo menos um rádio do grupo está selecionado
            const radios = document.querySelectorAll(`input[name="${fieldId}"]`);
            const isChecked = Array.from(radios).some(radio => radio.checked);
            
            if (!isChecked) {
                formIsValid = false;
                radios.forEach(radio => {
                    const legend = radio.closest('fieldset').querySelector('.required');
                    if (legend) {
                        legend.classList.add('invalid');
                    }
                });
            } else {
                radios.forEach(radio => {
                    const legend = radio.closest('fieldset').querySelector('.required');
                    if (legend) {
                        legend.classList.remove('invalid');
                    }
                });
            }
        } else {
            // Verifica se o campo de texto está vazio
            if (!field.value.trim()) {
                formIsValid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        }
    });

    if (!formIsValid) {
        event.preventDefault(); // Impede o envio do formulário
        var myModal = new bootstrap.Modal(document.getElementById('modalCamposObrigatorios'));
        myModal.show(); // Mostra o modal de aviso
    }
});
