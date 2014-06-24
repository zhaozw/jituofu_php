<?php

class FilesController extends Controller
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
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions'=>array('create','add'),
                'users'=>array('*'),
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
        if(F::loggedCommonVerify()){
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $userId = $public['uesrId'];
            $file = @CUploadedFile::getInstanceByName("file");

            if(!$file){
                return F::returnError(F::lang('UPLOAD_FILE_SPECIFY'));
            }

            if($path = Files::upload($userId, $file)){
                $model=new Files;
                $model->attributes = array(
                    'dir' => $userId,
                    'name' => $path
                );
                $this->performAjaxValidation($model);
                if($model->save()){
                    F::returnSuccess(F::lang('UPLOAD_IMAGE_SUCCESS'), array($model->primaryKey));
                }else{
                    F::returnError(F::lang('UPLOAD_IMAGE_ERROR'));
                }
            }
        }
    }

    /**
     * 未登录状态下上传图片
     */
    public function actionAdd()
    {
        if(F::notLoggedCommonVerify()){
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $dir = $operation['dir'];
            $file = @CUploadedFile::getInstanceByName('file');

            if(!$file){
                return F::returnError(F::lang('UPLOAD_FILE_SPECIFY'));
            }

            if(!$dir){
                return F::returnError(F::lang('UPLOAD_DIR_SPECIFY'));
            }

            if($path = Files::upload($dir, $file)){
                $model=new Files;
                $model->attributes = array(
                    'dir' => $dir,
                    'name' => $path
                );
                $this->performAjaxValidation($model);
                if($model->save()){
                    F::returnSuccess(F::lang('UPLOAD_IMAGE_SUCCESS'), array("fileId" => $model->primaryKey));
                }else{
                    F::returnError(F::lang('UPLOAD_IMAGE_ERROR'));
                }
            }
        }
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

		if(isset($_POST['Files']))
		{
			$model->attributes=$_POST['Files'];
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
		$dataProvider=new CActiveDataProvider('Files');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Files('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Files']))
			$model->attributes=$_GET['Files'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Files the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Files::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Files $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='files-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
