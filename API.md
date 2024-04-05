# API DOCUMENT

## Base URL
`http://localhost:8000/api`

## Tag 
> - `AUTH`: can co 1 truong trong header: `Authorization: Bearer {token}`  
> - `Admin`: Account Admin

## Type
![image](./image/type.png)

## CSRF Token `AUTH`
`GET`: `/token`
>- Lấy csrf token của phiên hiện tại.  
>- Cần thêm trường `_token` vào mỗi requset gửi lên.

### Response
```
{
    "token": "Cl6MrILAOrVW0gvhrd68k8Uv1pYKzkzjAHUDtdWj"
}
```

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
### Cac truong yeu cau
> `username`: required  
> `password`: required

### Response
#### Success
```
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "test",
            "email": null,
            "email_verified_at": null,
            "created_at": "2024-03-23 06:00:33",
            "updated_at": "2024-03-23 06:00:33",
            "phone": null,
            "dob": null,
            "username": "username",
            "address": null,
            "role_id": 2,
            "wp_id": null,
            "img_id": null,
            "role": {
                "id": 2,
                "name": "User",
                "desc": "User",
                "created_at": "2024-03-22T17:37:12.000000Z",
                "updated_at": "2024-03-22T17:37:12.000000Z"
            },
            "work_plate": null,
            "img": null
        },
        "token": "3|VmMe6ejKCQzNuRCflJsGqTgD0clTu38RIMYkXvQIc0f88ec0",
    },
    "message": "User login successfully."
}
```
#### Error
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
## Logout `AUTH`
`DELETE`: `users/me`
### Response
```
{
    "success": true,
    "data": [],
    "message": "logout success"
}
```
```
HTTP status code 500
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

## Update info user `AUTH`
`PUT`: `users\{id}`
>`name`: string  
>`dob`: date  
>`email`: email  
>`phone`: string
>`address`: string
>`image`: image

### Response
```
{
    "success": true,
    "data": {
        "id": 1,
        "name": "test",
        "email": null,
        "email_verified_at": null,
        "created_at": "2024-03-21 15:32:58",
        "updated_at": "2024-03-21 15:32:58",
        "phone": null,
        "dob": null,
        "username": "username",
        "address": null,
        "role_id": 2,
        "wp_id": null,
        "img_id": null
    },
    "message": "update user success"
}
```
```
{
    "success": false,
    "error": {
        "email": [
            "The email field must be a valid email address."
        ]
    },
    "status_code": 422
}
```

## Change WP `AUTH` `Admin`
`PUT` `users\{id}\change-wp`

> `wp_id`: required|exists:work_palates,id
### Response
```
{
    "success": true,
    "data": {
        "id": 1,
        "name": "test",
        "email": null,
        "email_verified_at": null,
        "created_at": "2024-03-22 06:21:32",
        "updated_at": "2024-03-22 06:23:48",
        "phone": null,
        "dob": null,
        "username": "username",
        "address": null,
        "role_id": 1,
        "wp_id": 1,
        "img_id": null
    },
    "message": "change WP success"
}
```
```
{
    "success": false,
    "error": {
        "wp_id": [
            "The selected wp id is invalid."
        ]
    },
    "status_code": 422
}
```


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
## Address
> Đây là các Api để lấy thông tin về địa chỉ theo địa chỉ của Việt Nam  

### Provinces
`\address\provinces`  
```
{
    "success": true,
    "data": [
        {
            "code": "01",
            "name": "Hà Nội",
            "name_en": "Ha Noi",
            "full_name": "Thành phố Hà Nội",
            "full_name_en": "Ha Noi City",
            "code_name": "ha_noi",
            "administrative_unit_id": 1,
            "administrative_region_id": 3
        },
        {
            "code": "26",
            "name": "Vĩnh Phúc",
            "name_en": "Vinh Phuc",
            "full_name": "Tỉnh Vĩnh Phúc",
            "full_name_en": "Vinh Phuc Province",
            "code_name": "vinh_phuc",
            "administrative_unit_id": 2,
            "administrative_region_id": 3
        },...
    ],
     "message": "get all provinces"
}
```

### District
`\address\districts?code={code}`  
`code` là mã province  
```
{
    "success": true,
    "data": [
        {
            "code": "001",
            "name": "Ba Đình",
            "name_en": "Ba Dinh",
            "full_name": "Quận Ba Đình",
            "full_name_en": "Ba Dinh District",
            "code_name": "ba_dinh",
            "province_code": "01",
            "administrative_unit_id": 5
        },
        {
            "code": "002",
            "name": "Hoàn Kiếm",
            "name_en": "Hoan Kiem",
            "full_name": "Quận Hoàn Kiếm",
            "full_name_en": "Hoan Kiem District",
            "code_name": "hoan_kiem",
            "province_code": "01",
            "administrative_unit_id": 5
        },...
    ],
     "message": "get all districts"
}
```

### Ward
`\address\wards?code={code}`  
`code` là mã District  
```
{
    "success": true,
    "data": [
        {
            "code": "31942",
            "name": "1",
            "name_en": "1",
            "full_name": "Phường 1",
            "full_name_en": "Ward 1",
            "code_name": "1",
            "district_code": "959",
            "administrative_unit_id": 8
        },
        {
            "code": "31945",
            "name": "Hộ Phòng",
            "name_en": "Ho Phong",
            "full_name": "Phường Hộ Phòng",
            "full_name_en": "Ho Phong Ward",
            "code_name": "ho_phong",
            "district_code": "959",
            "administrative_unit_id": 8
        },...
    ],
     "message": "get all wards"
}
```
## Vehicle `AUTH` `ADMIN`
### Create 
`POST` `vehicles/`  
> Tạo xe mới  

> `name`: required  
> `payload`: required|numeric|min:0  
> `typeId`: required|in(type.vehicle)

#### Response
```
{
    "success": true,
    "data": [],
    "message": "create vehicle success"
}
```
```
http status code 402
```

### Show
`GET` `vehicles/{id}`

#### Response
```
{
    "success": true,
    "data": {
        "id": 1,
        "created_at": "2024-03-18T20:42:23.000000Z",
        "updated_at": "2024-03-18T20:42:23.000000Z",
        "name": "tét",
        "payload": 10000,
        "type_id": 4,
        "driver_id": 1,
        "type": {
            "id": 4,
            "name": "freezingCar",
            "for": 2
        },
        "driver": {
            "id": 1,
            "name": "test",
            "email": null,
            "email_verified_at": null,
            "created_at": "2024-03-18 20:42:14",
            "updated_at": "2024-03-18 20:42:14",
            "phone": null,
            "dob": null,
            "username": "username",
            "address": null,
            "role_id": 4,
            "wp_id": null,
            "img_id": null
        }
    },
    "message": ""
}
```

### Delete
`DELETE` `vehicles/{id}`

#### Response
```
{
    "success": true,
    "data": [],
    "message": "delete success"
}
```

### Edit
`PUT` `vehicles/{id}`
> `name`  
> `payload`: numeric|min:0  
> `driverId`: exists:users,id

#### Response
```
{
    success:true,
    data:[],
    message:success
}
```

## Order

### Create `AUTH` 

> `sender_name`: required|string  
> `sender_phone`: required|string  
> `sender_address_id`: required|exists  
> `receiver_name`: required|string  
> `receiver_phone`: required|string  
> `receiver_address_id`: required|exists  

#### Response
```
{
    "success": true,
    "data": {
        "sender_name": "test",
        "sender_phone": "123123123",
        "sender_address_id": "27280",
        "receiver_name": "tert",
        "receiver_phone": "123",
        "receiver_address_id": "27283",
        "updated_at": "2024-03-22T06:45:17.000000Z",
        "created_at": "2024-03-22T06:45:17.000000Z",
        "id": 13,
        "notifications": [
            {
                "id": 2,
                "order_id": 13,
                "from_id": 1,
                "to_id": 1,
                "status_id": 10,
                "description": null,
                "created_at": null,
                "updated_at": null
            }
        ]
    },
    "message": ""
}
```
```
{
    "success": false,
    "error": {
        "sender_name": [
            "The sender name field is required."
        ],
        "receiver_name": [
            "The receiver name field is required."
        ],
        "sender_phone": [
            "The sender phone field is required."
        ],
        "receiver_phone": [
            "The receiver phone field is required."
        ],
        "sender_address_id": [
            "The sender address id field is required."
        ],
        "receiver_address_id": [
            "The selected receiver_address_id is invalid."
        ]
    },
    "status_code": 422
}
```

### Detail `AUTH`
`GET`:`orders/{id}`

#### Response
```
{
    "success": true,
    "data": {
        "id": 15,
        "sender_name": "test",
        "sender_address_id": "27280",
        "sender_phone": "123123123",
        "receiver_name": "tert",
        "receiver_address_id": "27283",
        "receiver_phone": "123",
        "created_at": "2024-03-22T06:50:48.000000Z",
        "updated_at": "2024-03-22T06:50:48.000000Z",
        "notifications": [
            {
                "id": 4,
                "order_id": 15,
                "from_id": 1,
                "to_id": 1,
                "status_id": 10,
                "description": null,
                "created_at": null,
                "updated_at": null
            }
        ],
        "details": []
    },
    "message": "get Order Detail success"
}
```
```
HTTP status code 404
```

### Add detail order `AUTH`
`POST`: `orders/{orderId}`

> `type_id`: required|in(9,10,11,12)    
> `name`: required|string  
> `mass`: required|numeric|min:1,  
> `desc`: required  
> `img`: image|file

#### Response
```
{
    "success": true,
    "data": {
        "order_id": 15,
        "type_id": "9",
        "desc": "test thpo",
        "mass": "1000",
        "name": "test",
        "updated_at": "2024-03-22T07:26:49.000000Z",
        "created_at": "2024-03-22T07:26:49.000000Z",
        "id": 113
    },
    "message": "add order detail success"
}
```
```
HTTP status code 404
```
```
{
    "success": false,
    "error": {
        "mass": [
            "The mass field is required."
        ],
        "desc": [
            "The desc field is required."
        ]
    },
    "status_code": 422
}
```
### Get Suggestion next position `AUTH`
`GET`: `/multi/next`

>`orders`: json string. contain array of id order want to get Suggestion

#### Response
```
{
    "success": true,
    "data": {
        "11": {
            "id": 2,
            "name": "im9n2cNtqE",
            "address_id": "27280",
            "type_id": 3,
            "created_at": "2024-04-05T06:21:17.000000Z",
            "updated_at": "2024-04-05T06:21:17.000000Z",
            "cap": "2",
            "vung": "773",
            "address": {
                "provinceCode": "79",
                "districtCode": "773",
                "wardCode": "27280",
                "province": "Thành phố Hồ Chí Minh",
                "district": "Quận 4",
                "ward": "Phường 14"
            },
            "detail": null,
            "type": {
                "id": 3,
                "name": "transshipmentPoint",
                "for": 1
            }
        }
    },
    "message": "gui dia diem diem goi y tiep theo"
}
```
```
{
    "success": false,
    "error": {
        "orders": [
            {
                "12": "Order id invalid."
            },
            {
                "22": "Order id invalid."
            }
        ]
    },
    "status_code": 422
}
```

### Create request move to next position `AUTH`
`POST`: `/multi/next`

> `data`: là 1 chuỗi json. chứa mảng các item:  
> Mỗi Item sẽ có:
> - `to_id`: id wp tồn tại trong hệ thống.  
> - `to_address_id`: id wards tồn tại.  
> - `from_id`: id wp tồn tại trong hệ thống.  
> - `from_address_id`: id wards tồn tại.  
> - `orderId`: id order muốn chuyển.  

#### Response
```
{   
    "success": true,
    "data": [],
    "message": "move to next post ok"
}
```
```
{
    "success": false,
    "error": {
        "request-no-1": [
            {
                "to": "must has one of to id or to address id"
            }
        ],
        "request-no-2": [
            {
                "from": "must has one of from id or from address id"
            }
        ],
        "request-no-3": [
            {
                "from": "must has one of from id or from address id",
                "to_address_id": "must be string"
            }
        ]
    },
    "status_code": 422
}
```

### Confirm order arrived `AUTH`
`PUT`: `multi/arrived`
> `data`: json. array contain list id order
#### Response
```
{
    "success": true,
    "data": [],
    "message": "success"
}
```
```
{
    "success": false,
    "error": {
        "orders": [
            {
                "12": "Order id invalid."
            },
            {
                "22": "Order id invalid."
            }
        ]
    },
    "status_code": 422
}
```
