document.getElementById('formCadastroPetPerdido').addEventListener('submit', function(event) {
    const files = document.getElementById('fileInput').files;
    
    if (files.length === 0) {
        // Impede o envio do formulário
        event.preventDefault();

        // Exibe o modal usando Bootstrap 5
        const modal = new bootstrap.Modal(document.getElementById('modalSemImagem'));
        modal.show();
    }
});