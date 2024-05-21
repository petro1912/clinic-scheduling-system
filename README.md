# Clinic registration and online scheduling system

This is the source code of clinical registration system with scheduling. It is built by Laravel and Vue.js with Vuexy theme.

## Recommended IDE Setup

[VSCode](https://code.visualstudio.com/) + [Volar](https://marketplace.visualstudio.com/items?itemName=johnsoncodehk.volar) (and disable Vetur).

## Key Features

- Check services and availability
- Schedule to clinic
- Payment with Transbank
- Upload basic health documentation
- Mail with Zoom Meeting link- 

## Type Support for `.vue` Imports in TS

Since TypeScript cannot handle type information for `.vue` imports, they are shimmed to be a generic Vue component type by default. In most cases this is fine if you don't really care about component prop types outside of templates.

However, if you wish to get actual prop types in `.vue` imports (for example to get props validation when using manual `h(...)` calls), you can run `Volar: Switch TS Plugin on/off` from VSCode command palette.

## Customize configuration

See [Vite Configuration Reference](https://vitejs.dev/config/).

## Project Setup

Please install mysql server and create database.(Please refer .env file)

Run Laravel local server with following commands.
```sh
composer install
php artisan generate
php artisan migrate
php artisan serve
```

```sh
npm install
```

### Compile and Hot-Reload for Development

```sh
npm run dev
```

### Type-Check, Compile and Minify for Production

```sh
npm run build
```
