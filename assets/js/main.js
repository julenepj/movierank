//alert("hola mundo")

const cookie = (event) => {
  event.preventDefault();
  console.log(document.getElementById("cookies").checked);
};
document.getElementById("boton").addEventListener("click", cookie);
