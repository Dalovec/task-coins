




# API Docs

## `GET /currencies`

Gets a paginated list of cryptocurrencies

### Response
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
        "first": "http://localhost:8000/currencies?page=1",
        "last": "http://localhost:8000/currencies?page=1",
        "prev": null,
        "next": null,
    }
}
```

## `GET /currencies/{currency}`

Gets a single cryptocurrency

### Response
```json
{
    "data": {
        "id": 1,
        "name": "Bitcoin",
        "coin_id": "bitcoin",
        "image_url": "https://s3.amazonaws.com/cryptocurrency-icons/bitcoin.png",
    },
}
```

## `POST /issue-token`

Creates or finds a user and returns a JWT access token

### Request
```json
{
    "email": "test@example.com",
    "password": "password"
}
```

### Response
```json
{
    "data": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjE1MzU0NDI0MDAwMDAwMDAwMDAwMDAwMCIsImVtYWlsIjoibXlfY2xpZW50X2lkIiwiaWF0IjoxNTQ0NTA0ODAwLCJleHAiOjE1NDQ1MDQ4MDB9.3-5-0-9-8-7-6-5-4-3-2-1-0-9-8-7-6-5-4-3-2-1-0"
    }
}
```

## `POST /revoke-token`

Revokes a JWT access token

### Request
```json
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjE1MzU0NDI0MDAwMDAwMDAwMDAwMDAwMCIsImVtYWlsIjoibXlfY2xpZW50X2lkIiwiaWF0IjoxNTQ0NTA0ODAwLCJleHAiOjE1NDQ1MDQ4MDB9.3-5-0-9-8-7-6-5-4-3-2-1-0-9-8-7-6-5-4-3-2-1-0"
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

## `POST /create-watch-dog`

Creates a watchdog for a currency

### Request
```json
{
    "currency": "bitcoin",
    "email": "test@example.com",
    "password": "password"
}
```

### Response
```json
{
    "data": {
        "id": 1,
        "currency": "bitcoin",
        "email": "test@example.com",
        "active": true,
    }
}
```

## `GET /watch-dogs` 

Gets a paginated list of my watchdogs

### Response
```json
{
    "data": [
        {
            "id": 1,
            "currency": "bitcoin",
            "email": "test@example.com",
            "active": true,
        },
        {
            "id": 2,
            "currency": "ethereum",
            "email": "test@example.com",
            "active": true,
        },
    ],
    "links": {
        "first": "http://localhost:8000/watchdogs?page=1",
        "last": "http://localhost:8000/watchdogs?page=1",
        "prev": null,
        "next": null,
    }
}
```

## `DELETE /watch-dogs/{watch_dog}`

Deletes a watchdog

### Response
```json
{
    "data": {
        "message": "Watchdog deleted"
    }
}
```
