# Proyecto Full Circle 2.0.0

## Instrucciones de Instalación

### Clonar el repositorio
```bash
git clone https://github.com/lordnitorrinco/fullcircle
cd fullcircle
```
Todos los contenedores se pueden ejecutar en paralelo, usan puertos distintos para bbdd:
```plaintext
- laravel   =   localhost:3306
- wordpress =   localhost:3307
- mysql     =   localhost:3308
```
y para aplicaciones web:
```plaintext
- laravel   =   localhost:80
- wordpress =   localhost:8000
```
### PHP
1. Navegar al directorio de PHP:
    ```bash
    cd php
    ```
2. Levantar los contenedores de Docker:
    ```bash
    docker compose up -d
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
    - Este método tiene contras asociados a trabajar con un archivo enorme y los tiempos para que Copilot lo modifique crecen, pero tiene el pro de "forzar" a Copilot a mantener una visión de conjunto del código, haciendo mucho más difícil que aparezcan incongruencias entre archivos y permitiendo maniobras como un cambio radical de la arquitectura de todo el proyecto con un solo comando. Como casi todo en este mundillo hay que usar este sistema con cuidado y no siempre es la mejor opción, pero ahí queda, como ejemplo de que se puede hacer.

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
    CALL GetAvailableRooms(CURDATE());
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
4. Esperar a que termine la configuración de la base de datos en Docker.
5. Ejecutar migraciones:
    ```bash
    ./vendor/bin/sail artisan migrate
    ```
6. Ejecutar seeders:
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
4. Acceder a http://localhost:8000, configurar WordPress y activar el plugin.

### Tkinter
1. Navegar al directorio de Tkinter:
    ```bash
    cd tkinter
    ```
2. Levantar el entorno virtual:
    ```bash
    python3 -m venv venv
    source venv/bin/activate
    ```
3. Instalar dependencias:
    ```bash
    pip install -r requirements.txt
    ```
4. Iniciar app:
    ```bash
    python src/main.py
    ```
- Esta aplicación permite monitorizar los contenedores de todas las demás y probarlas si los contenedores están levantados.
