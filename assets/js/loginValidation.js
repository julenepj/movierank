document.addEventListener("DOMContentLoaded", function(){
    const form = document.getElementById("loginForm");
    const clientErrorDiv = document.getElementById("clientError");

    form.addEventListener("submit", function(e){
        // Limpiar cualquier mensaje de error previo
        clientErrorDiv.style.display = "none";
        clientErrorDiv.textContent = "";

        // Obtener los valores de los campos (eliminando espacios en blanco)
        const username = document.getElementById("user").value.trim();
        const password = document.getElementById("pass").value.trim();

        // Si alguno de los campos está vacío, evitar el envío y mostrar el error
        if(username === "" || password === ""){
            e.preventDefault();
            clientErrorDiv.textContent = "Debes introducir el usuario y la contraseña obligatoriamente";
            clientErrorDiv.style.display = "block";
        }
    });
});
