# Business chat made with Laravel & Vue

![Screenshot](https://imgur.com/ZApjAHN.png)

### Installing Composer Dependencies

This command uses a small Docker container containing PHP and Composer to install the application's dependencies:
```
docker run --rm \
-u "$(id -u):$(id -g)" \
-v $(pwd):/opt \
-w /opt \
laravelsail/php80-composer:latest \
composer install --ignore-platform-reqs
```

Npm dependencies:

```
npm install
npm install -g laravel-echo-server
```

### Running App
If app runs for the first time run these commands to create .env variables, generate APP_KEY, migrate database and seed default values:
```
cp .env.example .env
```

```
./vendor/laravel/sail/bin/sail up -d
./vendor/laravel/sail/bin/sail artisan key:generate
./vendor/laravel/sail/bin/sail artisan migrate:fresh --seed
```

Then next time you can just run the container with these commands:
```
./vendor/laravel/sail/bin/sail up -d
```

The following commands run listening services (npm for js/css compiling, queue for laravel/redis communication, echo for websocket server):
```
npm run watch
./vendor/laravel/sail/bin/sail artisan queue:listen
laravel-echo-server start
```

### Testing app

```
./vendor/laravel/sail/bin/sail artisan test
```

### Folder structure
```
/
├─ app/                 #Serverside app logic
│  ├─ Events/           #Websocket events
│  ├─ Http/
│  │  ├─ Controllers/   #Controllers (DB operations & event calls)
│  │  └─ Middleware/    #User access checks (for routing & etc.)
│  └─ Models/           #DB models
├─ database/        
│  ├─ factories/        #Fake DB row filling values (fake users etc...)
│  ├─ migrations/       #DB structure migrations 
│  └─ seeders/          #Premade DB data seeding
├─ resources/           #Compilable clientside script & styles (VueJS & Vuetify app)
│  └─ js/
├─ routes/              #App routes
│  └─ api.php
├─ tests/               #App tests
└─ .env                 #Environment config (DB connection, Redis connection)
```

## About Laravel

Laravel is a web application framework with expressive, elegant syntax.
Laravel is accessible, powerful, and provides tools required for large, robust applications.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
