#  Online Banking Platform

![Silver Bank Logo](path/to/logo.png)

Silver Bank is a modern, user-friendly online banking platform that allows users to manage their finances, invest in cryptocurrencies, and track their investments all in one place.

## Features

- **Secure Dashboard**: View account balance and recent transactions at a glance.
- **Money Transfers**: Easily transfer money between accounts.
- **Multi-Currency Support**: Switch between different currencies seamlessly.
- **Cryptocurrency Integration**: Buy, sell, and manage various cryptocurrencies.
- **Investment Tracking**: Monitor and manage your investment portfolio.
- **Responsive Design**: Fully functional on both desktop and mobile devices.

## Getting Started

To get a local copy up and running, follow these simple steps.

### Prerequisites

- PHP >= 8.1
- Composer
- Node.js and npm
- MySQL or PostgreSQL

### Installation

1. Clone the repo
   ```sh
   git clone https://github.com/your_username/silver-bank.git

## Installation

To set up the project locally, follow these steps:

1. **Install PHP dependencies:**
    ```sh
    composer install
    ```

2. **Install NPM packages:**
    ```sh
    npm install
    ```

3. **Create a copy of the `.env.example` file and rename it to `.env`:**
    ```sh
    cp .env.example .env
    ```

4. **Generate an application key:**
    ```sh
    php artisan key:generate
    ```

5. **Configure your database in the `.env` file:**

    ```ini
    DB_CONNECTION=sqlite
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=database.sqlite
    DB_USERNAME=root
    DB_PASSWORD=
    ```

6. **Run database migrations:**
    ```sh
    php artisan migrate
    ```

7. **Seed the database with initial data (if available):**
    ```sh
    php artisan db:seed
    ```

8. **Compile assets:**
    ```sh
    npm run dev
    ```

9. **Start the development server:**
    ```sh
    php artisan serve
    ```

You can now visit [http://localhost:8000](http://localhost:8000) in your browser to see the application.

## Usage

- Register for a new account or log in if you already have one.
- Explore the dashboard to view your account summary and recent transactions.
- Use the "Transfer Money" feature to send funds to other accounts.
- Switch between currencies using the "Switch Currency" option.
- Navigate to the "Cryptocurrencies" page to buy or sell digital assets.
- Check your "Investments" page to track and manage your portfolio.

## Running Tests

To run the automated tests for this system:

```sh
php artisan test
