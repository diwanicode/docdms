import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    screens: {
        'sm': '640px',
        'md': '768px',
        'lg': '1024px',
        'xl': '1280px'
    }, 
    theme: {
        extend: {
            gridTemplateColumns: {
                7: 'repeat(7, minmax(0, 1fr))',
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            
            colors: {    
                baseColor: { 
                    50: '#f8fafc',  
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0f172a',
                    950: '#020617',
                },                
                brandColor: {
                    50:  '#edf8ff',  // very light blue tint (backgrounds)
                    100: '#d7ecff',
                    200: '#b8e0ff',
                    300: '#87ceff',
                    400: '#4fb2ff',
                    500: '#2690ff',
                    600: '#0f70ff', // Primary brand blue
                    700: '#0859f0',
                    800: '#0e46bf',
                    900: '#123f96',
                    950: '#10285b',
                },
                brandSecondaryColor:{
                    50:  '#fdf3f3',
                    100: '#fbe5e5',
                    200: '#f8d0d0',
                    300: '#f3aeaf',
                    400: '#ea7f80',
                    500: '#dd5657',
                    600: '#c9393a', //  (deep burgundy)
                    700: '#9e2a2b',
                    800: '#8c2829',
                    900: '#752728',
                    950: '#3f1010',
                },
                  primary: {
                    200: '#d7ecfb',
                    300: '#abd8f7',
                    400:'#88bae7',
                    DEFAULT: '#73aee3',
                    600:'#5ea2df',
                    700:'#4995db'
                },
                success: {
                    200: "#b6e6b6",
                    300: "#91d991",
                    400: "#6ccc6c",
                    500: "#4bbf4d",
                    600: "#3ea23f",
                    700: "#317e32",
                    800: "#245b24"
                    }, 
                warning: {
                    200: "#f9fbe8",
                    300: "#f5f8dc",
                    400: "#f2f6d1",
                    500: "#f0f3c6",
                    600: "#d4d7aa",
                    700: "#a4a77f",
                    800: "#767952"
                },  
                error: {
                    200: "#ffe6eb",
                    300: "#ffccd7",
                    400: "#ffb3c3",
                    500: "#ff8099",
                    600: "#e0667f",
                    700: "#b34d63",
                    800: "#803644"
                }  
            }
        },
    },

    plugins: [forms],
};
