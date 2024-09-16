# Demo App : Crypto Watch Dog

---

This is a demo of a cryptocurrency watch-dog service.

> Made by Dávid Kolembus 2024

## Table of Contents

- [Task Overview](#task-overview)
- [Run this locally](#run-this-locally)
- [API Documentation](#api-documentation)

---

# Task Overview

### Requirements

- Laravel 10+ (Using Laravel 11 with PHP 8.3)
- PostgreSQL v13
- Uses [Coin Gecko API](https://docs.coingecko.com)
- Utilizes [PHP JWT](https://github.com/firebase/php-jwt) for simple JWT auth

### Overview

- [X] Command for updating and inserting cryptocurrency data - Run `php artisan currency:refresh`
- [X] Authentication - Uses JWT
- [X] Watch-dogs
- [X] Queued mail for Watch-dog alerts

### Limitations

- Currently watch-dogs are set to trigger by a percentage change. Implementing a trigger by absolute price change is a TODO.
- Coins that are missing some data are not indexed.
- Auth layer only offers a basic token auth - OAuth2 is a better option for sure...
- UUIDs on the coins are quite expensive to generate especially when filling the database at the start
- Did not figure out how to get `->usert` to work with UUIDs
- Needs Integration tests

--- 

# Run this locally

1. **Pull the repo**
```bash
git clone https://github.com/Dalovec/task-coins.git
```

2. **Copy the .env.example file**
```bash
cp .env.example .env
```
> Note: If you have a Coin Gecko API key, you can add it to the .env file

3. **Run composer install**
```bash
composer install
```


4. **Run docker compose**
```bash
docker compose up -d
```

5. **Seed the database from the Coin Gecko API**
```bash
docker exec -it cryptowatcher php artisan currency:refresh
```

> Note: This will take a while to run and even tho it is rate limited it can sometimes fail ???? idk

## Endpoints and Ports

- Auth on:     **http://localhost:8000/auth**
- Api on:      **http://localhost:8000/api**
- Mailtrap on: **http://localhost:8025**

---

# API Documentation

---

## `GET /api/currencies`

Gets a paginated list of cryptocurrencies

### `200` Response
```json
{
    "data": [
        {
            "id": 1,
            "name": "Bitcoin",
            "coin_id": "bitcoin",
            "image_url": "https://s3.amazonaws.com/cryptocurrency-icons/bitcoin.png",
        },
        {
            "id": 2,
            "name": "Ethereum",
            "coin_id": "ethereum",
            "image_url": "https://s3.amazonaws.com/cryptocurrency-icons/ethereum.png",
        },
    ],
    "links": {
        "first": "http://localhost:8000/api/currencies?page=1",
        "last": "http://localhost:8000/api/currencies?page=1",
        "prev": null,
        "next": null
    }
}
```

## `GET /api/currencies/{currency}`

Gets a single cryptocurrency

### `200` Response 
```json
{
    "data": {
        "id": "167eeee8-4688-4c2f-9efc-db8479aebd18",
        "coin_id": "bitcoin",
        "name": "Bitcoin",
        "symbol": "btc",
        "image": "https://coin-images.coingecko.com/coins/images/1/large/bitcoin.png?1696501400",
        "current_price": 54007.8718,
        "price_change_percentage_24h": 0.14002,
        "market_cap": 1066582586795
    }
}
```



## `POST /auth/issue-token`

Creates or finds a user and returns a JWT access token

`Request Body`
```json
{
    "email": "test@example.com",
    "password": "password" // Must be secure
}
```

### Response
```json
{
    "token": "<tokenstring>",
    "expires_at": "2024-01-01 00:00:00"
}
```

## `POST /auth/revoke-token`

Revokes a JWT access token

`Request Body`
```json
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjE1MzU0NDI0MDAwMDAwMDAwMDAwMDAwMCIsImVtYWlsIjoibXlfY2xpZW50X2lkIiwiaWF0IjoxNTQ0NTA0ODAwLCJleHAiOjE1NDQ1MDQ4MDB9.3-5-0-9-8-7-6-5-4-3-2-1-0-9-8-7-6-5-4-3-2-1-0"
}
```
`Request Headers`
```json
{
    "Authorization": "Bearer <tokenstring>"
}
```

### Response
```json
{
    "data": {
        "message": "Token revoked"
    }
}
```

## `POST /api/create-watch-dog`

Creates a watchdog for a currency

`Request Body`
```json
{
    "coin_id": "bitcoin", // Coin_id from api/currencies
    "change": 1 // Percentage change to trigger alert
}
```

`Request Headers`
```json
{
    "Authorization": "Bearer <tokenstring>"
}
```

### `200` Response
```json
{
    "message": "Watch dog created",
    "coin": "Bitcoin",
    "price": "54007.8718", // Current price
    "change": "1%" // Change in price to trigger alert
}
```

### `400` Response - If watch-dog is already set
```json
{
    "error": "Watch dog already exists"
}
```

### `404` Response - If coin_id is not found
```json
{
    "error": "Coin not found"
}
```

## `GET /api/watch-dogs` 

Gets a list of my coins I have set a watch-dog for

`Request Headers`
```json
{
    "Authorization": "Bearer <tokenstring>"
}
```

### Response
```json
{
    "data": [
        {
            "coin_id": "bitcoin",
            "name": "Bitcoin",
            "symbol": "btc"
        },
        {
            "coin_id": "ethereum",
            "name": "Ethereum",
            "symbol": "eth"
        }
    ]
}
```

## `DELETE /watch-dogs/{watch_dog}`

Deletes one of my watch-dogs

`Request Headers`
```json
{
    "Authorization": "Bearer <tokenstring>"
}
```

### `200` Response
```json
{
    "data": {
        "message": "Watchdog deleted"
    }
}
```

### `404` Response - If watch-dog is not found
```json
{
    "error": "Watch dog not found"
}
```
