function sendForm(form) {
    let xhr = new XMLHttpRequest();
    
    xhr.open("POST", form.getAttribute("action"));
    xhr.responseType = 'json';

    xhr.onload = function () {
        if (xhr.status !== 200 || xhr.response === null) {
            console.log("A resposta não pode ser obtida");
            console.log(xhr.status);
            return;
        }

        let loginResponse = xhr.response;
        if (loginResponse.success) {
            window.location = loginResponse.detail;
        } else {
            document.querySelector("#loginFailMsg").textContent = loginResponse.detail;
            document.querySelector("#loginFailMsg").style.display = 'block';
            form.senha.value = "";
            form.senha.focus();
        }
    };

    xhr.onerror = function () {
        console.error("Erro de rede - requisição não finalizada");
    };

    xhr.send(new FormData(form));
}

window.onload = function () {
    const form = document.querySelector("#formLogin");
    form.onsubmit = function (e) {
        sendForm(form);
        e.preventDefault();
    };
};
