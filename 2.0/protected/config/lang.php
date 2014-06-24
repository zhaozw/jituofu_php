<?php
/*
本地化错误消息
*/
return array(
    //账户相关
    "ACCOUNT_SPECIFY_USERNAME" 		=> "请填写用户名",
    "ACCOUNT_SPECIFY_PASSWORD" 		=> "请填写登录密码",
    "ACCOUNT_SPECIFY_CPASSWORD" => "请再次填写登录密码",
    "ACCOUNT_SPECIFY_EMAIL"			=> "请填写常用邮箱，比如QQ邮箱",
    "ACCOUNT_INVALID_EMAIL"			=> "邮箱不正确",
    "ACCOUNT_USER_OR_EMAIL_INVALID"		=> "用户名或邮箱不正确",
    "ACCOUNT_USER_OR_PASS_INVALID"		=> "用户名或登录密码不正确",
    "ACCOUNT_USER_CHAR_LIMIT"		=> "用户名必须是 %m1% 到 %m2% 位中英文或数字",
    "ACCOUNT_EMAIL_NOTHING"   => "邮箱没有发生变化,因此不更新",
    "ACCOUNT_PASS_CHAR_LIMIT"		=> "登录密码必须是 %m1% 到 %m2% 位",
    "ACCOUNT_PASS_MISMATCH"			=> "2次输入的登录密码不一致",
    "ACCOUNT_USERNAME_IN_USE"		=> "%m1% 已被注册，请更换",
    "ACCOUNT_EMAIL_IN_USE"			=> "%m1% 已被使用，请更换",
    "ACCOUNT_NEW_PASSWORD_LENGTH"		=> "新的登录密码必须是 %m1% 到 %m2% 位中英文或数字",
    "ACCOUNT_DETAILS_UPDATED"		=> "账户已更新",
    "ACCOUNT_PASSWORD_NOTHING_TO_UPDATE"	=> "新密码与旧密码相同，更新失败",
    "ACCOUNT_PASSWORD_UPDATED"		=> "更新成功,请重新登录",
    "ACCOUNT_INVALID_USERNAME"		=> "无效的用户名",
    "ACCOUNT_INVALID_CURRENT_PASSWORD" => "当前登录密码不正确",
    "ACCOUNT_PASSWORD_NO_EQUAL"	=> "两次输入的密码不一致",


    //找回密码相关
    "FORGOT_PASS_ERROR"		=> "找回密码失败",
    "FORGOT_PASS_NEW_PASS_EMAIL_SUCCESS"		=> "登录密码设置成功",
    "FORGOT_PASS_NEW_PASS_EMAIL_ERROR" => "登录密码设置失败",
    "FORGOT_PASS_REQUEST_SUCCESS"		=> "校验码已经发送到您的邮箱，请按照邮件里的步骤重新设置登录密码",

    //验证码
    "CHECKCODE_ERROR" => "校验码不正确",
    "CHECKCODE_SPECIFY" => "缺少校验码参数",
    "CHECKCODE_INVALID" => "校验码已过期",

    //邮件标题
    "EMAILTITLE_REGISTER_SUCCESS" => "注册成功",
    "EMAILTITLE_SECURITY_TIPS" => "重要的安全提醒",
    "EMAILTITLE_USERINFO_UPDATE_SUCCESS" => "更新用户信息成功",

    //memo
    "MEMO_REGISTER_LOGIN_SUCCESS" => "注册并登录成功",
    "MEMO_LOGOUT_SUCCESS" => "您已安全退出",
    "MEMO_GET_USER_INFO_SUCCESS" => "成功获取用户信息",
    "MEMO_NO_CLIENTID" => "缺少clientId参数",
    "MEMO_NO_USERID" => "缺少userId参数",
    "MEMO_NO_COOKIE" => "缺少cookie参数",
    "MEMO_NO_REQUESTDATA" => "缺少requestData参数",
    "MEMO_NO_OLDPASSWORD" => "缺少old password参数",
    "MEMO_NO_PUBLIC" => "缺少public参数",
    "MEMO_NO_OPERATION" => "缺少operation参数",
    "MEMO_NO_PRODUCTVERSION" => "缺少productVersion参数",
    "MEMO_NO_PRODUCTID" => "缺少productId参数",
    "MEMO_NO_NETWORK" => "缺少network参数",
    "MEMO_NO_DISPLAY" => "缺少display参数",
    "MEMO_NO_SIGN" => "缺少sign参数",
    "MEMO_NO_TIME" => "缺少time参数",
    "MEMO_DONOT_PARSE_REQUESTDATA" => "无法解析requestData的数据格式",
    "MEMO_REGISTER_ERROR" => "注册失败",
    "MEMO_LOGIN_EXCEPTION" => "登录时发生异常",
    "MEMO_NOEXIST_USER" => "用户不存在",
    "MEMO_USERINFO_UPDATE_ERROR" => "更新用户信息失败",
    "MEMO_SIGN_INVALID" => "签名验证失败",
    "MEMO_USER_VERIFY_ERROR" => "用户验证失败",
    "MEMO_NO_LOGIN" => "当前用户未登录",

    //商品分类
    "TYPE_NAME_CHAR_LIMIT" => "分类名称必须是 %m1% 到 %m2% 位中英文或数字",
    "TYPE_NAME_SPECIFY" => "请填写分类名称",
    "TYPE_ID_SPECIFY" => "请填写分类id",
    "TYPE_NAME__IN_USE" => "分类名称已存在,请更换",
    "TYPE_CREATE_SUCCESS" => "添加分类成功",
    "TYPE_CREATE_ERROR" => "添加分类失败",
    "TYPE_NO_EXIST_PARENT" => "大分类不存在",
    "TYPE_NO_EXIST" => "当前分类不存在",
    "TYPE_NAME_NOTHING_TO_UPDATE" => "新分类名称和旧分类相同,修改失败",
    "TYPE_UPDATE_ERROR" => "分类修改失败",
    "TYPE_UPDATE_SUCCESS" => "分类修改成功",
    "TYPE_DELETE_ERROR" => "分类删除失败",
    "TYPE_DELETE_SUCCESS" => "分类已删除",
    "TYPE_PARENT_FORMAT_ERROR" => "大分类格式错误",
    "TYPE_PARENT_EQUAL_CHILD" => "大分类id和小分类id相同",
    "TYPE_PARENT_EQUAL_TARGETID" => "大分类id和目标分类id相同",
    "TYPE_CHILDID_EQUAL_TARGETID" => "当前分类id和目标分类id相同",
    "TYPE_NO_EXIST_TARGET" => "目标分类不存在",
    "TYPE_PARENT_CHILD_DELETE_PRODUCTS_SUCCESS" => "大分类和该大分类下的所有小分类及所有商品已删除",
    "TYPE_CHILD_DELETE_PRODUCTS_SUCCESS" => "小分类及其所有商品已删除",
    "TYPE_CHILD_DELETE_AND_MOVE_PRODUCTS_SUCCESS" => "小分类已删除,商品已移动",
    "TYPE_PARENT_CHILD_DELETE_SUCCESS" => "大分类和其下所有小分类已删除",
    "TYPE_TO_SPECIFY" => "请指定小分类要移动的位置",
    "TYPE_CHILD_MOVE_ERROR" => "移动小分类失败",
    "TYPE_TARGETPARENT_NO_EXIST" => "要移动到的大分类不存在",
    "TYPE_PARENT_CHILD_MOVE_SUCCESS" => "大分类删除,小分类移动成功",
    "TYPE_PARENT_DETAIL_SUCCESS" => "大分类详情查询成功",

    //商品
    "PRODUCT_NAME_SPECIFY" => "请填写商品名称",
    "PRODUCT_CHAR_LIMIT" => "商品名称必须是 %m1% 到 %m2% 位中英文或数字",
    "PRODUCT_PRICE_SPECIFY" => "请填写进货价",
    "PRODUCT_COUNT_SPECIFY" => "请填写商品数量",
    "PRODUCT_ADD_ERROR" => "商品添加失败",
    "PRODUCT_ADD_SUCCESS" => "商品添加成功",
    "PRODUCT_UPDATE_ERROR" => "商品修改失败",
    "PRODUCT_UPDATE_SUCCESS" => "商品修改成功",
    "PRODUCT_COUNT_INVALID" => "商品数量不正确",
    "PRODUCT_PRICE_INVALID" => "进货价不正确",
    "PRODUCT_TYPE_SPECIFY" => "请选择商品分类",
    "PRODUCT_NAME_IN_USE" => "商品名称重复,请更换",
    "PRODUCT_ID_SPECIFY" => "请指定商品id",
    "PRODUCT_ID_INVALID" => "无效的商品id",
    "PRODUCT_NO_EXIST" => "商品不存在",
    "PRODUCT_DELETED_SUCCESS" => "商品删除成功",
    "PRODUCT_DELETED_ERROR" => "商品删除失败",

    //上传图片
    "UPLOAD_MAX_FILE_SIZE" => "文件超过10兆",
    "UPLOAD_FILE_INVALID" => "文件无效",
    "UPLOAD_IMAGE_WIDTH_HEIGHT_INVALID" => "无法识别图片尺寸，请输出RGB格式的图像文件",
    "UPLOAD_IMAGE_SUCCESS" => "商品图片上传成功",
    "UPLOAD_IMAGE_ERROR" => "商品图片上传失败",
    "UPLOAD_WIDTH_HEIGHT_MIN" => "图片尺寸太小",
    "UPLOAD_FILE_SPECIFY" => "请提交图片文件",
    "UPLOAD_DIR_SPECIFY" => "请指定上传目录",

    //记账台
    "CASHIER_NOT_IS_ARRAY" => "只接收数组",
    "CASHIER_EMPTY_DATA" => "空数据",
    "CASHIER_COUNT_SPECIFY" => "请填写销售数量",
    "CASHIER_COUNT_INVALID" => "销售数量不正确",
    "CASHIER_SELLINGPRICE_SPECIFY" => "请填写销售价格",
    "CASHIER_SELLINGPRICE_INVALID" => "销售价格不正确",
    "CASHIER_MERGERCASHIER_ERROR" => "合并记账失败",
    "CASHIER_MERGERCASHIER_SUCCESS" => "合并记账成功",
    "CASHIER_MERGERCASHIER_DATA_SPECIFY" => "缺少data数据",
    "CASHIER_PID_SPECIFY" => "缺少PID",
    "CASHIER_SUCCESS" => "记账成功",
    "CASHIER_ERROR" => "记账失败",

    //帮助中心相关
    "HELP_PAGENUM_SPECIFY" => "请指定页码",
    "HELP_SORT_SPECIFY" => "请指定排序类型",
    "HELP_LIMIT_SPECIFY" => "请指定每页显示的最大数据量",
    "HELP_ID_SPECIFY" => "缺少帮助内容的id",
    "HELP_ID_INVALID" => "没有这条帮助内容",

    //反馈
    "FEEDBACK_CONTENT_SPECIFY" => "请指定反馈内容",

    //通用
    "COMMON_QUERY_SUCCESS" => "查询成功",
    "COMMON_CZ_SUCCESS" => "操作成功",
    "COMMON_CZ_ERROR" => "操作失败"
);
?>