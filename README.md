# DB Migrator for Bulk Data Insertion (SQL & XLS)

## Overview

This Laravel application is built using the VILT stack (Vue.js, Inertia.js, Laravel, Tailwind CSS). It enables the insertion of large amounts of data into a database via SQL and XLS file uploads. The application supports seamless data handling for bulk imports, ensuring data integrity and efficient processing.

## Features

-   Bulk data import from SQL files
-   Bulk data import from XLS files (Excel)
-   VILT stack for frontend and backend integration
-   User-friendly file upload interface with Vue.js
-   Efficient handling of large datasets
-   Data validation for file format and content
-   Supports customizable database structures

## Prerequisites

Make sure you have the following installed:

-   PHP 8.1 or higher
-   Composer
-   Node.js and npm
-   MySQL or compatible database
-   Laravel 11+
-   Inertia.js
-   Vue.js 3
-   Tailwind CSS

## Installation

### Step 1: Clone the repository

\`\`\`bash
git clone https://github.com/TharinduWijayarathna/DB-Migrator.git
cd your-repo-name
\`\`\`

### Step 2: Install PHP dependencies

\`\`\`bash
composer install
\`\`\`

### Step 3: Install Node.js dependencies

\`\`\`bash
npm install
npm run dev
\`\`\`

### Step 4: Configure environment variables

Copy the `.env.example` file to `.env` and update the necessary database credentials and other environment settings.

\`\`\`bash
cp .env.example .env
\`\`\`

Set up your database in the `.env` file:

\`\`\`bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
\`\`\`

### Step 5: Generate the application key

\`\`\`bash
php artisan key:generate
\`\`\`

### Step 6: Run database migrations

\`\`\`bash
php artisan migrate
\`\`\`

## Usage

### Upload SQL File

1. Go to the **Bulk Upload** section of the application.
2. Select an SQL file.
3. The application will parse and insert the data into the database.

### Upload XLS File

1. Go to the **Bulk Upload** section of the application.
2. Select an XLS file.
3. The application will process the Excel file and insert the data into the database.

### Data Validation

The system validates the uploaded files based on format and content. If the file is invalid, the user will be prompted with appropriate error messages.

## Technologies Used

-   **Backend**: Laravel 10+
-   **Frontend**: Vue.js 3, Inertia.js
-   **Styling**: Tailwind CSS
-   **Database**: MySQL
-   **File Parsing**:
    -   SQL: Native SQL support via Laravel migrations
    -   XLS: PHPSpreadsheet library

## Running Tests

To run tests, use the following command:

\`\`\`bash
php artisan test
\`\`\`

## License

This project is licensed under the MIT License. See the `LICENSE` file for details.
