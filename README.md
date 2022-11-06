<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

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
To run the container enter this command:
```
./vendor/laravel/sail/bin/sail up -d
```

If app runs for the first time run these commands to create .env variables, generate APP_KEY, migrate database and seed default values:
```
cp .env.example .env
```

```
./vendor/laravel/sail/bin/sail artisan key:generate
./vendor/laravel/sail/bin/sail artisan migrate:fresh --seed
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
