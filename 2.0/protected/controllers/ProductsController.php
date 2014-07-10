<?php

class ProductsController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view', 'create', 'update', 'delete', 'query', 'search'),
                'users' => array('*'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionSearch(){
        if (F::loggedCommonVerify()) {
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $keyword = @$operation['keyword'];
            $pageNum = @$operation['pageNum'];
            $limit = @$operation['limit'];
            $userId = $public['userId'];
            $sort = @$operation['sort'];

            if(!$keyword){
                return F::returnError(F::lang("PRODUCT_KEYWORD_SPECIFY"));
            }

            if(!$pageNum){
                $pageNum = 1;
            }
            if(!$limit){
                $limit = 10;
            }
            if(!$sort){
                $sort = 1;
            }

            $pageNum = $pageNum - 1;

            $criteria = new CDbCriteria;
            $criteria->addCondition('user_id=' . $userId);
            $criteria->addCondition('status=1');
            $criteria->addCondition("name like :name");
            $criteria->params = array(":name"=>"%".$keyword."%");

            $count = Products::model()->count($criteria);

            $pages = new CPagination($count);
            $pages->setPageSize($limit);
            $pages->setCurrentPage($pageNum);
            $pages->applyLimit($criteria);

            $csort = new CSort;

            if ($sort == 1) {
                //date倒序
                $csort->defaultOrder = 'date DESC';
            } else if ($sort == 2) {
                //date升序
                $csort->defaultOrder = 'date ASC';
            } else if ($sort == 3) {
                //price倒序
                $csort->defaultOrder = 'price DESC';
            } else if ($sort == 4) {
                //price升序
                $csort->defaultOrder = 'price ASC';
            }

            $result = Products::model()->findAll($criteria);
            $dataProvider = new CArrayDataProvider(
                $result,
                array(
                    'sort' => $csort,
                    'pagination' => $pages
                )
            );
            $records = array();

            $lastPage = $count / $limit;
            if (is_float($lastPage)) {
                $lastPage = $lastPage + 1;
            }
            if (($pageNum + 1) > $lastPage) {
                F::returnSuccess(F::lang('COMMON_QUERY_SUCCESS'), array("productsList"=>$records));
                return;
            }

            foreach ($dataProvider->getData() as $k => $record) {
                array_push($records, array(
                    'id' => $record->id,
                    'name' => $record->name,
                    'count' => $record->count,
                    'price' => $record->price,
                    'pic' => Files::getImg($record->pic),
                    'date' => $record->date,
                    'type' => Types::getTypeNameById($record->type),
                    'remark' => $record->remark
                ));
            }

            F::returnSuccess(F::lang('COMMON_QUERY_SUCCESS'), array("productsList"=>$records));
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        if (F::loggedCommonVerify()) {
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $name = @$operation['name'];
            $count = @$operation['count'];
            $price = @$operation['price'];
            $type = @$operation['type'];
            $date = @$operation['date'];
            $remark = @$operation['remark'];

            if (Products::isExistByName(F::trimAll($name), $type)) {
                F::returnError(F::lang('PRODUCT_NAME_IN_USE'));
            }

            //如果有图片上传
            $filesId = array();
            if (count($_FILES) > 0) {
                $dir = $public['userId'];
                foreach ($_FILES as $k => $file) {
                    $f = @CUploadedFile::getInstanceByName($k);
                    if ($f) {
                        if ($path = Files::upload($dir, $f)) {
                            $model = new Files;
                            $model->attributes = array(
                                'dir' => $dir,
                                'name' => $path
                            );
                            if ($model->save()) {
                                array_push($filesId, $model->primaryKey);
                            } else {
                                F::error("上传文件失败" . CJSON::encode($model->getErrors()));
                            }
                        }
                    }
                }
            }

            $model = new Products;

            $model->attributes = array(
                "user_id" => $public['userId'],
                "name" => $name,
                "count" => $count,
                "price" => $price,
                "pic" => @$filesId[0],
                "type" => $type,
                "remark" => $remark,
                "date" => $date ? $date : F::getCurrentDatetime(),
                "status" => 1 //默认显示该商品
            );
            $this->performAjaxValidation($model);

            $product = Products::add($model);

            if ($product) {
                F::returnSuccess(F::lang('PRODUCT_ADD_SUCCESS'), array(
                    'id' => $product['id'],
                    'name' => $model->attributes['name'],
                    'count' => $model->attributes['count'],
                    'price' => $model->attributes['price'],
                    'pic' => Files::getImg($model->attributes['pic']),
                    'date' => $model->attributes['date'],
                    'type' => $product['type']
                ));
            } else {
                F::returnError(F::lang('PRODUCT_ADD_ERROR'), $model->getErrors());
            }
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate()
    {
        if (F::loggedCommonVerify(true)) {
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $id = @$operation['id'];
            $name = @$operation['name'];
            $count = @$operation['count'];
            $price = @$operation['price'];
            $type = @$operation['type'];
            $date = @$operation['date'];
            $remark = @$operation['remark'];

            if (!$id) {
                F::returnError(F::lang('PRODUCT_ID_SPECIFY'));
            } else if (!is_numeric($id)) {
                F::returnError(F::lang('PRODUCT_ID_INVALID'));
            }
            $product = Products::isExistById(F::trimAll($id));
            if (!$product) {
                F::returnError(F::lang('PRODUCT_NO_EXIST'));
            }

            //可能没有修改
            if(
            (count($_FILES) <= 0)
            &&
            ($product->getAttribute("name") == F::trimAll($name))
            &&
            ($product->getAttribute("count") == F::trimAll($count))
            &&
            ($product->getAttribute("price") == F::trimAll($price))
            &&
            ($product->getAttribute("type") == F::trimAll($type))
            &&
            (F::trimAll($product->getAttribute("date")) == F::trimAll($date))//去除日期数据中的空格
            &&
            ($product->getAttribute("remark") == F::trimAll($remark))
            ){
                return F::returnError(F::lang("PRODUCT_NO_UPDATE"));
            }

            //分类不存在
            $typeName = Types::getTypeNameById($type);
            if (!$typeName) {
                return F::returnError(F::lang('PRODUCT_UPDATE_ERROR') . ' ' . F::lang('TYPE_NO_EXIST'));
            }

            //商品名称重复
            $existProduct = Products::isExistByName(F::trimAll($name), $type);
            if ($existProduct && $existProduct->getAttribute("id") != $id) {
                F::returnError(F::lang('PRODUCT_NAME_IN_USE'));
            }

            //如果有图片上传
            $filesId = array();
            if (count($_FILES) > 0) {
                $dir = $public['userId'];
                foreach ($_FILES as $k => $file) {
                    $f = @CUploadedFile::getInstanceByName($k);
                    if ($f) {
                        if ($path = Files::upload($dir, $f)) {
                            $model = new Files;
                            $model->attributes = array(
                                'dir' => $dir,
                                'name' => $path
                            );
                            if ($model->save()) {
                                if($product->getAttribute("pic")){
                                    $fileDeleted = Files::remove($product->getAttribute("pic"));
                                    if(!$fileDeleted){
                                        F::error("删除文件".$product->getAttribute("pic")."失败");
                                    }
                                }

                                array_push($filesId, $model->primaryKey);
                            } else {
                                F::returnError("更新文件失败" . CJSON::encode($model->getErrors()));
                            }
                        }
                    }
                }
            }else{
                array_push($filesId, $product->getAttribute("pic"));
            }

            $model = $this->loadModel($id);

            $model->attributes = array(
                "name" => $name,
                "count" => $count,
                "price" => $price,
                "pic" => @$filesId[0],
                "type" => $type,
                "date" => $date ? $date : F::getCurrentDatetime(),
                "remark" => F::trimAll($remark),
            );
            $this->performAjaxValidation($model);


            if ($model->save(true, array('name', 'count', 'price', 'type', 'date', 'pic', 'remark'))) {
                F::returnSuccess(F::lang('PRODUCT_UPDATE_SUCCESS'), array(
                    'id' => $model->primaryKey,
                    'name' => $model->attributes['name'],
                    'count' => $model->attributes['count'],
                    'price' => $model->attributes['price'],
                    'pic' => Files::getImg($model->attributes['pic']),
                    'date' => $model->attributes['date'],
                    'type' => $typeName,
                    "uid" => $model->attributes["user_id"],
                    "remark" => $model->attributes["remark"],
                    "typeId" => $model->attributes["type"]
                ));
            } else {
                F::returnError(F::lang('PRODUCT_UPDATE_ERROR'), $model->getErrors());
            }
        }
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete()
    {
        if (F::loggedCommonVerify(true)) {
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $id = @$operation['id'];

            if (!$id) {
                F::returnError(F::lang('PRODUCT_ID_SPECIFY'));
            } else if (!is_numeric($id)) {
                F::returnError(F::lang('PRODUCT_ID_INVALID'));
            }
            $record = Products::isExistById(F::trimAll($id));
            if (!$record) {
                F::returnError(F::lang('PRODUCT_NO_EXIST'));
            }

            $criteria = new CDbCriteria;
            $criteria->addInCondition('status', array(1));
            $criteria->addInCondition('user_id', array($public['userId']));

            if ($record->updateByPk($id, array('status' => 0), $criteria) > 0) {
                F::returnSuccess(F::lang('PRODUCT_DELETED_SUCCESS'));
            } else {
                F::returnSuccess(F::lang('PRODUCT_DELETED_ERROR'));
            }
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        if (F::loggedCommonVerify()) {
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $pageNum = $operation['pageNum'];
            $limit = $operation['limit'];
            $userId = $public['userId'];
            $sort = $operation['sort'];
            $type = @$operation['type']; //按分类查询

            $pageNum = $pageNum - 1;

            $criteria = new CDbCriteria;
            $criteria->addCondition('user_id=' . $userId);
            $criteria->addCondition('status=1');
            if ($type) {
                $criteria->addCondition("type=$type");
            }

            $count = Products::model()->count($criteria);

            $pages = new CPagination($count);
            $pages->setPageSize($limit);
            $pages->setCurrentPage($pageNum);
            $pages->applyLimit($criteria);

            $csort = new CSort;

            if ($sort == 1) {
                //date倒序
                $csort->defaultOrder = 'date DESC';
            } else if ($sort == 2) {
                //date升序
                $csort->defaultOrder = 'date ASC';
            } else if ($sort == 3) {
                //price倒序
                $csort->defaultOrder = 'price DESC';
            } else if ($sort == 4) {
                //price升序
                $csort->defaultOrder = 'price ASC';
            }

            $result = Products::model()->findAll($criteria);
            $dataProvider = new CArrayDataProvider(
                $result,
                array(
                    'sort' => $csort,
                    'pagination' => $pages
                )
            );
            $records = array();

            $lastPage = $count / $limit;
            if (is_float($lastPage)) {
                $lastPage = $lastPage + 1;
            }
            if (($pageNum + 1) > $lastPage) {
                F::returnSuccess(F::lang('COMMON_QUERY_SUCCESS'), $records);
                return;
            }

            foreach ($dataProvider->getData() as $k => $record) {
                array_push($records, array(
                    'id' => $record->id,
                    'name' => $record->name,
                    'count' => $record->count,
                    'price' => $record->price,
                    'pic' => Files::getImg($record->pic),
                    'date' => $record->date,
                    'type' => Types::getTypeNameById($record->type),
                    'remark' => $record->remark
                ));
            }

            F::returnSuccess(F::lang('COMMON_QUERY_SUCCESS'), $records);
        }
    }

    /**
     * 根据商品id获取商品详情
     */
    public function actionQuery()
    {
        if (F::loggedCommonVerify()) {
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $pid = $operation['id'];

            if (!$pid) {
                F::returnError(F::lang('PRODUCT_ID_SPECIFY'));
            } else if (!is_numeric($pid)) {
                F::returnError(F::lang('PRODUCT_ID_INVALID'));
            }
            $record = Products::isExistById(F::trimAll($pid));
            if (!$record) {
                F::returnError(F::lang('PRODUCT_NO_EXIST'));
            }

            $result = array(
                "id" => $record->getAttribute("id"),
                "uid" => $record->getAttribute("user_id"),
                "count" => $record->getAttribute("count"),
                "price" => $record->getAttribute("price"),
                "pic" => Files::getImg($record->getAttribute("pic")),
                "date" => $record->getAttribute("date"),
                "remark" => $record->getAttribute("remark"),
                "name" => $record->getAttribute("name"),
                "typeId" => $record->getAttribute("type"),
                "type" => Types::getTypeNameById($record->getAttribute("type")),

            );
            F::returnSuccess(F::lang('COMMON_QUERY_SUCCESS'), $result);
        }
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Products('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Products']))
            $model->attributes = $_GET['Products'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Products the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Products::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Products $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'products-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
