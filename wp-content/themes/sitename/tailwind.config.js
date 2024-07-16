const { addDynamicIconSelectors } = require('@iconify/tailwind');

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./**/*.php'],
  theme: {
    container: {
      center: true,
    },
    extend: {
      colors: { },
      fontFamily: { },
      fontSize: { }
    }, 
  },
  plugins: [
    require('@tailwindcss/typography'),
    addDynamicIconSelectors(),
  ],
}