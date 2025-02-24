# Proyecto Full Circle 1.1.1

## Instrucciones de Instalación

### Clonar el repositorio
```bash
git clone https://github.com/lordnitorrinco/fullcircle
cd fullcircle
```

### PHP
1. Navegar al directorio de PHP:
    ```bash
    cd php
    ```
2. Instalar dependencias:
    ```bash
    composer install
    ```
3. Acceder al contenedor de PHP:
    ```bash
    docker exec -it php_container sh
    ```
4. Ejecutar fullcircle -h (o --help) para ver las opciones de ejecución
    ```bash
    fullcircle -h
    ```
    o ejecutar una de las opciones directamente
    ```bash
    fullcircle all
    fullcircle question1
    fullcircle question2
    fullcircle question3
    ```
    para todos los comandos puedes introducir los datos manulamente con --manual (por defecto a --random)

5. He dejado el archivo create-project.php para mostrar lo que se puede afinar un output de GitHub Copilot. Para probarlo solo hay que copiarlo a un directorio vacío y ejecutarlo, creará una copia de todo lo que hay ahora en fullcircle/php/, directorios y archivos.

### MySQL
1. Navegar al directorio de MySQL:
    ```bash
    cd mysql
    ```
2. Levantar los contenedores de Docker:
    ```bash
    docker compose up -d
    ```
3. Esperar a que se ejecuten los scripts SQL (verificar en los logs de Docker Desktop).
4. Acceder al contenedor de MySQL:
    ```bash
    docker exec -it mysql_container mysql -uroot -p
    ```
    - Contraseña: `root`

5. **Pregunta 1:**
    ```sql
    USE rooms_db;
    SHOW TABLES;
    ```
6. **Pregunta 2:**
    ```sql
    USE rooms_db;
    CALL GetAvailableRooms('2025-02-21');
    ```
7. **Pregunta 3:**
    ```sql
    USE products_db;
    CALL GetTop5BestSellingProducts();
    CALL GetTotalRevenueLast30Days();
    ```

### Laravel
1. Navegar al directorio de Laravel:
    ```bash
    cd laravel
    ```
2. Instalar dependencias:
    ```bash
    composer install
    ```
3. Levantar los contenedores de Sail:
    ```bash
    ./vendor/bin/sail up -d
    ```
4. Ejecutar migraciones:
    ```bash
    ./vendor/bin/sail artisan migrate
    ```
5. Ejecutar seeders:
    ```bash
    ./vendor/bin/sail artisan db:seed
    ```
6. Usar la colección `/laravel/fullcircle.postman_collection.json` para testear los endpoints.

### WordPress
1. Navegar al directorio de WordPress:
    ```bash
    cd wordpress
    ```
2. Levantar los contenedores de Docker:
    ```bash
    docker compose up -d
    ```
3. Esperar a que termine la configuración de la base de datos en Docker.
4. Acceder a [http://localhost:8000/](http://localhost:8000/), configurar WordPress y activar el plugin.

