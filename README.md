<!-- ABOUT THE PROJECT -->
## About The Project

This project is an API designed to manage a library system efficiently. It provides a comprehensive set of functionalities including CRUD operations for books, registration and authorization services, and the ability to export the library's book database to a CSV file.

<!-- GETTING STARTED -->
## Getting Started

### Prerequisites

Make sure that you installed all components:

* Composer
* Symfony CLI
* PostgreSQL
* PHP >= 8.1
* Node.js

### Installation

1. Clone the repo
   ```sh
   git clone https://github.com/svet-vrs/symfony6-library-api.git
   ```
2. Install composer packages
   ```sh
   composer install
   ```
3. Install NPM packages
   ```sh
   npm install
   ```
4. Set Database Configuration in env `.env` file
   ```
   DATABASE_URL = 'ENTER YOUR CREADENTIALS TO POSTGRESQL';
   ```
5. Create database
    ```sh
   php bin/console doctrine:database:create
   ```
6. Apply migrations to new database
   ```sh
   php bin/console doctrine:migrations:migrate
   ```
7. Load fixtures to new database (Optionaly)
   ```sh
   php bin/console doctrine:migrations:migrate
   ```
8. Run the Application
   ```sh
   symfony server:start
   ```

<p align="right">(<a href="#readme-top">back to top</a>)</p>
