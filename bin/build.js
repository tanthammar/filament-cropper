const esbuild = require('esbuild')
const shouldWatch = process.argv.includes('--watch')
const stylePlugin = require('esbuild-style-plugin')
const Path = require('path')
const fs = require("fs")
let path = null

const envMode = {
    'process.env.NODE_ENV': shouldWatch
        ? `'production'`
        : `'development'`,
}

const esm = {
    define: envMode,
    outdir: 'resources/dist', //removes file name extension
    bundle: true,
    mainFields: ['module', 'main'],
    platform: 'neutral', //output format is set to esm, which uses the export syntax introduced with ECMAScript 2015 (i.e. ES6
    //watch: shouldWatch, //undefined option in esbuild ^0.17.17, but handled by npm run --watch
    sourcemap: shouldWatch,
    treeShaking: true, //removes unused code
    //target: ['es2020'], //or es2015? //DAN HARRIN, filament should support?
    minify: true, //includes whitespace, identifiers and syntax
    loader: { // convert image urls to embedded images, no need to publish css images
        '.jpg': 'dataurl',
        '.png': 'dataurl',
        '.svg': 'text',
    },
    plugins: [
        stylePlugin({
            postcss: {
                plugins: [
                    require('postcss-import'),
                    require('tailwindcss/nesting'),
                    require('tailwindcss'),
                    require('autoprefixer')
                ],
            },
        }),
    ],
}

//https://github.com/g45t345rt/esbuild-style-plugin
//https://stackoverflow.com/questions/70716940/using-tailwind-css-with-esbuild-the-process
const css = {
    define: envMode,
    outdir: 'resources/dist',
    bundle: true,
    minify: true,
    loader: { // convert image urls to embedded images, no need to publish css images
        '.jpg': 'dataurl',
        '.png': 'dataurl',
        '.svg': 'text',
    },
    plugins: [
        stylePlugin({
            postcss: {
                plugins: [
                    require('postcss-import'),
                    require('tailwindcss/nesting'),
                    require('tailwindcss'),
                    require('autoprefixer')
                ],
            },
        }),
    ],
}

function compile(options) {
    esbuild.build(options).catch(() => process.exit(1))
}

//js
if (fs.existsSync(path = "resources/js/")) {
    fs.readdirSync(path)
        .forEach((filename) => {
            esm.entryPoints = [path + filename]
            compile(esm)
        })
}

//css
if (fs.existsSync(path = "resources/css/")) {
    fs.readdirSync(path)
        .forEach((filename) => {
            css.entryPoints = [path + filename]
            compile(css)
        })
}
