销售记录模块
========
* [按开始和结束时间搜索](#按开始和结束时间搜索)
* [根据开始和结束时间获取利润列表](#根据开始和结束时间获取利润列表)
* [根据开始和结束时间获取成本列表](#根据开始和结束时间获取成本列表)
* [根据开始和结束时间获取商品列表](#根据开始和结束时间获取商品列表)

####按开始和结束时间搜索

接口:`salesreport/index`或`salesreport/`
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
        <td>start</td>
        <td>String</td>
        <td>开始时间</td>
        <td>YYYY-mm-dd</td>
        <td>No</td>
        <td>年月日</td>
    </tr>
    <tr>
        <td>end</td>
        <td>String</td>
        <td>结束时间</td>
        <td>YYYY-mm-dd</td>
        <td>No</td>
        <td>年月日</td>
    </tr>
    <tr>
        <td>limit</td>
        <td>int</td>
        <td>每页输出的数据量</td>
        <td>默认为10</td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>sort</td>
        <td>int</td>
        <td>排序指令</td>
        <td>
          1为日期倒序(默认)<br />
          2为日期升序<br />
        </td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>pageNum</td>
        <td>int</td>
        <td>页码</td>
        <td>默认为1</td>
        <td>Yes</td>
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
        <td>totalCost</td>
        <td>Float</td>
        <td>总成本</td>
        <td></td>
        <td>No</td>
        <td></td>
    </tr>
    <tr>
        <td>totalCount</td>
        <td>Float</td>
        <td>总销售量</td>
        <td></td>
        <td>No</td>
        <td></td>
    </tr>
    <tr>
        <td>totalPrice</td>
        <td>Float</td>
        <td>总销售额</td>
        <td></td>
        <td>No</td>
        <td></td>
    </tr>
</table>

####根据开始和结束时间获取利润列表

接口:`salesreport/index`或`salesreport/`
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
        <td>reportType</td>
        <td>String</td>
        <td>报表类型</td>
        <td>profits</td>
        <td>No</td>
        <td></td>
    </tr>
    <tr>
        <td>start</td>
        <td>String</td>
        <td>开始时间</td>
        <td>YYYY-mm-dd</td>
        <td>No</td>
        <td>年月日</td>
    </tr>
    <tr>
        <td>end</td>
        <td>String</td>
        <td>结束时间</td>
        <td>YYYY-mm-dd</td>
        <td>No</td>
        <td>年月日</td>
    </tr>
    <tr>
        <td>limit</td>
        <td>int</td>
        <td>每页输出的数据量</td>
        <td>默认为10</td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>sort</td>
        <td>int</td>
        <td>排序指令</td>
        <td>
          1为日期倒序(默认)<br />
          2为日期升序<br />
          3为利润倒序<br />
          4为利润升序<br />
        </td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>pageNum</td>
        <td>int</td>
        <td>页码</td>
        <td>默认为1</td>
        <td>Yes</td>
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
        <td>date</td>
        <td>String</td>
        <td>日期</td>
        <td>yyyy-MM-dd</td>
        <td>No</td>
        <td></td>
    </tr>
    <tr>
        <td>profit</td>
        <td>Float</td>
        <td>利润</td>
        <td></td>
        <td>No</td>
        <td></td>
    </tr>
    <tr>
        <td>id</td>
        <td>Int</td>
        <td>销售记录id</td>
        <td></td>
        <td>No</td>
        <td></td>
    </tr>
</table>


####根据开始和结束时间获取成本列表

接口:`salesreport/index`或`salesreport/`
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
        <td>reportType</td>
        <td>String</td>
        <td>报表类型</td>
        <td>costs</td>
        <td>No</td>
        <td></td>
    </tr>
    <tr>
        <td>start</td>
        <td>String</td>
        <td>开始时间</td>
        <td>YYYY-mm-dd</td>
        <td>No</td>
        <td>年月日</td>
    </tr>
    <tr>
        <td>end</td>
        <td>String</td>
        <td>结束时间</td>
        <td>YYYY-mm-dd</td>
        <td>No</td>
        <td>年月日</td>
    </tr>
    <tr>
        <td>limit</td>
        <td>int</td>
        <td>每页输出的数据量</td>
        <td>默认为10</td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>sort</td>
        <td>int</td>
        <td>排序指令</td>
        <td>
          1为日期倒序(默认)<br />
          2为日期升序<br />
          3为成本倒序<br />
          4为成本升序<br />
        </td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>pageNum</td>
        <td>int</td>
        <td>页码</td>
        <td>默认为1</td>
        <td>Yes</td>
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
        <td>date</td>
        <td>String</td>
        <td>日期</td>
        <td>yyyy-MM-dd</td>
        <td>No</td>
        <td></td>
    </tr>
    <tr>
        <td>cost</td>
        <td>Float</td>
        <td>成本</td>
        <td></td>
        <td>No</td>
        <td></td>
    </tr>
    <tr>
        <td>id</td>
        <td>Int</td>
        <td>销售记录id</td>
        <td></td>
        <td>No</td>
        <td></td>
    </tr>
</table>

####根据开始和结束时间获取商品列表

接口:`salesreport/index`或`salesreport/`
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
        <td>reportType</td>
        <td>String</td>
        <td>报表类型</td>
        <td>products</td>
        <td>No</td>
        <td></td>
    </tr>
    <tr>
        <td>start</td>
        <td>String</td>
        <td>开始时间</td>
        <td>YYYY-mm-dd</td>
        <td>No</td>
        <td>年月日</td>
    </tr>
    <tr>
        <td>end</td>
        <td>String</td>
        <td>结束时间</td>
        <td>YYYY-mm-dd</td>
        <td>No</td>
        <td>年月日</td>
    </tr>
    <tr>
        <td>limit</td>
        <td>int</td>
        <td>每页输出的数据量</td>
        <td>默认为10</td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>sort</td>
        <td>int</td>
        <td>排序指令</td>
        <td>
          1为日期倒序(默认)<br />
          2为日期升序<br />
        </td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>pageNum</td>
        <td>int</td>
        <td>页码</td>
        <td>默认为1</td>
        <td>Yes</td>
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
        <td>销售记录id</td>
        <td></td>
        <td>No</td>
        <td></td>
    </tr>
    <tr>
        <td>date</td>
        <td>String</td>
        <td>销售记录日期</td>
        <td></td>
        <td>No</td>
        <td></td>
    </tr>
    <tr>
        <td>isMerge</td>
        <td>Int</td>
        <td>是否是合并类型的销售记录</td>
        <td>0 or 1</td>
        <td>No</td>
        <td>1是合并记账，0是单笔记账</td>
    </tr>
    <tr>
        <td>name</td>
        <td>String</td>
        <td>商品名称</td>
        <td>当`isMerge`为0时，name才会返回</td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>pic</td>
        <td>String</td>
        <td>商品图片</td>
        <td>当`isMerge`为0时，pic才会返回</td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>typeName</td>
        <td>Map</td>
        <td>商品分类名称</td>
        <td>当`isMerge`为0时，typeName才会返回</td>
        <td>Yes</td>
        <td>请注意：typeName有可能是空字符串</td>
    </tr>
    <tr>
        <td>selling_count</td>
        <td>Float</td>
        <td>单笔销售记录的销售数量</td>
        <td>当`isMerge`为0时，selling_count才会返回</td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>selling_price</td>
        <td>Float</td>
        <td>单笔销售记录的销售价格</td>
        <td>当`isMerge`为0时，selling_price才会返回</td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>price</td>
        <td>Float</td>
        <td>商品销售时的进货价格</td>
        <td>当`isMerge`为0时，price才会返回</td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>remark</td>
        <td>String</td>
        <td>销售记录备注</td>
        <td>当`isMerge`为0时，remark才会返回</td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>cashierList</td>
        <td>Array</td>
        <td>合并记账列表</td>
        <td>当`isMerge`为1时，cashierList才会返回</td>
        <td>Yes</td>
        <td>`cashierList`中每一项的数据结构和isMerge为0的单笔记账数据结构是一样的</td>
    </tr>
    <tr>
        <td>products</td>
        <td>Array</td>
        <td>合并记账商品列表</td>
        <td>当`isMerge`为1时，products才会返回</td>
        <td>Yes</td>
        <td></td>
    </tr>
    <tr>
        <td>totalCount</td>
        <td>Float</td>
        <td>总销售量</td>
        <td>当`isMerge`为1时，totalCount才会返回</td>
        <td>No</td>
        <td></td>
    </tr>
    <tr>
        <td>totalPrice</td>
        <td>Float</td>
        <td>总销售额</td>
        <td>当`isMerge`为1时，totalPrice才会返回</td>
        <td>No</td>
        <td></td>
    </tr>
</table>
```javascript
//输出数据
{
    "public": {
        "updateUrl": "",
        "updateTitle": "",
        "resultStatus": 100,
        "memo": "查询成功"
    },
    "operation": {
        "products": [
            {
                "isMerge": 1,
                "id": "39",
                "totalSalePrice": 48,
                "totalSaleCount": 8,
                "date": "2015-06-01 00:00:00",
                "cashierList": [//合并记账清单
                    {
                        "id": "1884",
                        "user_id": "125",
                        "pid": "696",
                        "selling_count": "2",
                        "selling_price": "3",
                        "who": null,
                        "date": "2013-03-09 06:06:00",
                        "remark": "4",
                        "merge_id": "39",
                        "price": "1"
                    },
                    {
                        "id": "1885",
                        "user_id": "125",
                        "pid": "697",
                        "selling_count": "6",
                        "selling_price": "7",
                        "who": null,
                        "date": "2013-03-09 06:06:00",
                        "remark": "8",
                        "merge_id": "39",
                        "price": "5"
                    }
                ],
                "products": [//合并记账清单中的所有售出商品
                    {
                        "pic": "",
                        "pid": "696",
                        "name": "我得到，",
                        "typeName": {}//没有分类名称
                    },
                    {
                        "pic": "",
                        "pid": "697",
                        "name": "女女",
                        "typeName": {//有大分类、小分类
                            "parent": "默认分类112",
                            "child": "默认分类112的子分类"
                        },
                    }
                ]
            },
            {
                "isMerge": 0,//单笔记账
                "pic": "",
                "pid": "702",
                "id": "1898",
                "name": "经历",
                "typeName": {},//没有分类名称
                "selling_count": "5",
                "selling_price": "5",
                "price": "58",
                "date": "2015-06-01 00:00:00",
                "remark": ""
            },
            {
                "isMerge": 1,
                "id": "42",
                "totalSalePrice": 0,
                "totalSaleCount": 0,
                "date": "2015-06-02 02:02:00",
                "cashierList": [],//合并记账没有销售清单
                "products": []//合并记账没有相关的售出商品
            },
            {
                "isMerge": 0,
                "pic": "http://192.168.1.101/uploadfiles/125/l_94d63f2e104b39fa7fa1eb96375f1713.png",
                "pid": "639",
                "id": "1897",
                "name": "金融危机",
                "typeName": {
                    "parent": "默认分类112"
                },
                "selling_count": "1",
                "selling_price": "2",
                "price": "24678",
                "date": "2015-06-21 10:44:33",
                "remark": ""
            }
        ]
    }
}
```