<?php

class HelpController extends Controller
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
			'postOnly + delete', // we only allow deletion via POST request
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
				'actions'=>array('index','yes', 'no'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
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
		$model=new Help;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Help']))
		{
			$model->attributes=$_POST['Help'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Help']))
		{
			$model->attributes=$_POST['Help'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		if(F::notLoggedCommonVerify()){
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $pageNum = @$operation['pageNum'];
            $limit = @$operation['limit'];
            $sort = @$operation['sort'];

            if(!$pageNum){
                return F::returnError(F::lang('HELP_PAGENUM_SPECIFY'));
            }
            if(!$limit){
                return F::returnError(F::lang('HELP_LIMIT_SPECIFY'));
            }
            if(!$sort){
                return F::returnError(F::lang('HELP_SORT_SPECIFY'));
            }

            $pageNum = $pageNum-1;

            $criteria=new CDbCriteria;

            $count = Help::model()->count();

            $pages = new CPagination($count);
            $pages->setPageSize($limit);
            $pages->setCurrentPage($pageNum);
            $pages->applyLimit($criteria);

            $csort = new CSort;

            if($sort == 1){
                //time倒序
                $csort->defaultOrder = 'createtime DESC';
            }else if($sort == 2){
                //time升序
                $csort->defaultOrder = 'createtime ASC';
            }

            $result = Help::model()->findAll($criteria);
            $dataProvider = new CArrayDataProvider(
                $result,
                array(
                    'sort' => $csort,
                    'pagination' => $pages
                )
            );
            $records = array();

            $lastPage = $count/$limit;
            if(is_float($lastPage)){
                $lastPage = $lastPage+1;
            }
            if(($pageNum+1)>$lastPage){
                return F::returnSuccess(F::lang('COMMON_QUERY_SUCCESS'), array("helps" => $records));
            }

            foreach($dataProvider->getData() as $k => $record){
                array_push($records, array(
                    'id' => $record->id,
                    'title' => $record->title,
                    'yes' => $record->yes,
                    'no' => $record->no,
                    'content' => $record -> content,
                    'alias' => $record -> alias,
                    'createtime' => $record->createtime
                ));
            }

            return F::returnSuccess(F::lang('COMMON_QUERY_SUCCESS'), array("helps" => $records));
        }
	}

    public function actionYes(){
        if(F::notLoggedCommonVerify()){
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $id = @$operation['id'];

            if(!$id){
                return F::returnError(F::lang('HELP_ID_SPECIFY'));
            }

            $record = Help::model()->findByPk($id);

            if(!$record){
                return F::returnError(F::lang('HELP_ID_INVALID'));
            }

            $record_yes = (int) $record->getAttribute("yes");

            if(Help::model()->updateByPk($id, array(
                "yes" => $record_yes+1
            ))){
                return F::returnSuccess(F::lang('COMMON_CZ_SUCCESS'));
            }else{
                return F::returnError(F::lang('COMMON_CZ_ERROR'));
            }
        }
    }

    public function actionNo(){
        if(F::notLoggedCommonVerify()){
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $id = @$operation['id'];

            if(!$id){
                return F::returnError(F::lang('HELP_ID_SPECIFY'));
            }

            $record = Help::model()->findByPk($id);

            if(!$record){
                return F::returnError(F::lang('HELP_ID_INVALID'));
            }

            $record_yes = (int) $record->getAttribute("no");

            if(Help::model()->updateByPk($id, array(
                "no" => $record_yes+1
            ))){
                return F::returnSuccess(F::lang('COMMON_CZ_SUCCESS'));
            }else{
                return F::returnError(F::lang('COMMON_CZ_ERROR'));
            }
        }
    }

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Help('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Help']))
			$model->attributes=$_GET['Help'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Help the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Help::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Help $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='help-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
