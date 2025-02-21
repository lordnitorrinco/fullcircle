# Proyecto Full Circle

## Instrucciones de Instalación

### Clonar el repositorio
```bash
git clone https://github.com/lordnitorrinco/fullcircle
cd fullcircle
```

### PHP
1. **Pregunta 1:**
    ```bash
    php php/question_1.php
    ```
2. **Pregunta 2:**
    ```bash
    php php/question_2.php
    ```
3. **Pregunta 3:**
    ```bash
    cd php/question_3
    composer install
    php index.php
    ```

### MySQL
1. Navegar al directorio de MySQL:
    ```bash
    cd mysql
    ```
2. Bajar los contenedores de Docker si están corriendo:
    ```bash
    docker compose down
    ```
3. Levantar los contenedores de Docker:
    ```bash
    docker compose up -d
    ```
4. Esperar a que se ejecuten los scripts SQL (verificar en los logs de Docker Desktop).
5. Acceder al contenedor de MySQL:
    ```bash
    docker exec -it mysql_container mysql -uroot -p
    ```
    - Contraseña: `root`

6. **Pregunta 1:**
    ```sql
    USE rooms_db;
    SHOW TABLES;
    ```
7. **Pregunta 2:**
    ```sql
    USE rooms_db;
    CALL GetAvailableRooms('2025-02-21');
    ```
8. **Pregunta 3:**
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
3. Bajar los contenedores de Sail si están corriendo:
    ```bash
    ./vendor/bin/sail down
    ```
4. Levantar los contenedores de Sail:
    ```bash
    ./vendor/bin/sail up -d
    ```
5. Ejecutar migraciones:
    ```bash
    ./vendor/bin/sail artisan migrate
    ```
6. Ejecutar seeders:
    ```bash
    ./vendor/bin/sail artisan db:seed
    ```
7. Usar la colección `/laravel/fullcircle.postman_collection.json` para testear los endpoints.

### WordPress
1. Navegar al directorio de WordPress:
    ```bash
    cd wordpress
    ```
2. Bajar los contenedores de Docker si están corriendo:
    ```bash
    docker compose down
    ```
3. Levantar los contenedores de Docker:
    ```bash
    docker compose up -d
    ```
4. Esperar a que termine la configuración de la base de datos en Docker.
5. Acceder a [http://localhost:8000/](http://localhost:8000/), configurar WordPress y activar el plugin.

