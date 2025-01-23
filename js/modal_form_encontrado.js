document.getElementById('formCadastroPetEncontrado').addEventListener('submit', function(event) {
    const requiredFields = ['titulo', 'local', 'dia', 'tipo', 'porte', 'sexo', 'fileInput'];
    let formIsValid = true;

    requiredFields.forEach(function(fieldId) {
        const field = document.getElementById(fieldId);

        if (fieldId === 'tipo' || fieldId === 'porte' || fieldId === 'sexo') {
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
        } else if (fieldId === 'fileInput') {
            if (!field.files.length) {
                formIsValid = false;
                document.getElementById('fileLabel').classList.add('invalid');
            } else {
                document.getElementById('fileLabel').classList.remove('invalid');
            }
        } else {
            if (!field.value.trim()) {
                formIsValid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        }
    });

    if (!formIsValid) {
        event.preventDefault();
        var myModal = new bootstrap.Modal(document.getElementById('modalCamposObrigatorios'));
        myModal.show();
    }
});
