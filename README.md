# WebShop
A functional online store with all operations (Entry, exit, registrations, and deletions).

Demonstration video of the project:
https://github.com/user-attachments/assets/907c1cab-0c15-49cc-b728-85611c6c667b

## Environment Setup
This program is built following a microservices-oriented approach, so some configurations need to be made in the environment to use the tool.

### Required Programs
1. The environment needs to have PHP installed on the machine and properly set in the system's environment variables to be used in the command line. It is recommended to install XAMPP (link: https://www.apachefriends.org/pt_br/index.html).
2. Composer needs to be installed on the machine. Composer is PHP's package manager, but it does not come by default with PHP installation. Download tutorial (https://getcomposer.org/download/).
3. Node.js needs to be installed on the machine, along with npm. npm is Node.js's package manager, and it is included by default when installing Node.js. Download link (https://nodejs.org/pt/download/package-manager).
4. PGAdmin: The choice of the database is up to the user, but the program was built using PostgreSQL, which is recommended for those using the program. PGAdmin is the interface responsible for accessing the PostgreSQL database. Download link (https://www.pgadmin.org/download/).

### Database Configuration
1. Open PGAdmin and create a database.
2. Copy the `.env.example` file, rename the copy by removing ".example" from the name.
3. Inside the `.env` file, in the database section, enter the database access details according to the database created in PGAdmin:
   ```
   # Similar to the following:
   DB_CONNECTION=pgsql
   DB_HOST=localhost
   DB_PORT=5432
   DB_DATABASE="DATABASE_NAME"
   DB_USERNAME=postgres
   DB_PASSWORD=123
   ```
4. After that, open CMD in the "server" folder and run the following command:
   ```
   php artisan migrate
   ```
   !! If the error "could not find driver (Connection: pgsql, SQL: select * from information_schema.tables where table_catalog = agenda and table_schema = public and table_name = migrations and table_type = 'BASE TABLE')" occurs, go to the `php.ini` file (located where PHP is installed). In the file, look for `;extension=pdo_pgsql`, remove the semicolon (`;`) at the beginning of the line, and run the `php artisan migrate` command again.

5. The project includes a "PopulaBanco.sql" file, which contains some insert commands to populate the database.

The program was built using PostgreSQL as the DBMS, but it is possible to use another database.

### Server-side Configuration
1. Open CMD in the root folder of the project and run the following command:
   ```
   composer update
   ```
2. After installing all components, run the following command:
   ```
   php artisan key:generate
   ```
3. After generating the key, run the following command:
   ```
   php artisan serve
   ```
4. It is important to note that in the `.env` file, there is a configuration called "APP_URL," which must match the same route where the server is running. If the server starts at "127.0.0.1," try accessing the system via "localhost" in the browser.

### Client-side Configuration
In the "client" folder, open CMD and run the following command:
   ```
   npm install
   ```
In the directory `resources\js\Pages\Assets\`, there is a file named `apiconfig.js`. This file centralizes all routes used in the program. If the program is started on a different route than `http://localhost:8000/`, open this file and change the `BASE_URL` variable accordingly:
   ```
   export const BASE_URL = 'http://localhost:8000'
   ```
After all installations, run the following command:
   ```
   npm run build
   ```

## Stacks
[![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)]()
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)]()
[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)]()
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-316192?style=for-the-badge&logo=postgresql&logoColor=white)]()
[![Vue.js](https://img.shields.io/badge/Vue.js-35495E?style=for-the-badge&logo=vue.js&logoColor=4FC08D)]()
<center>
<img align="right" src="http://ForTheBadge.com/images/badges/built-with-love.svg" alt="visitor_badge">
</center>

