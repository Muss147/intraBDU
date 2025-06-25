/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/front/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#2b62aa',
        secondary: '#6ca942',
      },
      fontFamily: {
        sans: ['Roboto', 'Arial', 'sans-serif'],  // Utilisé avec font-sans
        roboto: ['Roboto', 'Arial', 'sans-serif'], // Utilisé avec font-roboto
        arial: ['Arial', 'sans-serif'],           // Utilisé avec font-arial si besoin
      },
    },
  },
  plugins: [],
}
