
### Codelex Final Project

This is a final project for Codelex Bootcamp by **Dins Vasijevs**.

The application replicates a digital banking platform with built-in cryptocurrency trading capabilities. It provides secure registration and authentication for both individual and corporate users. Users can create and manage cash accounts, each denominated in their chosen currency. The system also supports connections between users, facilitating smooth money transfers. When a transfer requires currency conversion, the exchange rates are automatically retrieved from the CurrencyBaconApi.

## Requirements
- **PHP**: 8.2+
- **Composer**: 2.7.8+
- **Node.js**: 22.4.1+
- **ext-http**: Required

## Setup Instructions

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/your-repo/dinsvasijevs/F01082024FPE.git

Set Up Environment File:

Rename .env.example to .env and update it with your configurations.
Set PostgreSQL for Docker or SQLite for local development.
Install PHP Dependencies:

bash
Kopēt kodu
composer install
Generate Application Key:

bash
Kopēt kodu
php artisan key:generate
Run Migrations:

bash
Kopēt kodu
php artisan migrate
Install Frontend Dependencies:

bash
Kopēt kodu
npm install
Build Frontend Assets:

bash
Kopēt kodu
npm run build
Serve the Application:

bash
Kopēt kodu
php artisan serve
Open your browser and navigate to http://localhost:8000.

Commands
Essential Commands:

php artisan app:fetch-fiat: Retrieve exchange rates from the CurrencyBaconApi.
php artisan app:fetch-crypto: Fetch cryptocurrency data from the CoinMarketCapApi.
Optional Commands:

php artisan db:seed: Populate the database with demo data, including two test users:
Email: amelia@example.com
Email: oscar@example.com
Password: password
php artisan app:fetch-crypto-info: Retrieve cryptocurrency logos from CoinMarketCap.
Environment Variables
CoinMarketCap API Key: Ensure that your CoinMarketCap API key is set in the COINMC environment variable.
Automated testing is implemented using Pest. To run the test suite:

bash
Kopēt kodu
./vendor/bin/pest
Contact
If you have any questions or feedback, feel free to reach out:

Name: Dins Vasijevs
Email: dianex13@gmail.com
markdown
Kopēt kodu









