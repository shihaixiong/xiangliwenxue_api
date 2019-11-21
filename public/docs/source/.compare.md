---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://localhost/docs/collection.json)

<!-- END_INFO -->

#general
<!-- START_9b03e0d435f17585b849bfea550eb6b9 -->
## 搜索 api/searchBook/search
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/searchBook/search" \
    -H "Content-Type: application/json" \
    -d '{"search":"corrupti"}'

```
```javascript
const url = new URL("http://localhost/api/searchBook/search");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "search": "corrupti"
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": []
}
```
> Example response (200):

```json
{
    "code": 0,
    "msg": "密码设置失败",
    "data": []
}
```

### HTTP Request
`GET api/searchBook/search`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    search | string |  required  | 搜索内容

<!-- END_9b03e0d435f17585b849bfea550eb6b9 -->

<!-- START_c13ba8df19d990647359f88366096219 -->
## 搜索记录 api/searchBook/searchList
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/searchBook/searchList" 
```
```javascript
const url = new URL("http://localhost/api/searchBook/searchList");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": {
        "code": 1,
        "msg": "success",
        "data": [
            {
                "keyName": "b"
            },
            {
                "keyName": "d"
            },
            {
                "keyName": "1"
            }
        ]
    }
}
```
> Example response (200):

```json
{
    "code": 0,
    "msg": "没有搜索记录",
    "data": []
}
```

### HTTP Request
`GET api/searchBook/searchList`


<!-- END_c13ba8df19d990647359f88366096219 -->

#书库
<!-- START_8c9e343a59fc0752340851b893f60dc7 -->
## 书库 api/bookStack/list
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/bookStack/list" \
    -H "Content-Type: application/json" \
    -d '{"page":8}'

```
```javascript
const url = new URL("http://localhost/api/bookStack/list");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "page": 8
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": [
        {
            "title": "李莲花夜ya",
            "desc": "一段可歌可泣可笑可爱的草根崛起史",
            "author": "李莲花",
            "keywords": [
                "异世"
            ],
            "image": "http:\/\/127.0.0.1\/\/Users\/wangxin\/Desktop\/test_dir\/15583423582532.jpeg",
            "is_vip": 1,
            "articleid": 9
        },
        {
            "title": "将夜",
            "desc": "一段可歌可泣可笑可爱的草根崛起史。 　　一个物质要求宁滥勿缺的开朗少年行。 　　书院后山里永恒回荡着他疑惑的声音： 　　宁可永劫受沉沦，不从诸圣求解脱？ 　　与天斗，其乐无穷。 　　…… 　　…… 　　这是一个“别人家孩子”撕掉臂上杠章后穿越前尘的故事，作者俺要说的是：千万年来，拥有吃肉的自由和自由吃肉的能力，就是我们这些万物之灵奋斗的目标。",
            "author": "猫腻",
            "keywords": [
                "异世"
            ],
            "image": "http:\/\/127.0.0.1\/book_pic\/18b45bbcaab3ac3d75cf6e250e5f3e66.jpeg",
            "is_vip": 1,
            "articleid": 1
        }
    ]
}
```

### HTTP Request
`GET api/bookStack/list`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    page | integer |  optional  | 页码

<!-- END_8c9e343a59fc0752340851b893f60dc7 -->

<!-- START_6a69409e3b3cdefcf21160c26ef87ea2 -->
## 导航 api/bookStack/navigation
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/bookStack/navigation" 
```
```javascript
const url = new URL("http://localhost/api/bookStack/navigation");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": {
        "channel": {
            "1": "男频",
            "2": "女频",
            "3": "漫画"
        },
        "sort": {
            "1": "言情",
            "2": "校园",
            "3": "玄幻",
            "4": "恐怖",
            "5": "悬疑",
            "6": "社会",
            "7": "战争",
            "8": "自传"
        },
        "finish": [
            "连载中",
            "已完结"
        ]
    }
}
```

### HTTP Request
`GET api/bookStack/navigation`


<!-- END_6a69409e3b3cdefcf21160c26ef87ea2 -->

<!-- START_73f773bb3f677906cd93f9d9dff78646 -->
## 排行榜 api/bookStack/rankingList
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/bookStack/rankingList" 
```
```javascript
const url = new URL("http://localhost/api/bookStack/rankingList");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": {
        "channel": {
            "1": "男频",
            "2": "女频",
            "3": "漫画"
        },
        "sort": {
            "1": "言情",
            "2": "校园",
            "3": "玄幻",
            "4": "恐怖",
            "5": "悬疑",
            "6": "社会",
            "7": "战争",
            "8": "自传"
        },
        "finish": [
            "连载中",
            "已完结"
        ]
    }
}
```

### HTTP Request
`GET api/bookStack/rankingList`


<!-- END_73f773bb3f677906cd93f9d9dff78646 -->

#书架
<!-- START_5621a313fbe25701c203fb8ac2e46eb5 -->
## 获取书架内容 api/book/shelf
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/book/shelf" 
```
```javascript
const url = new URL("http://localhost/api/book/shelf");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": {
        "lastRead": {
            "articleid": 1,
            "title": "第二十三章 我以为你知道我的异禀……",
            "image": "http:\/\/127.0.0.1\/book_pic\/18b45bbcaab3ac3d75cf6e250e5f3e66.jpeg",
            "ratio": 24,
            "chapterid": 24
        },
        "shelf": [
            {
                "image": "http:\/\/127.0.0.1\/book_pic\/18b45bbcaab3ac3d75cf6e250e5f3e66.jpeg",
                "title": "将夜",
                "articleid": 1
            }
        ],
        "rec": [
            {
                "image": "http:\/\/127.0.0.1\/\/Users\/wangxin\/Desktop\/test_dir\/15583423582532.jpeg",
                "title": "李莲花ya",
                "articleid": 9
            },
            {
                "image": "http:\/\/127.0.0.1\/book_pic\/18b45bbcaab3ac3d75cf6e250e5f3e66.jpeg",
                "title": "将夜",
                "articleid": 1
            }
        ]
    }
}
```
> Example response (200):

```json
{
    "code": 0,
    "msg": "添加失败",
    "data": []
}
```

### HTTP Request
`GET api/book/shelf`


<!-- END_5621a313fbe25701c203fb8ac2e46eb5 -->

<!-- START_a579b45db033cbee7159ca6198745fde -->
## api/book/sign
> Example request:

```bash
curl -X POST "http://localhost/api/book/sign" 
```
```javascript
const url = new URL("http://localhost/api/book/sign");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`POST api/book/sign`


<!-- END_a579b45db033cbee7159ca6198745fde -->

<!-- START_f96c1b1dd3fc033e339f6ea862fc7781 -->
## 加入书架 book/shelf/add
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X POST "http://localhost/api/book/shelf/add" \
    -H "Content-Type: application/json" \
    -d '{"articleid":10}'

```
```javascript
const url = new URL("http://localhost/api/book/shelf/add");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "articleid": 10
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": []
}
```
> Example response (200):

```json
{
    "code": 0,
    "msg": "添加失败",
    "data": []
}
```

### HTTP Request
`POST api/book/shelf/add`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    articleid | integer |  required  | 书籍id

<!-- END_f96c1b1dd3fc033e339f6ea862fc7781 -->

<!-- START_a14b60a090a48668895b1198a3454f8b -->
## 加入书架 book/shelf/del
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X POST "http://localhost/api/book/shelf/del" \
    -H "Content-Type: application/json" \
    -d '{"articleid":13}'

```
```javascript
const url = new URL("http://localhost/api/book/shelf/del");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "articleid": 13
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": []
}
```
> Example response (200):

```json
{
    "code": 0,
    "msg": "error",
    "data": []
}
```

### HTTP Request
`POST api/book/shelf/del`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    articleid | integer |  required  | 书籍id  多个id用逗号隔开

<!-- END_a14b60a090a48668895b1198a3454f8b -->

#任务中心
<!-- START_f5f20df060db3cfe53c12d20513879db -->
## 获取任务中心列表 api/task/list
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/task/list" 
```
```javascript
const url = new URL("http://localhost/api/task/list");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": {
        "result": [
            {
                "id": 1,
                "task_name": "每日签到",
                "type": 1,
                "reward_integral": 10,
                "status": 2
            },
            {
                "id": 2,
                "task_name": "每日登录",
                "type": 1,
                "reward_integral": 1,
                "status": 2
            },
            {
                "id": 3,
                "task_name": "每日分享",
                "type": 1,
                "reward_integral": 20,
                "status": 0
            },
            {
                "id": 4,
                "task_name": "订阅章节",
                "type": 2,
                "reward_integral": 5,
                "status": 0
            },
            {
                "id": 5,
                "task_name": "充值",
                "type": 2,
                "reward_integral": 100,
                "status": 0
            }
        ]
    }
}
```
> Example response (200):

```json
{
    "code": 0,
    "msg": "暂无任务",
    "data": []
}
```

### HTTP Request
`GET api/task/list`


<!-- END_f5f20df060db3cfe53c12d20513879db -->

<!-- START_6e570c970def128c6ef4ff78571976eb -->
## 完成任务 api/task/finish
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/task/finish" \
    -H "Content-Type: application/json" \
    -d '{"tid":15}'

```
```javascript
const url = new URL("http://localhost/api/task/finish");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "tid": 15
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": []
}
```
> Example response (200):

```json
{
    "code": 0,
    "msg": "网络错误,请重试",
    "data": []
}
```

### HTTP Request
`GET api/task/finish`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    tid | integer |  required  | 任务id

<!-- END_6e570c970def128c6ef4ff78571976eb -->

#商城
<!-- START_56357339e7a37503b86a42feb11c151e -->
## 商品列表 api/shop/list
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/shop/list" 
```
```javascript
const url = new URL("http://localhost/api/shop/list");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": {
        "result": {
            "integral": 0,
            "goods": [
                {
                    "goods_type": "A",
                    "goods": [
                        {
                            "id": 3,
                            "name": "测试商品C",
                            "cover": "http:\/\/148.70.4.186\/\/upload\/goods_img\/90cf950564c6b697ccb857beee06933b.jpeg",
                            "type": "A",
                            "price": 300,
                            "sold": 0
                        },
                        {
                            "id": 4,
                            "name": "测试商品D",
                            "cover": "http:\/\/148.70.4.186\/\/upload\/goods_img\/ba9cab888d7c08de68a664c66a89e24b.jpeg",
                            "type": "A",
                            "price": 300,
                            "sold": 0
                        },
                        {
                            "id": 9,
                            "name": "测试商品I",
                            "cover": "http:\/\/148.70.4.186\/\/upload\/goods_img\/90cf950564c6b697ccb857beee06933b.jpeg",
                            "type": "A",
                            "price": 300,
                            "sold": 0
                        },
                        {
                            "id": 10,
                            "name": "测试商品J",
                            "cover": "http:\/\/148.70.4.186\/\/upload\/goods_img\/ba9cab888d7c08de68a664c66a89e24b.jpeg",
                            "type": "A",
                            "price": 300,
                            "sold": 0
                        },
                        {
                            "id": 11,
                            "name": "测试商品K",
                            "cover": "http:\/\/148.70.4.186\/\/upload\/goods_img\/90cf950564c6b697ccb857beee06933b.jpeg",
                            "type": "A",
                            "price": 300,
                            "sold": 0
                        },
                        {
                            "id": 13,
                            "name": "测试商品M",
                            "cover": "http:\/\/148.70.4.186\/\/upload\/goods_img\/ba9cab888d7c08de68a664c66a89e24b.jpeg",
                            "type": "A",
                            "price": 300,
                            "sold": 0
                        },
                        {
                            "id": 15,
                            "name": "测试商品O",
                            "cover": "http:\/\/148.70.4.186\/\/upload\/goods_img\/90cf950564c6b697ccb857beee06933b.jpeg",
                            "type": "A",
                            "price": 300,
                            "sold": 0
                        }
                    ]
                },
                {
                    "goods_type": "衣",
                    "goods": [
                        {
                            "id": 1,
                            "name": "测试商品A",
                            "cover": "http:\/\/148.70.4.186\/\/upload\/goods_img\/ba9cab888d7c08de68a664c66a89e24b.jpeg",
                            "type": "衣",
                            "price": 300,
                            "sold": 0
                        },
                        {
                            "id": 5,
                            "name": "测试商品E",
                            "cover": "http:\/\/148.70.4.186\/\/upload\/goods_img\/90cf950564c6b697ccb857beee06933b.jpeg",
                            "type": "衣",
                            "price": 300,
                            "sold": 0
                        },
                        {
                            "id": 6,
                            "name": "测试商品F",
                            "cover": "http:\/\/148.70.4.186\/\/upload\/goods_img\/90cf950564c6b697ccb857beee06933b.jpeg",
                            "type": "衣",
                            "price": 300,
                            "sold": 0
                        },
                        {
                            "id": 8,
                            "name": "测试商品H",
                            "cover": "http:\/\/148.70.4.186\/\/upload\/goods_img\/90cf950564c6b697ccb857beee06933b.jpeg",
                            "type": "衣",
                            "price": 300,
                            "sold": 0
                        },
                        {
                            "id": 12,
                            "name": "测试商品L",
                            "cover": "http:\/\/148.70.4.186\/\/upload\/goods_img\/90cf950564c6b697ccb857beee06933b.jpeg",
                            "type": "衣",
                            "price": 300,
                            "sold": 0
                        },
                        {
                            "id": 14,
                            "name": "测试商品N",
                            "cover": "http:\/\/148.70.4.186\/\/upload\/goods_img\/90cf950564c6b697ccb857beee06933b.jpeg",
                            "type": "衣",
                            "price": 300,
                            "sold": 0
                        }
                    ]
                },
                {
                    "goods_type": "食",
                    "goods": [
                        {
                            "id": 2,
                            "name": "测试商品B",
                            "cover": "http:\/\/148.70.4.186\/\/upload\/goods_img\/90cf950564c6b697ccb857beee06933b.jpeg",
                            "type": "食",
                            "price": 300,
                            "sold": 0
                        },
                        {
                            "id": 7,
                            "name": "测试商品G",
                            "cover": "http:\/\/148.70.4.186\/\/upload\/goods_img\/ba9cab888d7c08de68a664c66a89e24b.jpeg",
                            "type": "食",
                            "price": 300,
                            "sold": 0
                        }
                    ]
                }
            ]
        }
    }
}
```

### HTTP Request
`GET api/shop/list`


<!-- END_56357339e7a37503b86a42feb11c151e -->

<!-- START_4d83548e92132ec40ac7e85d17883c1a -->
## 商品详情 api/shop/details
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/shop/details" \
    -H "Content-Type: application/json" \
    -d '{"id":19}'

```
```javascript
const url = new URL("http://localhost/api/shop/details");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "id": 19
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{}
```

### HTTP Request
`GET api/shop/details`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    id | integer |  required  | 商品id

<!-- END_4d83548e92132ec40ac7e85d17883c1a -->

<!-- START_93c8e9455ed6f54b596b52cadcc1e8a2 -->
## 商品兑换 api/shop/purchase
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X POST "http://localhost/api/shop/purchase" \
    -H "Content-Type: application/json" \
    -d '{"id":12,"tel":"et","address":"quis","name":"alias"}'

```
```javascript
const url = new URL("http://localhost/api/shop/purchase");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "id": 12,
    "tel": "et",
    "address": "quis",
    "name": "alias"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`POST api/shop/purchase`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    id | integer |  required  | 商品id
    tel | string |  required  | 电话号码
    address | string |  required  | 收货地址
    name | string |  required  | 收货姓名

<!-- END_93c8e9455ed6f54b596b52cadcc1e8a2 -->

<!-- START_402c7ae8238dfd73fbacc3b70d08d54e -->
## 商品兑换记录 api/shop/purchaseRecord
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/shop/purchaseRecord" 
```
```javascript
const url = new URL("http://localhost/api/shop/purchaseRecord");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": {
        "result": [
            {
                "id": 5,
                "name": "测试商品E",
                "cover": "http:\/\/148.70.4.186\/\/upload\/goods_img\/90cf950564c6b697ccb857beee06933b.jpeg",
                "price": 300,
                "order_num": "156040369029370",
                "order_status": 0,
                "express_num": null
            }
        ]
    }
}
```

### HTTP Request
`GET api/shop/purchaseRecord`


<!-- END_402c7ae8238dfd73fbacc3b70d08d54e -->

<!-- START_0ba5eba93a6629e0f2f7bd991911843d -->
## alipay api/shop/pay
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/shop/pay" 
```
```javascript
const url = new URL("http://localhost/api/shop/pay");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": {
        "result": "alipay_sdk=alipay-sdk-php-20180705&app_id=2019052565361337&biz_content=%7B%22body%22%3A%22tessssst%22%2C%22subject%22%3A%22test%22%2C%22out_trade_no%22%3A%221560404553%22%2C%22timeout_express%22%3A%2215m%22%2C%22total_amount%22%3A%220.01%22%2C%22product_code%22%3A%22QUICK_MSECURITY_PAY%22%7D&charset=UTF-8&format=json&method=alipay.trade.app.pay&notify_url=https%3A%2F%2Fnovel.kdreading.com&sign_type=RSA2&timestamp=2019-06-13+05%3A42%3A33&version=1.0&sign=Qp8aY%2FZel6wy9%2BlbdYSDqGVNYMd4%2F4PqpRIppTRSIzqN7JcB5JlcU0VyjF%2F551yOhZTRgdgK34cXFxhBU8FM62nNjn1Eo5X9JbRU3oYatW0cFCrDHlslRjIBEeumzp%2F%2BSZb9yNW8TmrZG4M0kowecm%2BUjZh0T9ZDlk3GZY8Kyykaf1zkTpqCJT4NgdclBZTKw3jmQM%2F7xXHjLzI17OmPRH%2ByX04zIInkTqttfFFa5TkUptKqd2o0Ij6m8lU9VzyP4iUqxo33e6c6%2FswfEb4r4jtLzQdX3ZRG5VdHtDcq8jLj3arejDW2nh6E30SgeJK3Pv%2FMvs3tjvazo7apr5wpbA%3D%3D"
    }
}
```

### HTTP Request
`GET api/shop/pay`


<!-- END_0ba5eba93a6629e0f2f7bd991911843d -->

<!-- START_b312256511a1e7b00a31f29931f85dcd -->
## wechatPay api/shop/wechatpay
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/shop/wechatpay" 
```
```javascript
const url = new URL("http://localhost/api/shop/wechatpay");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": {
        "result": {
            "appid": "wx38fbbcbda6059377",
            "noncestr": "56SUrXtRENy94T4ZcUBW1T9ST7rSbnqB",
            "package": "Sign=WXPay",
            "partnerid": "1354155002",
            "prepayid": "wx171605579955482d30d6306d1623527600",
            "timestamp": 1560758758,
            "sign": "2971D5D5268585D561E77FF4CFFF3FE0",
            "packagestr": "Sign=WXPay"
        }
    }
}
```

### HTTP Request
`GET api/shop/wechatpay`


<!-- END_b312256511a1e7b00a31f29931f85dcd -->

#用户
<!-- START_57e3b4272508c324659e49ba5758c70f -->
## 登录 api/user/setPwd
登录phone必传 code 和 password必需传一个
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X POST "http://localhost/api/user/login" \
    -H "Content-Type: application/json" \
    -d '{"phone":"exercitationem","password":"suscipit","code":"optio"}'

```
```javascript
const url = new URL("http://localhost/api/user/login");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "phone": "exercitationem",
    "password": "suscipit",
    "code": "optio"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": {
        "token": "string(32)"
    }
}
```
> Example response (200):

```json
{
    "code": 0,
    "msg": "用户名或密码错误",
    "data": []
}
```

### HTTP Request
`POST api/user/login`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    phone | string |  required  | 手机号
    password | string |  required  | 密码
    code | string |  required  | 验证码

<!-- END_57e3b4272508c324659e49ba5758c70f -->

<!-- START_1ebe045c4992fa719becc9455463416c -->
## 我的 api/user/my
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/user/my" 
```
```javascript
const url = new URL("http://localhost/api/user/my");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": {
        "userid": 1,
        "username": "用户20",
        "img": null,
        "remain": 0,
        "money": 0,
        "integral": 0,
        "level": 1
    }
}
```
> Example response (200):

```json
{
    "code": 0,
    "msg": "暂未登录",
    "data": []
}
```

### HTTP Request
`GET api/user/my`


<!-- END_1ebe045c4992fa719becc9455463416c -->

<!-- START_ef6b1d7879caabcd2797d6838e0593b8 -->
## 修改我的信息页面详情 api/user/myInfo
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/user/myInfo" 
```
```javascript
const url = new URL("http://localhost/api/user/myInfo");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": {
        "username": "大哥大",
        "img": "http:\/\/148.70.4.186\/user_logo\/1_lg.jpg",
        "sex": 1
    }
}
```
> Example response (200):

```json
{
    "code": 0,
    "msg": "暂未登录",
    "data": []
}
```

### HTTP Request
`GET api/user/myInfo`


<!-- END_ef6b1d7879caabcd2797d6838e0593b8 -->

<!-- START_5d3e553cea771b2dd78543e975b7a8bc -->
## 发送手机验证码 api/user/sendcode
暂时短信平台不好使  data返回验证码  正式后data不返回数据 成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/user/sendcode" \
    -H "Content-Type: application/json" \
    -d '{"phone":"nobis"}'

```
```javascript
const url = new URL("http://localhost/api/user/sendcode");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "phone": "nobis"
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{}
```
> Example response (200):

```json
{
    "code": 0,
    "msg": "发送频率太快了,请稍后重试",
    "data": []
}
```

### HTTP Request
`GET api/user/sendcode`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    phone | string |  required  | 手机号

<!-- END_5d3e553cea771b2dd78543e975b7a8bc -->

<!-- START_8585fac8b8b79b25bea145c51e6c8b83 -->
## 修改密码 api/user/setPwd
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X POST "http://localhost/api/user/setPwd" \
    -H "Content-Type: application/json" \
    -d '{"password":"molestias"}'

```
```javascript
const url = new URL("http://localhost/api/user/setPwd");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "password": "molestias"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": []
}
```
> Example response (200):

```json
{
    "code": 0,
    "msg": "密码设置失败",
    "data": []
}
```

### HTTP Request
`POST api/user/setPwd`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    password | string |  required  | 密码

<!-- END_8585fac8b8b79b25bea145c51e6c8b83 -->

<!-- START_9e9c7fdb64ae0eb63e3f9bc02243e9be -->
## 修改个人信息 api/user/updInfo
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X POST "http://localhost/api/user/updInfo" \
    -H "Content-Type: application/json" \
    -d '{"username":"doloribus","sex":11,"birthday":"occaecati","image":"porro"}'

```
```javascript
const url = new URL("http://localhost/api/user/updInfo");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "username": "doloribus",
    "sex": 11,
    "birthday": "occaecati",
    "image": "porro"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{}
```
> Example response (200):

```json
{
    "code": 0,
    "msg": "error",
    "data": []
}
```

### HTTP Request
`POST api/user/updInfo`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    username | string |  required  | 用户名
    sex | integer |  required  | 性别
    birthday | string |  required  | 生日
    image | file |  required  | 头像

<!-- END_9e9c7fdb64ae0eb63e3f9bc02243e9be -->

<!-- START_38b5d8281010ab60631806961cee7b09 -->
## 我的订阅 api/user/paid
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/user/paid" \
    -H "Content-Type: application/json" \
    -d '{"page":15}'

```
```javascript
const url = new URL("http://localhost/api/user/paid");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "page": 15
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": {
        "result": [
            {
                "articleid": 1,
                "image": "http:\/\/148.70.4.186\/book_img\/15595504042952.jpg",
                "title": "将夜"
            }
        ]
    }
}
```
> Example response (200):

```json
{
    "code": 0,
    "msg": "暂未登陆",
    "data": []
}
```

### HTTP Request
`GET api/user/paid`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    page | integer |  required  | 页码

<!-- END_38b5d8281010ab60631806961cee7b09 -->

<!-- START_4d4bad2b678c78099b0f22adcd9d73ef -->
## 最近阅读 api/user/lastRead
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/user/lastRead" \
    -H "Content-Type: application/json" \
    -d '{"page":8}'

```
```javascript
const url = new URL("http://localhost/api/user/lastRead");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "page": 8
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": {
        "result": [
            {
                "articleid": 1,
                "image": "http:\/\/148.70.4.186\/book_img\/15595504042952.jpg",
                "title": "将夜"
            }
        ]
    }
}
```
> Example response (200):

```json
{
    "code": 0,
    "msg": "暂未登陆",
    "data": []
}
```

### HTTP Request
`GET api/user/lastRead`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    page | integer |  required  | 页码

<!-- END_4d4bad2b678c78099b0f22adcd9d73ef -->

<!-- START_b8dc60497768812759886a30f8a99790 -->
## 微信登录 api/user/wechatLogin
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X POST "http://localhost/api/user/wechatLogin" \
    -H "Content-Type: application/json" \
    -d '{"username":"ullam","sex":14,"openid":"ut","image":"quaerat"}'

```
```javascript
const url = new URL("http://localhost/api/user/wechatLogin");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "username": "ullam",
    "sex": 14,
    "openid": "ut",
    "image": "quaerat"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": {
        "token": "1c5a599185cd01d4cecb69631d5a6e76",
        "new_user": 0
    }
}
```

### HTTP Request
`POST api/user/wechatLogin`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    username | string |  required  | 用户名
    sex | integer |  required  | 性别
    openid | string |  required  | openid
    image | file |  required  | 头像

<!-- END_b8dc60497768812759886a30f8a99790 -->

#精选
<!-- START_f4acb950fced86821747ca55ab275f94 -->
## 获取频道接口 api/bestchoice/getChannel
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/bestchoice/getChannel" 
```
```javascript
const url = new URL("http://localhost/api/bestchoice/getChannel");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": [
        {
            "channel_id": 1,
            "channel_name": "男频"
        },
        {
            "channel_id": 2,
            "channel_name": "女频"
        },
        {
            "channel_id": 3,
            "channel_name": "漫画"
        }
    ]
}
```

### HTTP Request
`GET api/bestchoice/getChannel`


<!-- END_f4acb950fced86821747ca55ab275f94 -->

<!-- START_181dfb22f1d6c81732feeaab063a23fe -->
## 精选页接口 api/bestchoice/list
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/bestchoice/list" \
    -H "Content-Type: application/json" \
    -d '{"channel":2}'

```
```javascript
const url = new URL("http://localhost/api/bestchoice/list");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "channel": 2
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": {
        "banner": [
            {
                "image": "http:\/\/127.0.0.1\/\/Users\/wangxin\/Desktop\/test_dir\/15583343512963.jpeg",
                "link": "www.baidu.com\/aa",
                "articleid": null
            }
        ],
        "res_pos": [
            {
                "title": "测试推荐位",
                "type": 1,
                "id": 1,
                "content": [
                    {
                        "image": "http:\/\/127.0.0.1\/book_pic\/18b45bbcaab3ac3d75cf6e250e5f3e66.jpeg",
                        "id": 1,
                        "title": "将夜",
                        "desc": "简介",
                        "author": "猫腻",
                        "keywords": [
                            "异世"
                        ]
                    }
                ]
            },
            {
                "title": "测试推荐位6",
                "type": 1,
                "id": 2,
                "content": [
                    {
                        "image": "http:\/\/127.0.0.1\/book_pic\/18b45bbcaab3ac3d75cf6e250e5f3e66.jpeg",
                        "id": 1,
                        "title": "将夜",
                        "desc": "简介",
                        "author": "猫腻",
                        "keywords": [
                            "异世"
                        ]
                    }
                ]
            }
        ]
    }
}
```

### HTTP Request
`GET api/bestchoice/list`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    channel | integer |  required  | 频道 男频1 女频2 漫画3

<!-- END_181dfb22f1d6c81732feeaab063a23fe -->

<!-- START_cc823bbf7582069138042a69d3bf7164 -->
## 获取推荐位更多内容 api/bestchoice/list/more
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/bestchoice/list/more" \
    -H "Content-Type: application/json" \
    -d '{"id":7,"page":13}'

```
```javascript
const url = new URL("http://localhost/api/bestchoice/list/more");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "id": 7,
    "page": 13
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": {
        "result": [
            {
                "image": "http:\/\/148.70.4.186\/book_img\/15597287977431.jpg",
                "id": 482,
                "title": "村长的后院",
                "desc": "村长的后院真牛逼",
                "author": "不知道",
                "keywords": [
                    "后院",
                    "村长"
                ]
            },
            {
                "image": "http:\/\/148.70.4.186\/book_img\/15597304604278.jpg",
                "id": 9,
                "title": "李莲花夜ya",
                "desc": "一段可歌可泣可笑可爱的草根崛起史",
                "author": "李莲花",
                "keywords": [
                    "异世",
                    "重生",
                    "复仇"
                ]
            },
            {
                "image": "http:\/\/148.70.4.186\/book_img\/15595504042952.jpg",
                "id": 1,
                "title": "将夜",
                "desc": "一段可歌可泣可笑可爱的草根崛起史。 　　一个物质要求宁滥勿缺的开朗少年行。 　　书院后山里永恒回荡着他疑惑的声音： 　　宁可永劫受沉沦，不从诸圣求解脱？ 　　与天斗，其乐无穷。 　　…… 　　…… 　　这是一个“别人家孩子”撕掉臂上杠章后穿越前尘的故事，作者俺要说的是：千万年来，拥有吃肉的自由和自由吃肉的能力，就是我们这些万物之灵奋斗的目标。",
                "author": "猫腻",
                "keywords": [
                    "异世"
                ]
            },
            {
                "image": "http:\/\/148.70.4.186\/book_img\/15597305163073.jpg",
                "id": 483,
                "title": "军少独宠惹火妻",
                "desc": "厉害呢",
                "author": "猜一猜",
                "keywords": [
                    "惹火",
                    "独宠",
                    "娇妻"
                ]
            }
        ]
    }
}
```

### HTTP Request
`GET api/bestchoice/list/more`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    id | integer |  required  | 推荐位id
    page | integer |  required  | 页码

<!-- END_cc823bbf7582069138042a69d3bf7164 -->

#美图
<!-- START_5588b350ebb977ecb99c64f3ef905faf -->
## 美图列表 api/beautifulImg/list
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/beautifulImg/list" 
```
```javascript
const url = new URL("http://localhost/api/beautifulImg/list");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{}
```

### HTTP Request
`GET api/beautifulImg/list`


<!-- END_5588b350ebb977ecb99c64f3ef905faf -->

<!-- START_8e7e00e0ce9b46f924a5e9d2cd02dbb7 -->
## 美图详情 api/beautifulImg/details
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/beautifulImg/details" \
    -H "Content-Type: application/json" \
    -d '{"id":12}'

```
```javascript
const url = new URL("http://localhost/api/beautifulImg/details");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "id": 12
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": {
        "id": 3,
        "image": [
            "http:\/\/127.0.0.1\/beautiful_img\/6ae0874044eca7a5cd3df3603c0ce2e7.jpeg",
            "http:\/\/127.0.0.1\/beautiful_img\/64a6adef6f7d5b77a3d91817559b8e5b.jpeg",
            "http:\/\/127.0.0.1\/beautiful_img\/039d8d99699b6e4868ce55ab1f3afc5d.jpg"
        ],
        "praise": 1,
        "upload": 1,
        "browse": 10,
        "is_praise": 0
    }
}
```

### HTTP Request
`GET api/beautifulImg/details`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    id | integer |  required  | 图集id

<!-- END_8e7e00e0ce9b46f924a5e9d2cd02dbb7 -->

<!-- START_1e86faeb780eb58fca85bb3e8e19b66a -->
## 点赞 api/beautifulImg/praise
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X POST "http://localhost/api/beautifulImg/praise" \
    -H "Content-Type: application/json" \
    -d '{"id":18}'

```
```javascript
const url = new URL("http://localhost/api/beautifulImg/praise");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "id": 18
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": []
}
```

### HTTP Request
`POST api/beautifulImg/praise`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    id | integer |  required  | 图集id

<!-- END_1e86faeb780eb58fca85bb3e8e19b66a -->

<!-- START_fd58d0f21c0e7a959b3bf1aaa27d2332 -->
## 下载 api/beautifulImg/upload
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/beautifulImg/upload" \
    -H "Content-Type: application/json" \
    -d '{"id":1}'

```
```javascript
const url = new URL("http://localhost/api/beautifulImg/upload");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "id": 1
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": []
}
```

### HTTP Request
`GET api/beautifulImg/upload`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    id | integer |  required  | 图集id

<!-- END_fd58d0f21c0e7a959b3bf1aaa27d2332 -->

#阅读
<!-- START_8f80269694f2ff130832482f42a2a68e -->
## 章节目录 api/book/chapters
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/book/chapters" \
    -H "Content-Type: application/json" \
    -d '{"articleid":16}'

```
```javascript
const url = new URL("http://localhost/api/book/chapters");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "articleid": 16
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": [
        {
            "subhead": "开头",
            "is_vip": 0,
            "word_count": 4737,
            "chapterid": 1
        },
        {
            "subhead": "第一章 渭城有雨，少年有侍",
            "is_vip": 0,
            "word_count": 3704,
            "chapterid": 2
        },
        {
            "subhead": "第二章 能书能言穷酸少年",
            "is_vip": 0,
            "word_count": 3631,
            "chapterid": 3
        }
    ]
}
```

### HTTP Request
`GET api/book/chapters`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    articleid | integer |  required  | 书籍id

<!-- END_8f80269694f2ff130832482f42a2a68e -->

<!-- START_35b9dd5ae623c398a5b4e256f77f5d80 -->
## 章节内容 api/book/content
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/book/content" \
    -H "Content-Type: application/json" \
    -d '{"articleid":16,"chapterid":20}'

```
```javascript
const url = new URL("http://localhost/api/book/content");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "articleid": 16,
    "chapterid": 20
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": {
        "subhead": "标题",
        "content": "正文",
        "chapter": 32
    }
}
```
> Example response (200):

```json
{
    "code": 2,
    "msg": "暂未订阅",
    "data": {
        "remain": 0,
        "price": 18,
        "chapter": 32,
        "subhead": "第三十一章 一文钱难死主仆俩（上）"
    }
}
```

### HTTP Request
`GET api/book/content`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    articleid | integer |  required  | 书籍id
    chapterid | integer |  required  | 章节id

<!-- END_35b9dd5ae623c398a5b4e256f77f5d80 -->

<!-- START_5f4b1588035ecea6ea0355ed00126ab5 -->
## 订阅章节 api/book/paid
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X POST "http://localhost/api/book/paid" \
    -H "Content-Type: application/json" \
    -d '{"chapterid":14,"auto_paid":20}'

```
```javascript
const url = new URL("http://localhost/api/book/paid");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "chapterid": 14,
    "auto_paid": 20
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{}
```
> Example response (200):

```json
{
    "code": 0,
    "msg": "已经订阅过了",
    "data": []
}
```

### HTTP Request
`POST api/book/paid`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    chapterid | integer |  required  | 章节id
    auto_paid | integer |  required  | 是否自动订阅 1是 0 否

<!-- END_5f4b1588035ecea6ea0355ed00126ab5 -->

<!-- START_938f607bc813da663f2a9934e15ad72d -->
## 书籍详情 api/book/detail
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/book/detail" \
    -H "Content-Type: application/json" \
    -d '{"articleid":15}'

```
```javascript
const url = new URL("http://localhost/api/book/detail");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "articleid": 15
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": {
        "bookInfo": {
            "title": "将夜",
            "articleid": 1,
            "keyword": [
                "异世"
            ],
            "desc": "简介",
            "author": "猫腻",
            "word_count": 3994319,
            "is_vip": 1,
            "grade": "3.5"
        },
        "comment": [
            {
                "userid": 1,
                "pid": 2,
                "content": "啊哈哈 试一试",
                "username": "咣当",
                "image": "",
                "level": 1
            },
            {
                "userid": 1,
                "pid": 1,
                "content": "哈哈哈 这号",
                "username": "咣当",
                "image": "",
                "level": 1
            }
        ],
        "relate": [
            {
                "image": "http:\/\/127.0.0.1\/\/Users\/wangxin\/Desktop\/test_dir\/15583423582532.jpeg",
                "title": "李莲花夜ya",
                "articleid": 9
            }
        ]
    }
}
```
> Example response (200):

```json
{
    "code": 0,
    "msg": "缺少必要参数",
    "data": []
}
```

### HTTP Request
`GET api/book/detail`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    articleid | integer |  required  | 书籍id

<!-- END_938f607bc813da663f2a9934e15ad72d -->

<!-- START_7925827c533acb23d4845f74e52615c8 -->
## 增加评论 api/book/comment/add
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X POST "http://localhost/api/book/comment/add" \
    -H "Content-Type: application/json" \
    -d '{"articleid":7,"content":"possimus","grade":2,"pid":7}'

```
```javascript
const url = new URL("http://localhost/api/book/comment/add");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "articleid": 7,
    "content": "possimus",
    "grade": 2,
    "pid": 7
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": []
}
```
> Example response (200):

```json
{
    "code": 0,
    "msg": "添加失败",
    "data": []
}
```

### HTTP Request
`POST api/book/comment/add`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    articleid | integer |  required  | 书籍id
    content | string |  required  | 内容
    grade | integer |  optional  | 评分  不是首次评分 可以不传 或者传0 都行
    pid | integer |  optional  | 回复id  (给书评论传0)

<!-- END_7925827c533acb23d4845f74e52615c8 -->

<!-- START_9b192a3846c845479160360ec8b152a9 -->
## 评论点赞 api/book/comment/like
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X POST "http://localhost/api/book/comment/like" \
    -H "Content-Type: application/json" \
    -d '{"pid":19,"type":"quo"}'

```
```javascript
const url = new URL("http://localhost/api/book/comment/like");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "pid": 19,
    "type": "quo"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": []
}
```
> Example response (200):

```json
{
    "code": 0,
    "msg": "添加失败",
    "data": []
}
```

### HTTP Request
`POST api/book/comment/like`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    pid | integer |  required  | 评论id
    type | string |  required  | 类型(订阅和是取消) 1点赞 0取消

<!-- END_9b192a3846c845479160360ec8b152a9 -->

<!-- START_e56d8ce257ef69625ae3a5d4b639fd0e -->
## 评论列表 api/book/comment/commentList
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/book/comment/commentList" \
    -H "Content-Type: application/json" \
    -d '{"articleid":8,"page":19}'

```
```javascript
const url = new URL("http://localhost/api/book/comment/commentList");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "articleid": 8,
    "page": 19
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": {
        "result": [
            {
                "userid": 8,
                "pid": 5,
                "content": "2",
                "username": "用户8",
                "image": "",
                "level": 1,
                "like": 0,
                "comment_num": 0,
                "is_like": 0,
                "create_time": "2019-06-03 03:50:57"
            },
            {
                "userid": 1,
                "pid": 3,
                "content": "啊哈哈 试一试",
                "username": "大哥大",
                "image": "http:\/\/148.70.4.186\/user_logo\/1_lg.jpg",
                "level": 1,
                "like": 0,
                "comment_num": 0,
                "is_like": 0,
                "create_time": "2019-05-29 09:04:41"
            },
            {
                "userid": 1,
                "pid": 1,
                "content": "哈哈哈 这号",
                "username": "大哥大",
                "image": "http:\/\/148.70.4.186\/user_logo\/1_lg.jpg",
                "level": 1,
                "like": 3,
                "comment_num": 4,
                "is_like": 0,
                "create_time": "2019-05-28 02:53:23"
            },
            {
                "userid": 1,
                "pid": 2,
                "content": "啊哈哈 试一试",
                "username": "大哥大",
                "image": "http:\/\/148.70.4.186\/user_logo\/1_lg.jpg",
                "level": 1,
                "like": 0,
                "comment_num": 0,
                "is_like": 0,
                "create_time": "2019-05-28 02:53:23"
            }
        ]
    }
}
```
> Example response (200):

```json
{
    "code": 0,
    "msg": "没有了",
    "data": []
}
```

### HTTP Request
`GET api/book/comment/commentList`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    articleid | integer |  required  | 书id
    page | integer |  required  | 页码 一页10条

<!-- END_e56d8ce257ef69625ae3a5d4b639fd0e -->

<!-- START_75c0c2eaa607eb7a15f6ab8d15fedeba -->
## 评论详情 api/book/comment/info
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X GET -G "http://localhost/api/book/comment/info" \
    -H "Content-Type: application/json" \
    -d '{"pid":3}'

```
```javascript
const url = new URL("http://localhost/api/book/comment/info");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "pid": 3
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": {
        "result": {
            "userid": 1,
            "pid": 1,
            "content": "哈哈哈 这号",
            "username": "大哥大",
            "image": "http:\/\/148.70.4.186\/user_logo\/1_lg.jpg",
            "level": 1,
            "like": 3,
            "comment_num": 4,
            "is_like": 0,
            "create_time": "2019-05-28 02:53:23",
            "list": [
                {
                    "userid": 1,
                    "pid": 4,
                    "content": "啊哈哈 试一试",
                    "username": "大哥大",
                    "image": "http:\/\/148.70.4.186\/user_logo\/1_lg.jpg",
                    "level": 1,
                    "like": 0,
                    "comment_num": 0,
                    "is_like": 0,
                    "create_time": "2019-06-01 05:54:24"
                },
                {
                    "userid": 1,
                    "pid": 6,
                    "content": "啊哈哈 试一试",
                    "username": "大哥大",
                    "image": "http:\/\/148.70.4.186\/user_logo\/1_lg.jpg",
                    "level": 1,
                    "like": 0,
                    "comment_num": 0,
                    "is_like": 0,
                    "create_time": "2019-06-04 14:44:51"
                },
                {
                    "userid": 1,
                    "pid": 7,
                    "content": "啊哈哈 试一试",
                    "username": "大哥大",
                    "image": "http:\/\/148.70.4.186\/user_logo\/1_lg.jpg",
                    "level": 1,
                    "like": 0,
                    "comment_num": 0,
                    "is_like": 0,
                    "create_time": "2019-06-04 14:45:07"
                },
                {
                    "userid": 1,
                    "pid": 8,
                    "content": "啊哈哈 试一试",
                    "username": "大哥大",
                    "image": "http:\/\/148.70.4.186\/user_logo\/1_lg.jpg",
                    "level": 1,
                    "like": 0,
                    "comment_num": 0,
                    "is_like": 0,
                    "create_time": "2019-06-04 14:47:26"
                }
            ]
        }
    }
}
```
> Example response (200):

```json
{
    "code": 0,
    "msg": "没有了",
    "data": []
}
```

### HTTP Request
`GET api/book/comment/info`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    pid | integer |  required  | 书id

<!-- END_75c0c2eaa607eb7a15f6ab8d15fedeba -->

<!-- START_d8518c296b46f6c85f8cdcc1d6fc08f0 -->
## 自动订阅 api/book/autoPaid
成功返回1 失败返回0+msg

> Example request:

```bash
curl -X POST "http://localhost/api/book/autoPaid" \
    -H "Content-Type: application/json" \
    -d '{"articleid":14,"type":"nihil"}'

```
```javascript
const url = new URL("http://localhost/api/book/autoPaid");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "articleid": 14,
    "type": "nihil"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "code": 1,
    "msg": "success",
    "data": []
}
```
> Example response (200):

```json
{
    "code": 0,
    "msg": "添加失败",
    "data": []
}
```

### HTTP Request
`POST api/book/autoPaid`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    articleid | integer |  required  | 书籍id
    type | string |  required  | 类型(订阅和是取消) 1订阅 0取消

<!-- END_d8518c296b46f6c85f8cdcc1d6fc08f0 -->


