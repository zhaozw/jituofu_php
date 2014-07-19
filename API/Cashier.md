记账台模块
========
* [新增一条记账](#新增记账)

####新增记账
接口:`cashier/create`

新增记账时,在公共参数`operation`中，通过`list`传入，`list`的值为数组类型,数组中的每一项为一条商品销售记录,如果该数组的长度大于2,系统自动识别为合并记账.
######入参
<table>
    <tr>
        <td>参数名称</td>
        <td>数据类型</td>
        <td>描述</td>
        <td>取值</td>
        <td>参数值是否可空</td>
        <td>其它</td>
    </tr>
    <tr>
        <td>list</td>
        <td>Array</td>
        <td>记账清单</td>
        <td></td>
        <td>No</td>
        <td></td>
    </tr>
    <tr>
        <td>date</td>
        <td>datetime</td>
        <td>日期</td>
        <td></td>
        <td>Yes</td>
        <td>默认为系统时间</td>
    </tr>
</table>
######list模型
<table>
    <tr>
        <td>参数名称</td>
        <td>数据类型</td>
        <td>描述</td>
        <td>取值</td>
        <td>参数值是否可空</td>
        <td>其它</td>
    </tr>
    <tr>
        <td>sellingCount</td>
        <td>String</td>
        <td>销售数量(可为浮点数)</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>sellingPrice</td>
        <td>String</td>
        <td>销售价格</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>pid</td>
        <td>int</td>
        <td>商品id</td>
        <td></td>
        <td>Yes</td>
        <td>1. 如果该商品是从仓库里选择的话,则该字段必须传入<br />2. 如果传入该字段,name和price可为空</td>
    </tr>
    <tr>
        <td>name</td>
        <td>String</td>
        <td>商品名称</td>
        <td></td>
        <td>Yes</td>
        <td>1. 如果该商品不是从仓库里选择的话,则该字段必须传入<br />2. 如果pid传入的,该字段可为空</td>
    </tr>
    <tr>
        <td>price</td>
        <td>String</td>
        <td>商品进货价</td>
        <td></td>
        <td>Yes</td>
        <td>和name字段的逻辑一样</td>
    </tr>
    <tr>
        <td>remark</td>
        <td>String</td>
        <td>备注信息</td>
        <td></td>
        <td>Yes</td>
        <td></td>
    </tr>
</table>
For example:
```javascript
//合并记账入参
[
    {//从仓库中选择的商品
        "pid": 109,
        "sellingPrice": "12.43",
        "remark": "这件商品是卖给王老五的",
        "count": "432.43",
        "date": "2014-04-23 11:31:44"
    },
    {//手动输入的商品信息
        "name": "全新的iPhone 4S",
        "price": "12.43",
        "sellingPrice": "34",
        "remark": "",
        "count": "34.54",
        "date": "2010-12-30 12:00:00"
    }
]
//单笔记账入参
[
    {//手动输入的商品信息
        "name": "全新的iPhone 4S",
        "price": "12.43",
        "sellingPrice": "34",
        "remark": "",
        "count": "34.54",
        "date": "2010-12-30 12:00:00"
    }
]
```
######出参
<table>
    <tr>
        <td>参数名称</td>
        <td>数据类型</td>
        <td>描述</td>
        <td>取值</td>
        <td>参数值是否可空</td>
        <td>其它</td>
    </tr>
    <tr>
        <td>which</td>
        <td>int</td>
        <td>销售记录序号</td>
        <td></td>
        <td>Yes</td>
        <td>表明当前的错误是属于哪条销售记录的?<br />适用于合并记账时提示用户</td>
    </tr>
</table>
For example:
```javascript
{
    "public": {
        "updateUrl": "",
        "updateTitle": "",
        "resultStatus": 400,
        "memo": "销售数量不正确"
    },
    "operation": {
        "which": 1
    }
}
```