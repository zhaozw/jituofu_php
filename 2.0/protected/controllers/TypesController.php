<?php

class TypesController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view', 'detailp'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'delete', 'deletepcp', 'deletepmc', 'deletecp', 'deletemp'),
				'users'=>array('*'),
                'verbs' => array('post', 'get')
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
        if(F::loggedCommonVerify()){
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $name = @$operation['name'];
            $parent = @$operation['parent'];

            //创建小分类
            if($parent){
                if(!is_numeric($parent)){
                    F::returnError(F::lang('TYPE_PARENT_FORMAT_ERROR'));
                }
                $model=new Types('createChild');
                if(!$this->isExistParentById($parent)){
                    F::returnError(F::lang('TYPE_NO_EXIST_PARENT'));
                }else{
                    if(!$this -> isExistChildByName(F::trimAll($name), $parent)){
                        $model->attributes=array(
                            'user_id' => $public['userId'],
                            'name' => $name,
                            'parent_id' => $parent,
                            'time' => F::getCurrentDatetime()
                        );
                    }else{
                        F::returnError(F::lang('TYPE_NAME__IN_USE'));
                    }
                }
            }else{
                //创建大分类
                $model=new Types('createParent');

                if($this -> isExistParentByName(F::trimAll($name))){
                    F::returnError(F::lang('TYPE_NAME__IN_USE'));
                }

                $model->attributes=array(
                    'user_id' => $public['userId'],
                    'name' => $name,
                    'time' => F::getCurrentDatetime()
                );
            }

            $this->performAjaxValidation($model);

            if($model->save()){
                F::returnSuccess(F::lang('TYPE_CREATE_SUCCESS'), array("id" => $model->primaryKey, "name" => F::trimAll($name)));
            }else{
                F::returnError(F::lang('TYPE_CREATE_ERROR'), $model->getErrors());
            }
        }
	}

    private function isExistParentByName($name){
        $public = F::getPublicData();
        $record = Types::model()->findByAttributes(array('name'=>$name, 'user_id' => $public['userId'], 'parent_id'=>null));

        return $record ? true : false;
    }

    private function isExistParentById($id){
        $public = F::getPublicData();
        $record = Types::model()->findByAttributes(array('id'=>$id, 'user_id' => $public['userId'], 'parent_id'=>null));

        return $record ? true : false;
    }

    private function isExistChildByName($name, $parent){
        $public = F::getPublicData();
        $record = Types::model()->findByAttributes(array('parent_id'=>$parent, 'name'=>$name, 'user_id' => $public['userId']));

        return $record ? true : false;
    }

    private function isExistChildById($id, $parent){
        $public = F::getPublicData();
        $record = Types::model()->findByAttributes(array('parent_id'=>$parent, 'id'=>$id, 'user_id' => $public['userId']));

        return $record ? true : false;
    }

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
        if(F::loggedCommonVerify(true)){
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $id = @$operation['id'];
            $name = @$operation['name'];
            $parent = @$operation['parent'];

            if(!$id){
                F::returnError(F::lang(('TYPE_ID_SPECIFY')));
            }else if(!is_numeric($id)){
                F::returnError(F::lang('TYPE_NO_EXIST'));
            }
            if(!$name){
                F::returnError(F::lang(('TYPE_NAME_SPECIFY')));
            }

            if($parent){//修改小分类名称
                if($parent === $id){
                    F::returnError(F::lang('TYPE_PARENT_EQUAL_CHILD'));
                }
                if(!is_numeric($parent)){
                    F::returnError(F::lang('TYPE_PARENT_FORMAT_ERROR'));
                }

                $nameIsExist = Types::model()->findByAttributes(array('id'=>$id, 'user_id' => $public['userId'], 'parent_id' => $parent, 'name' => F::trimAll($name)));

                if($nameIsExist){
                    return F::returnError(F::lang("TYPE_NAME__IN_USE"));
                }

                $record = Types::model()->findByAttributes(array('id'=>$id, 'user_id' => $public['userId'], 'parent_id' => $parent));
            }else{

                $nameIsExist = Types::model()->findByAttributes(array('id'=>$id, 'user_id' => $public['userId'], 'parent_id' => null, 'name' => F::trimAll($name)));

                if($nameIsExist){
                    return F::returnError(F::lang("TYPE_NAME__IN_USE"));
                }

                $record = Types::model()->findByAttributes(array('id'=>$id, 'user_id' => $public['userId'], 'parent_id'=>null));
            }

            if(!$record){
                F::returnError(F::lang('TYPE_NO_EXIST'));
            }else if($record->getAttribute('name') === F::trimAll($name)){
                F::returnError(F::lang('TYPE_NAME_NOTHING_TO_UPDATE'));
            }

            $model=$this->loadModel($id);
            $model->attributes=array(
                'name' => $name,
                'time' => F::getCurrentDatetime()
            );

            $this->performAjaxValidation($model);
            if($model->save(true, array('name'))){
                F::returnSuccess(F::lang('TYPE_UPDATE_SUCCESS'), array("id" => $id, "name" => F::trimAll($name), "parent" => $model->getAttribute("parent_id")));
            }else{
                F::returnError(F::lang('TYPE_UPDATE_ERROR'), $model->getErrors());
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
        if(F::loggedCommonVerify(true)){
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $id = @$operation['id'];
            $parent = @$operation['parent'];

            if(!$id){
                F::returnError(F::lang(('TYPE_ID_SPECIFY')));
            }else if(!is_numeric($id)){
                F::returnError(F::lang('TYPE_NO_EXIST'));
            }

            if($parent){
                if($parent === $id){
                    F::returnError(F::lang('TYPE_PARENT_EQUAL_CHILD'));
                }
                if(!is_numeric($parent)){
                    F::returnError(F::lang('TYPE_PARENT_FORMAT_ERROR'));
                }
                $record = Types::model()->findByAttributes(array('id'=>$id, 'user_id' => $public['userId'], 'parent_id' => $parent));
            }else{
                $record = Types::model()->findByAttributes(array('id'=>$id, 'user_id' => $public['userId'], 'parent_id'=>null));
            }

            if(!$record){
                F::returnError(F::lang('TYPE_NO_EXIST'));
            }

            if($record->delete()){
                F::returnSuccess(F::lang('TYPE_DELETE_SUCCESS'));
            }else{
                F::returnError(F::lang('TYPE_DELETE_ERROR'));
            }
        }
	}

    /**
     * 删除指定大分类及其下的所有商品所有小分类
     */
    public function actionDeletepcp(){
        if(F::loggedCommonVerify(true)){
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $id = $operation['id'];

            if(!$id){
                F::returnError(F::lang(('TYPE_ID_SPECIFY')));
            }else if(!is_numeric($id)){
                F::returnError(F::lang('TYPE_NO_EXIST'));
            }

            $record = Types::model()->findByAttributes(array('id'=>$id, 'user_id' => $public['userId'], 'parent_id'=>null));

            if(!$record){
                F::returnError(F::lang('TYPE_NO_EXIST'));
            }

            //大分类和小分类的ID存储
            //在这里,传给Products::deleteByTypeID
            $typeIds = array("parent" => array($id), "child" => array());

            $typeIsDeleted = false;
            if($record->delete()){
                $typeIsDeleted = true;
            }

            if($typeIsDeleted){
                $criteria = new CDbCriteria;
                $criteria->addInCondition('parent_id', array($id));
                $criteria->addInCondition('user_id', array($public['userId']));
                $childRecords = Types::model()->findAll($criteria);

                if (!empty($childRecords)) {
                    //将即将被删除的小分类id添加到$typeIds
                    foreach($childRecords as $k => $cr){
                        array_push($typeIds['child'], $cr->getAttribute('id'));
                    }
                    if(Types::model()->deleteAll($criteria) === count($childRecords)){
                        //根据分类id,删除商品
                        if(Products::deleteByTypeID($typeIds)){
                            F::returnSuccess(F::lang('TYPE_PARENT_CHILD_DELETE_PRODUCTS_SUCCESS'));
                        }else{
                            F::returnSuccess(F::lang('TYPE_PARENT_CHILD_DELETE_SUCCESS'));
                        }
                    }else{
                        F::returnError(F::lang('TYPE_DELETE_ERROR'));
                    }
                }else{
                    F::returnSuccess(F::lang('TYPE_DELETE_SUCCESS'));
                }
            }else{
                F::returnError(F::lang('TYPE_DELETE_ERROR'));
            }
        }
    }

    /**
     * 删除小分类及其下的所有商品
     */
    public function actionDeletecp(){
        if(F::loggedCommonVerify(true)){
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $id = @$operation['id'];
            $parent = @$operation['parent'];

            if(!$id){
                F::returnError(F::lang(('TYPE_ID_SPECIFY')));
            }else if(!is_numeric($id)){
                F::returnError(F::lang('TYPE_NO_EXIST'));
            }
            if(!is_numeric($parent)){
                F::returnError(F::lang('TYPE_PARENT_FORMAT_ERROR'));
            }else if($parent === $id){
                F::returnError(F::lang('TYPE_PARENT_EQUAL_CHILD'));
            }

            $record = Types::model()->findByAttributes(array('id'=>$id, 'user_id' => $public['userId'], 'parent_id'=>$parent));

            if(!$record){
                F::returnError(F::lang('TYPE_NO_EXIST'));
            }

            $typeIsDeleted = false;
            if($record->delete()){
                $typeIsDeleted = true;
            }

            if($typeIsDeleted){
                if(Products::deleteByTypeID(array("child" => array($id), "parent"=>array()))){
                    F::returnSuccess(F::lang('TYPE_CHILD_DELETE_PRODUCTS_SUCCESS'));
                }else{
                    F::returnSuccess(F::lang('TYPE_DELETE_SUCCESS'));
                }
            }else{
                F::returnError(F::lang('TYPE_DELETE_ERROR'));
            }
        }
    }

    /**
     * 删除小分类并且移动其下的所有商品到指定的其它小分类
     */
    public function actionDeletemp(){
        if(F::loggedCommonVerify(true)){
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $id = @$operation['id'];
            $parent = @$operation['parent'];
            $targetId = @$operation['targetId'];

            if(!$id){
                F::returnError(F::lang(('TYPE_ID_SPECIFY')));
            }else if(!is_numeric($id)){
                F::returnError(F::lang('TYPE_NO_EXIST'));
            }
            if(!is_numeric($parent)){
                F::returnError(F::lang('TYPE_PARENT_FORMAT_ERROR'));
            }else if($parent === $id){
                F::returnError(F::lang('TYPE_PARENT_EQUAL_CHILD'));
            }else if($parent === $targetId){
                F::returnError(F::lang('TYPE_PARENT_EQUAL_TARGETID'));
            }else if($id === $targetId){
                F::returnError(F::lang('TYPE_CHILDID_EQUAL_TARGETID'));
            }

            $record = Types::model()->findByAttributes(array('id'=>$id, 'user_id' => $public['userId'], 'parent_id'=>$parent));
            $targetTypeRecord = Types::model()->findByAttributes(array('id'=>$targetId, 'user_id' => $public['userId'], 'parent_id'=>$parent));

            if(!$targetTypeRecord){
                F::returnError(F::lang('TYPE_NO_EXIST_TARGET'));
            }else if(!$record){
                F::returnError(F::lang('TYPE_NO_EXIST'));
            }

            $typeIsDeleted = false;
            if($record->delete()){
                $typeIsDeleted = true;
            }

            if($typeIsDeleted){
                if(Products::updateType(array("child" => array($id), "parent"=>array()), $targetId)){
                    F::returnSuccess(F::lang('TYPE_CHILD_DELETE_AND_MOVE_PRODUCTS_SUCCESS'));
                }else{
                    F::returnSuccess(F::lang('TYPE_DELETE_SUCCESS'));
                }
            }else{
                F::returnError(F::lang('TYPE_DELETE_ERROR'));
            }
        }
    }

    /**
     * 删除大分类,并且移动其下的小分类到其它大分类
     */
    public function actionDeletepmc(){
        if(F::loggedCommonVerify(true)){
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $id = $operation['id'];
            $to = $operation['to'];

            if(!$id){
                F::returnError(F::lang(('TYPE_ID_SPECIFY')));
            }else if(!is_numeric($id)){
                F::returnError(F::lang('TYPE_NO_EXIST'));
            }
            if(!$to){
                F::returnError(F::lang(('TYPE_TO_SPECIFY')));
            }else if(!is_numeric($to)){
                F::returnError(F::lang('TYPE_NO_EXIST'));
            }

            //查询当前分类是否存在
            $record = Types::model()->findByAttributes(array('id'=>$id, 'user_id' => $public['userId'], 'parent_id'=>null));
            if(!$record){
                F::returnError(F::lang('TYPE_NO_EXIST'));
            }
            //查询目标分类是否存在
            $trecord = Types::model()->findByAttributes(array('id'=>$to, 'user_id' => $public['userId'], 'parent_id'=>null));
            if(!$trecord){
                F::returnError(F::lang('TYPE_TARGETPARENT_NO_EXIST'));
            }

            $typeIsDeleted = false;
            if($record->delete()){
                $typeIsDeleted = true;
            }

            if($typeIsDeleted){
                $criteria = new CDbCriteria;
                $criteria->addInCondition('parent_id', array($id));
                $criteria->addInCondition('user_id', array($public['userId']));
                $childRecords = Types::model()->findAll($criteria);

                if (!empty($childRecords)) {
                    if(Types::model()->updateAll(array('parent_id' => $to), $criteria) === count($childRecords)){
                        F::returnSuccess(F::lang('TYPE_PARENT_CHILD_MOVE_SUCCESS'));
                    }else{
                        F::returnError(F::lang('TYPE_CHILD_MOVE_ERROR'));
                    }
                }else{
                    F::returnSuccess(F::lang('TYPE_DELETE_SUCCESS'));
                }
            }else{
                F::returnError(F::lang('TYPE_DELETE_ERROR'));
            }
        }
    }

    /**
     * 查询大分类详情
     */
    public function actionDetailp(){
        if(F::loggedCommonVerify()){
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $id = $operation['id'];

            if(!$id){
                F::returnError(F::lang(('TYPE_ID_SPECIFY')));
            }else if(!is_numeric($id)){
                F::returnError(F::lang('TYPE_NO_EXIST'));
            }

            //查询当前分类是否存在
            $record = Types::model()->findByAttributes(array('id'=>$id, 'user_id' => $public['userId'], 'parent_id'=>null));
            if(!$record){
                F::returnError(F::lang('TYPE_NO_EXIST'));
            }

            $criteria = new CDbCriteria;
            $criteria->addInCondition('parent_id', array($id));
            $criteria->addInCondition('user_id', array($public['userId']));
            $childRecords = Types::model()->findAll($criteria);

            $pcriteria = new CDbCriteria;
            $pcriteria->addInCondition('type', array($id));
            $pcriteria->addInCondition('status', array(1));
            $pcriteria->addInCondition('user_id', array($public['userId']));
            $productRecords = Products::model()->findAll($pcriteria);

            F::returnSuccess(F::lang('TYPE_PARENT_DETAIL_SUCCESS'), array(
                "child" => count($childRecords),
                "product" => count($productRecords)
            ));
        }
    }

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
        if(F::loggedCommonVerify()){
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $pageNum = $operation['pageNum'];
            $limit = $operation['limit'];
            $userId = $public['userId'];
            $parent = @$operation['parent'];

            $pageNum = $pageNum-1;

            $criteria=new CDbCriteria;
            $criteria->addCondition('user_id='.$userId);
            $criteria->order = "time DESC";
            //查询小分类
            if($parent){
                $criteria->addCondition("parent_id=$parent");
            }else{
                $criteria->addCondition("parent_id IS NULL");
            }

            $count = Types::model()->count($criteria);

            $pages = new CPagination($count);
            $pages->setPageSize($limit);
            $pages->setCurrentPage($pageNum);
            $pages->applyLimit($criteria);

            $result = Types::model()->findAll($criteria);
            $dataProvider = new CArrayDataProvider(
                $result,
                array(
                    'pagination' => $pages
                )
            );
            $records = array();

            $lastPage = $count/$limit;
            if(is_float($lastPage)){
                $lastPage = $lastPage+1;
            }
            if(($pageNum+1)>$lastPage){
                F::returnSuccess(F::lang('COMMON_QUERY_SUCCESS'), array("types" => $records));
                return;
            }

            foreach($dataProvider->getData() as $k => $record){
                array_push($records, array(
                    'id' => $record->id,
                    'name' => $record->name
                ));
            }

            F::returnSuccess(F::lang('COMMON_QUERY_SUCCESS'), array("types" => $records));
        }
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Types('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Types']))
			$model->attributes=$_GET['Types'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Types the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Types::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Types $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='types-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
