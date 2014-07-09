产品模块
========
* [搜索商品](#搜索商品)
* [添加商品](#添加商品)
* [修改商品](#修改商品)
* [删除商品](#删除商品)
* [获取商品列表](#获取商品列表)
* [根据商品id查询商品信息](#根据商品id查询商品信息)

####搜索商品
接口:`products/search`
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
        <td>keyword</td>
        <td>String</td>
        <td>商品名称关键字</td>
        <td></td>
        <td>No</td>
        <td></td>
    </tr>
    <tr>
        <td>pageNum</td>
        <td>int</td>
        <td>页码</td>
        <td></td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>limit</td>
        <td>int</td>
        <td>每页输出的数据量</td>
        <td></td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>sort</td>
        <td>int</td>
        <td>排序指令</td>
        <td>
          1为入库日期倒序(默认)<br />
          2为入库日期升序<br />
          3为进货价倒序<br />
          4为进货价升序<br />
        </td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>type</td>
        <td>int</td>
        <td>分类id</td>
        <td></td>
        <td>Yes</td>
        <td>按分类查询商品</td>
    </tr>
</table>
######出参
见[公共参数](Public.md)

####添加商品
接口:`products/create`
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
        <td>name</td>
        <td>String</td>
        <td>商品名称</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>count</td>
        <td>String</td>
        <td>商品数量(可为浮点数)</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>price</td>
        <td>String</td>
        <td>进货价</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>type</td>
        <td>int</td>
        <td>所属分类id</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>date</td>
        <td>datetime</td>
        <td>日期</td>
        <td></td>
        <td>NO</td>
        <td>默认为系统时间</td>
    </tr>
    <tr>
        <td>remark</td>
        <td>String</td>
        <td>备注信息</td>
        <td></td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>file1</td>
        <td>Stream</td>
        <td>文件流</td>
        <td></td>
        <td>Yes</td>
        <td>商品图片文件流</td>
    </tr>
</table>
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
        <td>id</td>
        <td>int</td>
        <td>商品id</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>name</td>
        <td>String</td>
        <td>商品名称</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>price</td>
        <td>String</td>
        <td>进货价</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>count</td>
        <td>String</td>
        <td>本次进货数量</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>date</td>
        <td>datetime</td>
        <td>商品添加完成时间</td>
        <td></td>
        <td>No</td>
        <td></td>
    </tr>
    <tr>
        <td>pic</td>
        <td>String</td>
        <td>商品图片地址</td>
        <td></td>
        <td>No</td>
        <td>如果没有上传商品图片,则返回空字符串</td>
    </tr>
    <tr>
        <td>type</td>
        <td>map</td>
        <td>所属分类</td>
        <td></td>
        <td>NO</td>
        <td>
        1. 如果当前分类是小分类,则返回parent和child的分类名称<br />
        2. 如果当前分类是大分类,则只返回parent分类名称
        <pre style="background:#f50;color:#fff;">
        "type": {
                    "child": "日本风格柜子",
                    "parent": "柜子"
                }
        </pre>
        </td>
    </tr>
</table>
For example:
```javascript
//商品添加成功
{
    "public": {
        "updateUrl": "",
        "updateTitle": "",
        "resultStatus": 100,
        "memo": "商品添加成功"
    },
    "operation": {
        "id": "550",
        "name": "iPhone5s",
        "count": "0.788",
        "price": "1.6",
        "pic": "http://api.jizhangbao.com.cn/uploadfiles/106/3249ifkeiweo.png",
        "date": "2014-04-23 10:48:29",
        "type": {
            "child": "柜子3",
            "parent": "柜子2"
        }
    }
}
//商品添加失败
{
    "public": {
        "updateUrl": "",
        "updateTitle": "",
        "resultStatus": 400,
        "memo": "商品添加失败"
    },
    "operation": {
        "price": [
            "进货价不正确"
        ]
    }
}
```

####修改商品
接口:`products/update`
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
        <td>id</td>
        <td>int</td>
        <td>商品id</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>name</td>
        <td>String</td>
        <td>商品名称</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>count</td>
        <td>String</td>
        <td>商品数量(可为浮点数)</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>price</td>
        <td>String</td>
        <td>进货价</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>type</td>
        <td>int</td>
        <td>所属分类id</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>date</td>
        <td>datetime</td>
        <td>日期</td>
        <td></td>
        <td>NO</td>
        <td>默认为系统时间</td>
    </tr>
    <tr>
        <td>file1</td>
        <td>Stream</td>
        <td>文件流</td>
        <td></td>
        <td>Yes</td>
        <td>商品图片文件流</td>
    </tr>
</table>
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
        <td>id</td>
        <td>int</td>
        <td>商品id</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>name</td>
        <td>String</td>
        <td>商品名称</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>price</td>
        <td>String</td>
        <td>进货价</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>count</td>
        <td>String</td>
        <td>修改后的库存数量</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>date</td>
        <td>datetime</td>
        <td>最后修改时间</td>
        <td></td>
        <td>No</td>
        <td></td>
    </tr>
    <tr>
        <td>pic</td>
        <td>String</td>
        <td>商品图片地址</td>
        <td></td>
        <td>No</td>
        <td>如果没有上传商品图片,则返回空字符串</td>
    </tr>
    <tr>
        <td>typeId</td>
        <td>int</td>
        <td>分类id</td>
        <td></td>
        <td>No</td>
        <td></td>
    </tr>
    <tr>
        <td>uid</td>
        <td>int</td>
        <td>商品所属的user_ud</td>
        <td></td>
        <td>No</td>
        <td></td>
    </tr>
    <tr>
        <td>type</td>
        <td>map</td>
        <td>所属分类的名称</td>
        <td></td>
        <td>NO</td>
        <td>
        1. 如果当前分类是小分类,则返回parent和child的分类名称<br />
        2. 如果当前分类是大分类,则只返回parent分类名称
        <pre style="background:#f50;color:#fff;">
        "type": {
                    "child": "日本风格柜子",
                    "parent": "柜子"
                }
        </pre>
        </td>
    </tr>
</table>

####删除商品
接口:`products/delete`
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
        <td>id</td>
        <td>int</td>
        <td>商品id</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
</table>
######出参
见[公共参数](Public.md)

####获取商品列表
接口:`products/index`或`products`
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
        <td>pageNum</td>
        <td>int</td>
        <td>页码</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>limit</td>
        <td>int</td>
        <td>每页输出的数据量</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>sort</td>
        <td>int</td>
        <td>排序指令</td>
        <td>
          1为入库日期倒序<br />
          2为入库日期升序<br />
          3为进货价倒序<br />
          4为进货价升序<br />
        </td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>type</td>
        <td>int</td>
        <td>分类id</td>
        <td></td>
        <td>Yes</td>
        <td>按分类查询商品</td>
    </tr>
</table>
######出参
见[公共参数](Public.md)

####根据商品id查询商品信息
接口:`products/query`
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
        <td>id</td>
        <td>int</td>
        <td>商品id</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
</table>
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
        <td>id</td>
        <td>int</td>
        <td>商品id</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>name</td>
        <td>String</td>
        <td>商品名称</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>price</td>
        <td>String</td>
        <td>进货价</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>count</td>
        <td>String</td>
        <td>本次进货数量</td>
        <td></td>
        <td>NO</td>
        <td></td>
    </tr>
    <tr>
        <td>date</td>
        <td>datetime</td>
        <td>商品添加完成时间</td>
        <td></td>
        <td>No</td>
        <td></td>
    </tr>
    <tr>
        <td>pic</td>
        <td>String</td>
        <td>商品图片地址</td>
        <td></td>
        <td>No</td>
        <td>如果没有上传商品图片,则返回空字符串</td>
    </tr>
    <tr>
        <td>typeId</td>
        <td>int</td>
        <td>分类id</td>
        <td></td>
        <td>No</td>
        <td></td>
    </tr>
    <tr>
        <td>uid</td>
        <td>int</td>
        <td>商品所属的user_ud</td>
        <td></td>
        <td>No</td>
        <td></td>
    </tr>
    <tr>
        <td>type</td>
        <td>map</td>
        <td>所属分类的名称</td>
        <td></td>
        <td>NO</td>
        <td>
        1. 如果当前分类是小分类,则返回parent和child的分类名称<br />
        2. 如果当前分类是大分类,则只返回parent分类名称
        <pre style="background:#f50;color:#fff;">
        "type": {
                    "child": "日本风格柜子",
                    "parent": "柜子"
                }
        </pre>
        </td>
    </tr>
    <tr>
        <td>remark</td>
        <td>String</td>
        <td>商品描述</td>
        <td></td>
        <td>Yes</td>
        <td></td>
    </tr>
</table>
