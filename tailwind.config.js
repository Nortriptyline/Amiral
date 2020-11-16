const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    purge: [
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            maxHeight: {
                '0': '0',
                '1/4vh': '25%',
                '50vh': '50%',
                '75vh': '75vh',
                'full': '100%',
            },
        },
    },
    
    variants: {
        opacity: ['responsive', 'hover', 'focus', 'disabled'],
        overflow: ['responsive', 'hover'],
    },

    plugins: [require('@tailwindcss/ui')],
};
