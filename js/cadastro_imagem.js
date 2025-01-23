const image = document.getElementById('image');
        const fileInput = document.getElementById('fileInput');

        // Quando a imagem for clicada, dispara o clique no input de arquivo
        image.addEventListener('click', () => {
            fileInput.click();
        });

        // Quando o usuário seleciona um novo arquivo
        fileInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Atualiza a imagem com o novo arquivo selecionado
                    image.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });