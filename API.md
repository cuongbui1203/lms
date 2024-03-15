# API DOCUMENT

## Base URL
`http://localhost:8000/api`

## Tag 
> - `AUTH`: can co 1 truong trong header: `Authorization: Bearer {token}`  
> - `Admin`: Account Admin

## Register ###
> `POST` : `/users`  

### Cac truong yeu cau:  
> `name`: required  
> `username`: required    
> `password`: required | min:8 | chua chu va so  
> `password_confirmation`: required | giong password  
#### VD:  
> `name`: test  
> `username`: username  
> `password`: password1  
> `password_confirmation`: password1

## Response
### Success
```
{
    "success": true,
    "data": {
        "name": "test",
        "username": "username26",
        "role_id": 3,
        "updated_at": 1710439777,
        "created_at": 1710439777,
        "id": 1
    },
    "message": "create User success"
}
```
### Error
```
{
    "success": false,
    "error": {
        "name": [
            "The name field is required."
        ],
        "username": [
            "The username field is required."
        ],
        "dob": [
            "The dob field is required."
        ],
        "password": [
            "The password field is required."
        ],
        "email": [
            "The email field is required."
        ]
    },
    "status_code": 422
}
```
## Login
> `POST`: `users/login`
## Cac truong yeu cau
> `username`: required  
> `password`: required

## Response
### Success
```
{
    "success": true,
    "data": {
        "token": "6|JNemJvmnEFDx3A5xY8V3UtLUIOruc1nKYDLVqGuX5cb61cca"
    },
    "message": "User login successfully."
}
```
### Error
```
{
    "success": false,
    "error": {
        "username": [
            "The username field is required."
        ],
        "password": [
            "The password field is required."
        ]
    },
    "status_code": 422
}
```
## Get user info `AUTH` 
`GET`: `users/me`  
 
### Response
### Success
```
{
    "success": true,
    "data": {
        "id": 1,
        "name": "test",
        "email": null,
        "email_verified_at": null,
        "created_at": "2024-03-14 11:09:37",
        "updated_at": "2024-03-14 11:09:37",
        "phone": null,
        "dob": null,
        "username": "username26",
        "address": null,
        "role_id": 3,
        "wp_id": null,
        "img_id": null,
        "img": null,
        "work_plate": null,
        "role": {
            "id": 3,
            "name": "User",
            "desc": "User",
            "created_at": "2024-03-14T10:51:05.000000Z",
            "updated_at": "2024-03-14T10:51:05.000000Z"
        }
    },
    "message": ""
}
```
### Fail
![image](./image/image.png)

## Get list Account `AUTH` `Admin`
`GET` `users\`  
  

Param
>page: number|min:1  

### Response
```
{
    "success": true,
    "data": {
        "total": 1,
        "currentPage": 1,
        "pageSize": 12,
        "data": [
            {
                "id": 1,
                "name": "test",
                "email": null,
                "role_id": 3,
                "wp_id": null,
                "role": {
                    "id": 3,
                    "name": "User",
                    "desc": "User",
                    "created_at": "2024-03-14T10:51:05.000000Z",
                    "updated_at": "2024-03-14T10:51:05.000000Z"
                },
                "work_plate": null
            }
        ]
    },
    "message": "success"
}
```

## Image
### GetImage
`post`: `images/{id}`  
### Response
#### Success
> file Image
#### Fail
> http code `404`

## Work Plate
### Create `AUTH` `ADMIN`
`POST` `\work-plates`

#### Cac truong yeu cau:
> - name: required
> - address: required
> - type_id: required

#### Response
```
{
    "success": true,
    "data": {
        "name": "test",
        "address": "ew",
        "type_id": "1",
        "updated_at": "2024-03-15T03:48:29.000000Z",
        "created_at": "2024-03-15T03:48:29.000000Z",
        "id": 2
    },
    "message": "WorkPlate create success"
}
```
```
{
    "success": false,
    "error": {
        "address": [
            "The address field is required."
        ]
    },
    "status_code": 422
}
```

### Get info `AUTH`
`GET` `\work-plates\{id}`

#### Response
```
{
    "success": true,
    "data": {
        "id": 1,
        "name": "test",
        "address": "ew",
        "type_id": 1,
        "created_at": "2024-03-14T18:27:39.000000Z",
        "updated_at": "2024-03-14T18:27:39.000000Z",
        "detail": {
            "id": 1,
            "wp_id": 1,
            "max_payload": 1000,
            "payload": 0,
            "created_at": "2024-03-14T18:47:00.000000Z",
            "updated_at": "2024-03-14T18:47:00.000000Z"
        },
        "type": {
            "id": 1,
            "name": "warehouse"
        }
    },
    "message": "Get success"
}
```
```
HTTP code 404
```

## Type `AUTH`
`GET` `types\{work-plates|vehicles}`

### Response
```
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "warehouse",
            "for": 1
        },
        {
            "id": 2,
            "name": "transactionPoint",
            "for": 1
        },
        {
            "id": 3,
            "name": "transshipmentPoint",
            "for": 1
        }
    ],
    "message": "Get all Type success"
}
```