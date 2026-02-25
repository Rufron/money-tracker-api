# 💰 Money Tracker API - Backend

A robust Laravel-based REST API for personal finance tracking. This backend powers the Money Tracker frontend application, handling user management, wallet operations, and transaction processing with automatic balance calculations.

## 📁 Project Structure

```
money-tracker-api/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── UserController.php      # User CRUD operations
│   │   │   ├── WalletController.php    # Wallet management
│   │   │   └── TransactionController.php # Transaction handling with balance updates
│   │   └── Requests/
│   │       ├── StoreUserRequest.php    # User validation rules
│   │       ├── StoreWalletRequest.php  # Wallet validation rules
│   │       └── StoreTransactionRequest.php # Transaction validation
│   ├── Models/
│   │   ├── User.php      # User model with wallets relationship
│   │   ├── Wallet.php    # Wallet model with user and transactions
│   │   └── Transaction.php # Transaction model with wallet relationship
│
├── database/
│   ├── migrations/       # Table structures
│   ├── seeders/         # Test data
│   └── database.sqlite  # SQLite database file
│
├── routes/
│   └── api.php          # API route definitions
│
├── Dockerfile           # Docker configuration for Render deployment
├── docker-entrypoint.sh # Container startup script
├── render.yaml          # Render deployment configuration
└── README.md            # Project documentation
```

## ✨ Features

### Core Functionality
- **User Management** - Create and retrieve users
- **Wallet Management** - Create wallets linked to users
- **Transaction Processing** - Add income/expense transactions
- **Automatic Balance Updates** - Wallet balances update automatically with each transaction
- **Data Aggregation** - Calculate total balances across all wallets
- **Relationships** - Full Eloquent relationships between users, wallets, and transactions

### Technical Highlights
- **RESTful Design** - Clean, predictable API endpoints
- **Database Transactions** - Ensures data consistency during operations
- **Validation** - Request validation for all endpoints
- **Error Handling** - Proper HTTP status codes and error messages
- **SQLite Database** - Simple file-based database for easy deployment
- **Docker Support** - Containerized for easy deployment

## 🚀 Live API

- **Base URL**: [https://money-tracker-api-uesx.onrender.com/api](https://money-tracker-api-uesx.onrender.com/api)
- **Test Endpoint**: [https://money-tracker-api-uesx.onrender.com/api/users](https://money-tracker-api-uesx.onrender.com/api/users)

## 🛠️ Technologies Used

- **Laravel 10.x** - PHP framework
- **PHP 8.2** - Programming language
- **SQLite** - Database
- **Docker** - Containerization
- **Render** - Cloud deployment platform
- **Composer** - Dependency management

## 📋 API Endpoints

### Users

| Method | Endpoint | Description | Response |
|--------|----------|-------------|----------|
| GET | `/api/users` | Get all users | Array of users with wallets |
| GET | `/api/users/{id}` | Get specific user | Single user with wallets |
| POST | `/api/users` | Create new user | Created user object |

**POST `/api/users` Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com"
}
```

### Wallets

| Method | Endpoint | Description | Response |
|--------|----------|-------------|----------|
| GET | `/api/wallets` | Get all wallets | Array of all wallets |
| GET | `/api/wallets/{id}` | Get specific wallet | Wallet with transactions |
| POST | `/api/wallets` | Create new wallet | Created wallet object |
| DELETE | `/api/wallets/{id}` | Delete wallet | Success confirmation |
| GET | `/api/users/{user}/wallets` | Get user's wallets | Array of user's wallets |

**POST `/api/wallets` Request Body:**
```json
{
  "user_id": 1,
  "name": "Savings",
  "balance": 1000,
  "description": "Long-term savings"
}
```

### Transactions

| Method | Endpoint | Description | Response |
|--------|----------|-------------|----------|
| GET | `/api/transactions` | Get all transactions | Array of all transactions |
| GET | `/api/transactions/{id}` | Get specific transaction | Single transaction |
| POST | `/api/transactions` | Create new transaction | Created transaction with updated balance |
| DELETE | `/api/transactions/{id}` | Delete transaction | Success confirmation (reverses balance) |
| GET | `/api/wallets/{wallet}/transactions` | Get wallet transactions | Array of wallet's transactions |

**POST `/api/transactions` Request Body:**
```json
{
  "wallet_id": 1,
  "amount": 50.00,
  "type": "expense",
  "description": "Groceries"
}
```

> **Note:** The `type` field accepts `"income"` or `"expense"`. The amount should always be positive; the backend handles the sign automatically.

## 🔧 Local Setup

### Prerequisites
- PHP 8.2 or higher
- Composer
- SQLite (included with PHP)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/money-tracker-api.git
   cd money-tracker-api
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**
   
   In `.env`, set:
   ```
   DB_CONNECTION=sqlite
   # Optional: specify database path
   # DB_DATABASE=/absolute/path/to/database.sqlite
   ```

5. **Create SQLite database**
   ```bash
   touch database/database.sqlite
   chmod 666 database/database.sqlite
   ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

8. **Test the API**
   ```bash
   curl http://127.0.0.1:8000/api/users
   ```

## 🐳 Docker Deployment

### Local Docker Build

```bash
# Build the Docker image
docker build -t money-tracker-api .

# Run the container
docker run -p 8000:80 money-tracker-api
```

### Render Deployment

This project includes configuration for easy deployment on Render:

1. **Push code to GitHub**
2. **Connect repository to Render**
3. **Use the included `render.yaml` blueprint** or create a new Web Service with:
   - Runtime: Docker
   - Environment variables as needed

### Docker Files Explained

- **Dockerfile**: Multi-stage build with PHP 8.2 Apache, installs dependencies, configures Laravel
- **docker-entrypoint.sh**: Handles database setup, migrations, and permissions on container start
- **render.yaml**: Render blueprint configuration for automated deployment

## 🗄️ Database Schema

### Users Table
| Column | Type | Description |
|--------|------|-------------|
| id | integer | Primary key |
| name | string | User's full name |
| email | string | Unique email address |
| created_at | timestamp | Record creation time |
| updated_at | timestamp | Record update time |

### Wallets Table
| Column | Type | Description |
|--------|------|-------------|
| id | integer | Primary key |
| user_id | integer | Foreign key to users |
| name | string | Wallet name |
| description | text | Optional description |
| balance | decimal | Current wallet balance |
| created_at | timestamp | Record creation time |
| updated_at | timestamp | Record update time |

### Transactions Table
| Column | Type | Description |
|--------|------|-------------|
| id | integer | Primary key |
| wallet_id | integer | Foreign key to wallets |
| amount | decimal | Transaction amount (positive) |
| type | string | 'income' or 'expense' |
| description | string | Transaction description |
| created_at | timestamp | Record creation time |
| updated_at | timestamp | Record update time |

## 🔒 Data Integrity

The API uses database transactions to ensure consistency:

```php
// Example from TransactionController
return DB::transaction(function () use ($request) {
    $transaction = Transaction::create($request->validated());
    $wallet = Wallet::findOrFail($request->wallet_id);
    
    if ($request->type === 'income') {
        $wallet->balance += $request->amount;
    } else {
        $wallet->balance -= $request->amount;
    }
    
    $wallet->save();
    return response()->json(['success' => true, 'data' => $transaction]);
});
```

## 📊 Example API Responses

### GET /api/users/1
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Jane Doe",
    "email": "jane@example.com",
    "created_at": "2026-02-24T13:08:20.000000Z",
    "total_balance": 5728,
    "wallets": [
      {
        "id": 1,
        "name": "Personal",
        "balance": 4628,
        "description": "Daily expenses"
      },
      {
        "id": 2,
        "name": "Savings",
        "balance": 1100,
        "description": "Long term savings"
      }
    ]
  }
}
```

## 🚦 Error Handling

The API returns appropriate HTTP status codes:

- **200 OK** - Successful GET request
- **201 Created** - Successful POST request
- **404 Not Found** - Resource doesn't exist
- **422 Unprocessable Entity** - Validation errors
- **500 Internal Server Error** - Server-side error

Error response format:
```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field": ["Validation error message"]
  }
}
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

## 🙏 Acknowledgments

- Laravel community for the amazing framework
- Render for simple cloud deployment
- All contributors and testers

---

**Built with ❤️ using Laravel and SQLite**