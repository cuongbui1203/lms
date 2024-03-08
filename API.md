# API DOCUMENT

## Base URL
`http://localhost:8000/api`

## Tag 
> `AUTH`: can co 1 truong trong header: `Authorization: Bearer {token}`


## Register ###
> `POST` : `/users`  

### Cac truong yeu cau:  
> `name`: required  
> `email`: required | email  
> `username`: required  
> `dob`: required | date  
> `address`: string  
> `phone`: required  
> `wp_id`: required | can co workPlate ton tai  
> `image`: image | file  
> `password`: required | min:8 | chua chu va so  
> `password_confirmation`: required | giong password  
#### VD:  
> `name`: test  
> `email`: a@a.a  
> `username`: username  
> `dob`: 14/3/2020  
> `address`: hn  
> `phone`: 0123456789
> `wp_id`: 1  
> `image`:   
> `password`: password1  
> `password_confirmation`: password1

## Response
### Success
```
{
    "success": true,
    "data": {
        "name": "test",
        "username": "username",
        "email": "a@a.a",
        "dob": "14/3/2020",
        "role_id": 3,
        "wp_id": "1",
        "phone": "0123456789",
        "address": "hn",
        "img_id": 1,
        "updated_at": "2024-03-07T17:26:52.000000Z",
        "created_at": "2024-03-07T17:26:52.000000Z",
        "id": 3
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
## Get user info
`GET`: `users/me`  
`AUTH`  
### Response
### Success
```
{
    "success": true,
    "data": {
        "id": 2,
        "name": "test",
        "email": "a2@a.a",
        "email_verified_at": null,
        "created_at": "2024-03-07T07:53:26.000000Z",
        "updated_at": "2024-03-07T07:53:26.000000Z",
        "phone": "1231231",
        "dob": "0001-01-01",
        "username": "username2",
        "address": "address",
        "role_id": 3,
        "wp_id": 1,
        "img_id": 3
    },
    "message": ""
}
```
### Fail
![image](./image/image.png)

## Image
### GetImage
`post`: `images/{id}`  
### Response
#### Success
> file Image
#### Fail
> http code `404`
