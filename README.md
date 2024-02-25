# Test task

Test task application built on SF6

## Prerequisites
* Docker

## Installation

Use makefile with following commands

```bash
make init
make install
```

## Api endpoints
* [POST] - http://localhost:8081/api/shop/calculate-price

Calculates price according to tax and discount and returns value in JSON. 

JSON request example: 

```bash
{
    "product": 1,
    "taxNumber": "DE123456789",
    "couponCode": "P15"
}
```

* [POST] - http://localhost:8081/api/shop/purchase

Creates record in Purchase table according to submitted data. 

JSON request example:

```bash
{
    "product": 1,
    "taxNumber": "DE123456789",
    "couponCode": "P15"
}
```

## License

[MIT](https://choosealicense.com/licenses/mit/)