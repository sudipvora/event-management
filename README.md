
# 🎉 **Event Management System**

A **Laravel-based web application** designed to streamline the process of planning, organizing, and managing events efficiently.

---

## 🚀 Features

- **Event Scheduling**: Create, update, and manage events with ease.
- **API Integration**: RESTful APIs for seamless frontend-backend communication.
- **User Authentication**: Secure login and registration functionalities.
- **Postman Collections**: Predefined API requests for testing and development.

---

## 🛠️ Technologies Used

| Category         | Technology                     |
|------------------|--------------------------------|
| Backend          | Laravel (PHP), MySQL           |
| Frontend         | Blade Templates                |
| Package Managers | Composer (PHP)                 |
| API Testing      | Postman                        |

---

## 📂 Project Structure

```
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
```

---

## ⚙️ Installation & Setup

### ✅ Prerequisites

- PHP >= 8.0  
- Composer  
- MySQL  

### 📦 Steps

1. **Clone the Repository**

    ```bash
    git clone https://github.com/sudipvora/event-management.git
    cd event-management
    ```

2. **Install Backend Dependencies**

    ```bash
    composer install
    ```

3. **Set Up Environment Variables**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    Update `.env` with your database credentials.

4. **Run Migrations**

    ```bash
    php artisan migrate
    ```

5. **Start Development Servers**

    - **Backend**:

        ```bash
        php artisan serve
        ```

---

## 📬 API Testing with Postman

The repository includes Postman collections for API testing:

- **Collection**: `postman-api-collection.json`
- **Environment**: `postman_environment.json`

Import these files into Postman to test available API endpoints.

---

## 🧪 Running Tests

Run the test suite using:

```bash
php artisan test
```

---

## 🤝 Contributing

Contributions are welcome!  
Please fork the repository and submit a pull request for enhancements or bug fixes.

---

## 📄 License

This project is open-source and available under the **MIT License**.
