const defaultTheme = require("tailwindcss/defaultTheme");

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.svelte",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Nunito", ...defaultTheme.fontFamily.sans],
                chinese: ["chinese_takeawayregular"]
            },
            colors: {
                'primary': 'rgb(var(--color-primary) / <alpha-value>)',
                'primary-dark': 'rgb(var(--color-primary-dark) / <alpha-value>)',
                'secondary': 'rgb(var(--color-secondary) / <alpha-value>)',
                'content': 'rgb(var(--color-content) / <alpha-value>)'
            },
        },
    },

    plugins: [require("@tailwindcss/forms")],
};
