# Secure Information Storage REST API

### Project setup

* Add `secure-storage.localhost` to your `/etc/hosts`: `127.0.0.1 secure-storage.localhost`

* Run `make init` to initialize project

* Open in browser: http://secure-storage.localhost:8000/item Should get `Full authentication is required to access this resource.` error, because first you need to make `login` call (see `postman_collection.json` or `SecurityController` for more info).

### Run tests

make tests

### API credentials

* User: john
* Password: maxsecure

### Postman requests collection

You can import all available API calls to Postman using `postman_collection.json` file

## Api documentation

### Authorization

Used to log in.

```http
POST /login
```

```javascript
{
    "username":"yourusername",
    "password":"yoursecurepassword"
}
```

Example Response:

```javascript
{
    "username":"yourusername",
    "roles":[
        "SOME_ROLE"
    ]
}
```

Status Codes:

| Status Code | Description |
| :--- | :--- |
| 200 | `OK` |
| 401 | `UNAUTHORIZED` |

### Logout

Logs currently logged in user out.

```http
POST /logout
```

Status Codes:

| Status Code | Description |
| :--- | :--- |
| 200 | `OK` |

### Get Items

Used to get all currently logged in user secure storage items.

```http
GET /item
```

Example Response:

```javascript
[
    {
        "id": 1,
        "data": "some secret",
        "created_at": {
            "date": "2021-05-16 16:39:48.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        },
        "updated_at": {
            "date": "2021-05-16 19:19:06.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        }
    },
    {
        "id": 2,
        "data": "some secret",
        "created_at": {
            "date": "2021-05-16 16:39:48.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        },
        "updated_at": {
            "date": "2021-05-16 19:19:06.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        }
    }
]
```

Status Codes:

| Status Code | Description |
| :--- | :--- |
| 200 | `OK` |
| 401 | `UNAUTHORIZED` |

### Create new item

Used to create new item for current user.

```http
POST /item
```

| Parameter | Type | Description |
| :--- | :--- | :--- |
| `data` | `string` | **Required**. Data string that you want to store |

Example Response:

```javascript
[]
```

If data is not passed then error is returned

```javascript
{
    "error":"No data parameter"
}
```

Status Codes:

| Status Code | Description |
| :--- | :--- |
| 200 | `OK` |
| 401 | `UNAUTHORIZED` |

### Update existing item

Used to update existing item that belongs current user.

```http
PUT /item
```

| Parameter | Type | Description |
| :--- | :--- | :--- |
| `id` | `integer` | **Required**. Item id that you want to update |
| `data` | `string` | **Required**. New data string |

Example Response:

```javascript
[]
```

If id parameter is not provided then error is returned

```javascript
{
    "error":"No id parameter"
}
```

If data is not passed then error is returned

```javascript
{
    "error":"No data parameter"
}
```

If item is not found or belong to another user then error is returned

```javascript
{
    "error":"No item"
}
```

Status Codes:

| Status Code | Description |
| :--- | :--- |
| 200 | `OK` |
| 400 | `BAD REQUEST` |
| 401 | `UNAUTHORIZED` |

### Delete existing item

Used to delete existing item that belongs current user.

```http
DELETE /item/{item}
```

| Parameter | Type | Description |
| :--- | :--- | :--- |
| `item` | `integer` | **Required**. Item id that you want to delete |

Example Response:

```javascript
[]
```

If id parameter is invalid then error is returned

```javascript
{
    "error":"No data parameter"
}
```

If item is not found or belong to another user then error is returned

```javascript
{
    "error":"No item"
}
```

Status Codes:

| Status Code | Description |
| :--- | :--- |
| 200 | `OK` |
| 400 | `BAD REQUEST` |
| 401 | `UNAUTHORIZED` |
