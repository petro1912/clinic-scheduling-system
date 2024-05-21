# Clinic registration and online scheduling system

This is the source code of clinical registration system with scheduling. It is built by Laravel and Vue.js with Vuexy theme.

## Recommended IDE Setup

[VSCode](https://code.visualstudio.com/) + [Volar](https://marketplace.visualstudio.com/items?itemName=johnsoncodehk.volar) (and disable Vetur).

## Key Features

- Check services and availability
- Schedule to clinic
- Payment with Transbank
- Upload basic health documentation
- Mail with Zoom Meeting link

## Scheduling Process
- Step 1: The patient must see the availability 15 days ahead of the current date they enter the system, for example if today is 7 you should see the hours available from the 15th onwards. 
- Step 2: once you take the time, you must complete your personal information and health data (there are two patient tables and base_history, the idea is that you complete the information and your account is created) 
- Step 3: you must attach documents to complete the reservation, the files are PDF or JPG results, they will be stored in a folder. 
- If you do not have tests, you can mark it as having no medical order 
- Step 4: then they go on to pay by transbank, when they finish the payment they should be redirected to a view of scheduled appointments. 
- Step 5: receive notification to the account creation email with a link to create your password and also another reservation with payment. 


## Test Hosting
[TeleMedicina](https://telemedicina.ssgastro.cl/)

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
