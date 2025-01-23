document.addEventListener("DOMContentLoaded", function () {
    const infoElements = document.querySelectorAll(".info"); // Seleciona os elementos com a classe 'info'
    const maxLength = 100; // Defina o número máximo de caracteres permitidos

    infoElements.forEach(function (element) {
        let text = element.textContent;
        if (text.length > maxLength) {
            element.textContent = text.substring(0, maxLength) + "..."; // Trunca o texto e adiciona '...'
        }
    });
});
