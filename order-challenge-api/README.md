# Order Challenge API Documentation
## Orders

**Request:**
#### Get One Order
```json
GET /api/orders/{id} HTTP/1.1
Accept: application/json
Content-Type: application/json
```

**Response:**
```json
HTTP Status Code: 200
    {
        "id": 1,
        "customerId": 1,
        "items": [
            {
                "productId": 100,
                "quantity": 5,
                "unitPrice": "15.90",
                "total": "79.50"
            }
        ],
        "total": "79.50"
    }
```
#### Get All Orders
**Request:**
```json
GET /api/orders HTTP/1.1
Accept: application/json
Content-Type: application/json
```

**Response:**
```json
HTTP Status Code: 200
   [
        {
               "id": 1,
               "customerId": 1,
               "items": [
                   {
                       "productId": 100,
                       "quantity": 5,
                       "unitPrice": "15.90",
                       "total": "79.50"
                   }
               ],
               "total": "79.50"
       },
       {
           "id": 2,
           "customerId": 2,
           "items": [
               {
                   "productId": 101,
                   "quantity": 2,
                   "unitPrice": "49.50",
                   "total": "99.00"
               },
               {
                   "productId": 100,
                   "quantity": 1,
                   "unitPrice": "120.75",
                   "total": "120.75"
               }
           ],
           "total": "219.75"
       }
  ]
```
#### Order Create

**Request:**
```json
POST /api/orders HTTP/1.1
Accept: application/json
Content-Type: application/json
{
    "customer_id": 1,
    "items" : [
        { "id": 1, "quantity": 4},
        { "id": 3, "quantity": 2}
    ]
}
```

**Response:**
```json
HTTP Status Code: 201
{"message":"Order Created"}
```

If the product is not in stock, you will receive the following response.

**Response:**
```json
HTTP Status Code: 400
{"message":"[items] There no stock."}
```

#### Order Delete

```json
DELETE /api/orders/{id} HTTP/1.1
Accept: application/json
Content-Type: application/json
```

**Response:**
```json
HTTP Status Code: 200
{
    "message": "Order Deleted"
}
```

## Discount

#### Get Discounts Calculate for Order
**Request:**
```json
POST /api/discounts HTTP/1.1
Accept: application/json
Content-Type: application/json
{
    "order_id": 8
}
```

**Response:**
```json
HTTP Status Code: 200
{
    "orderId": 8,
    "discounts": [
        {
            "discountReason": "10_PERCENT_OVER_1000",
            "discountAmount": 100,
            "subTotal": 900
        },
        {
            "discountReason": "BUY_6_GET_1",
            "discountAmount": 400,
            "subTotal": 600
        }
    ],
    "totalDiscount": 500,
    "discountedTotal": 600
}
```

## Customers
#### Get One Customer
**Request:**
```json
GET /api/customers/{id} HTTP/1.1
Accept: application/json
Content-Type: application/json
```

**Response:**
```json
HTTP Status Code: 200
    {
        "id": 1,
        "name": "ali",
        "since": "2021-09-01",
        "revenue": 260
    }
```
#### Get All Customers

**Request:**
```json
GET /api/customers HTTP/1.1
Accept: application/json
Content-Type: application/json
```

**Response:**
```json
HTTP Status Code: 200
[
    {
        "id": 1,
        "name": "osman",
        "since": "2021-09-01",
        "revenue": 275.10
    },
    {
        "id": 2,
        "name": "ali",
        "since": "2021-10-12",
        "revenue": 55.27
    },
    {
        "id": 3,
        "name": "veli",
        "since": "2021-10-12",
        "revenue": 200.11
    }
]
```
#### Customer Create

**Request:**
```json
POST /api/customers HTTP/1.1
Accept: application/json
Content-Type: application/json
{
    "name" : "bekir",
    "since" : "2021-10-12",
    "revenue": 200
}
```

**Response:**
```json
HTTP Status Code: 201
{"message":"Customer Created"}
```

## Products

#### Get One Product
**Request:**
```json
GET /api/products/{id} HTTP/1.1
Accept: application/json
Content-Type: application/json
```

**Response:**
```json
HTTP Status Code: 200
    {
        "name": "product 1",
        "category": 1,
        "price": 100,
        "stock": 9
    }
```
#### Get All Products
**Request:**
```json
GET /api/products HTTP/1.1
Accept: application/json
Content-Type: application/json
```

**Response:**
```json
HTTP Status Code: 200
[
    {
        "name": "product 1",
        "category": 1,
        "price": 100,
        "stock": 9
    },
    {
        "name": "product 2",
        "category": 2,
        "price": 110,
        "stock": 100
    },
    {
        "name": "product 3",
        "category": 2,
        "price": 200,
        "stock": 0
    }
]
```


#### Product Create
**Request:**
```json
POST /api/products HTTP/1.1
Accept: application/json
Content-Type: application/json
{
    "name" : "Foo Anahtarlik",
    "category_id" : 5,
    "price" : 155,
    "stock": 200
}
```

**Response:**
```json
HTTP Status Code: 201
{"message":"Product Created"}
```