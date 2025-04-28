# TODO LIST

simple implementation with laravel 12, vue, tailwind

## Installation

clone, change file

```bash
/backend/.env.example
to
/backend/.env
```

build docker with

```bash
docker-compose up -d
```

next run

```bash
docker-compose exec app php artisan migrate --seed
docker-compose exec app php artisan scribe:generate
```

for run frontend:

```bash
cd frontend
npm run dev
```

app should be available
```bash
http://localhost:5173
```

to view phpmyadmin
```bash
http://localhost:8081/
```

to view apidoc
```bash
http://localhost/docs
```

to run tests
```bash
docker-compose exec app php artisan test
```