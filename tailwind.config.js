const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './resources/views/*.blade.php',
        './resources/views/**/*.blade.php',
    ],

    variants: {
        backgroundColor: ['responsive', 'odd', 'hover', 'focus'],
    },
    plugins: [require('@tailwindcss/forms')],
};
