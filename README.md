# Forum-Laravel-Vue
Forum desenvolvido com PHP 7, Laravel 5.5 e VueJS 2

# Instalação
1. clone repository
2. configurar arquivo .env
3. composer install
4. npm install
5. php composer artisan serve (localhost:8000)
6. npm run watch

# Armazenamento de dados
1. mysql
2. sqlite (testing)
3. redis

# Seed
1. php artisan tinker;
2. factory('App\Thread', 30)->create(); // Cria 30 threads com usurios e replies relacionadas

# Testes
1. vendor/bin/phpunit
