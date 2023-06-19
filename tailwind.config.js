/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./templates/**/*.html.twig",
    "./assets/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      fontFamily: {
        body: ['Sora']
      },
      colors: {
        green: {
          '900': '#1f3819',
          '800': '#3d5f2f',
          '600': '#6db356',
          '500': '#7fcd65',
          '400': '#82d778',
          '300': '#bcffb2',
          '100': '#efffe6',
        },
      },
      boxShadow: theme => ({
        outline: '0 0 0 2px ' + theme('colors.green.500'),
      }),
      fill: theme => theme('colors'),
    },
  },
  variants: {
    fill: ['responsive', 'hover', 'focus', 'group-hover'],
    textColor: ['responsive', 'hover', 'focus', 'group-hover'],
    zIndex: ['responsive', 'focus'],
  },
  plugins: [],
}

