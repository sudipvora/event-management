🎉 Event Management System
A Laravel-based web application designed to streamline the process of planning, organizing, and managing events efficiently.

🚀 Features
Event Scheduling: Create, update, and manage events with ease.

User Authentication: Secure login and registration functionalities.

Responsive Design: Optimized for mobile, tablet, and desktop views.

API Integration: RESTful APIs for seamless frontend-backend communication.

Postman Collections: Predefined API requests for testing and development.

🛠️ Technologies Used
Backend: Laravel (PHP), MySQL

Frontend: Blade Templates, Vite

Package Managers: Composer (PHP), npm (Node.js)

API Testing: Postman

📂 Project Structure
pgsql
Copy
Edit
event-management/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── .env.example
├── composer.json
├── package.json
├── phpunit.xml
├── vite.config.js
├── postman-api-collection.json
├── postman_environment.json
└── README.md
⚙️ Installation & Setup
Prerequisites
PHP >= 8.0

Composer

Node.js & npm

MySQL

Steps
Clone the Repository

bash
Copy
Edit
git clone https://github.com/sudipvora/event-management.git
cd event-management
Install Backend Dependencies

bash
Copy
Edit
composer install
Set Up Environment Variables

bash
Copy
Edit
cp .env.example .env
php artisan key:generate
Configure the .env file with your database credentials and other necessary configurations.

Run Migrations

bash
Copy
Edit
php artisan migrate
Install Frontend Dependencies

bash
Copy
Edit
npm install
Start the Development Servers

Backend:

bash
Copy
Edit
php artisan serve
Frontend:

bash
Copy
Edit
npm run dev
📬 API Testing with Postman
The repository includes Postman collections for API testing:

Collection: postman-api-collection.json

Environment: postman_environment.json

Import these files into Postman to test the API endpoints.

🧪 Running Tests
Execute the following command to run the test suite:

bash
Copy
Edit
php artisan test
🤝 Contributing
Contributions are welcome! Please fork the repository and submit a pull request for any enhancements or bug fixes.

📄 License
This project is open-source and available under the MIT License.