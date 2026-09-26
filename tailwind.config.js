/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./index.php", // Asegúrate de incluir el archivo principal
    "./pages/**/*.php", // Incluye todas las páginas dentro de /pages/
    "./components/**/*.php", // Incluye los componentes
    "./includes/**/*.php", // Incluye cualquier otro archivo relevante
    "./assets/js/**/*.js", // Si usas clases en archivos JS],
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
