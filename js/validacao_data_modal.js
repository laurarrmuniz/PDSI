document.getElementById('dia_filtro').addEventListener('change', function() {
    const inputDate = new Date(this.value); // Data inserida
    const today = new Date(); // Data atual

    // Zera a hora para garantir que apenas a data seja comparada
    today.setHours(0, 0, 0, 0);

    // Obtém o elemento de mensagem de erro
    const errorMessage = document.getElementById('errorDia');

    // Verifica se a data é futura
    if (inputDate > today) {
        this.classList.add('is-invalid'); // Adiciona a classe para indicar campo inválido
        errorMessage.classList.remove('d-none'); // Mostra a mensagem de erro
    } else {
        this.classList.remove('is-invalid'); // Remove a classe se estiver válida
        errorMessage.classList.add('d-none'); // Oculta a mensagem de erro
    }
});