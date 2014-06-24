小店记账宝API文档
========
#####重要说明
1、 数据格式使用`JSON`,请求方式使用`POST`

2、 每次访问服务端必须要传入公共参数和当前业务参数，公共参数的key为public，业务参数的key为operation，完整的公共参数如下：
```javascript
{
    "productVersion": "产品版本", 
    "productId": "iphone/ipad/android/wp", 
    "channelId": "百度助手/91助手", 
    "network": "2g/3g/4g/wifi", 
    "display": "low/middle/high", 
    "sign": "509ogojpotr94984598",//签名
    "time": "432143214", //时间戳
    "pushToken": "用于推送消息的token", //由客户端提供的pushToken
    "cookie": "", //由服务端返回的cookie
    "userId": ""
}
```
公共参数和业务参数全部放在requestData对象里，序列化后传入服务端，因此一个完整的请求入参如下：
```javascript
var requestData = {
          public: {
            "productVersion": "产品版本", 
            "productId": "iphone/ipad/android/wp", 
            "channelId": "百度助手/91助手", 
            "network": "2g/3g/4g/wifi", 
            "display": "low/middle/high", 
            "sign": "509ogojpotr94984598",//签名
            "time": "432143214", //时间戳
            "pushToken": "用于推送消息的token", //由客户端提供的pushToken
            "cookie": "", //由服务端返回的cookie
            "userId": ""
          },
          operation: {}//业务相关的参数
}
```

3、签名方案：`sign=md5(operation+time+localkey)`，localkey由客户端提供

4、 每次请求返回的公共参数和业务参数同样分别由public和operation构成，一个完整的出参如下：
```javascript
{
    "public": {
        "resultStatus": "100", 
        "memo": "本次请求的处理结果", 
        "updateUrl": "", //应用升级地址
        "updateTitle": ""//应用升级日志
    }, 
    "operation": { }//业务相关的出参
}
```

#####API列表
* [公共参数列表](Public.md)
* [User模块(注册/登录/获取用户信息等)](User.md)
* [商品分类模块](Type.md)
* [文件上传](Upload.md)
* [产品模块](Products.md)
* [记账台](Cashier.md)
* [帮助](Help.md)
* [反馈](Feedback.md)