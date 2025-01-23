document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const items = document.querySelectorAll(".item");
    const searchButton = document.getElementById("button");

    // Função para realizar a pesquisa
    function performSearch() {
        const query = normalizeText(searchInput.value.toLowerCase());
    
        items.forEach(function (item) {
            const title = normalizeText(item.querySelector("h4").textContent.toLowerCase());
            if (title.includes(query)) {
                item.style.display = "flex"; // Mostrar o item
            } else {
                item.style.display = "none"; // Ocultar o item
            }
        });
    }
    
    // Função para remover acentos e caracteres especiais de um texto
    function normalizeText(text) {
        return text.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    }

    // Evento adicionado clique do botão "Buscar"
    searchButton.addEventListener("click", function (event) {
        event.preventDefault(); // Impede o envio do formulário
        performSearch();
    });

    // Evento adicionado ao pressionar a tecla  no campo de pesquisa
    searchInput.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            event.preventDefault(); // Impede o envio do formulário
            performSearch();
        }
    });
});