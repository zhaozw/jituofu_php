<?php

class FeedbackController extends Controller
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
				'actions'=>array('create'),
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
        if(F::notLoggedCommonVerify()){
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $content = @F::trimAll($operation['content']);
            $pics = @F::trimAll($operation['pics']);
            $author = @F::trimAll($operation['author']);
            $email = @F::trimAll($operation['email']);
            $phone = @F::trimAll($operation['phone']);

            $time = F::getCurrentDatetime();

            if(!$content){
                return F::returnError(F::lang('FEEDBACK_CONTENT_SPECIFY'));
            }

            //如果有图片上传
            $filesId = array();
            if(count($_FILES) > 0){
                $dir = @F::trimAll($operation['dir']);
                if(!$dir){
                    return F::returnError(F::lang("MEMO_NO_DIR"));
                }
                foreach($_FILES as $k => $file){
                    $f = @CUploadedFile::getInstanceByName($k);
                    if($f){
                        if($path = Files::upload($dir, $f)){
                            $model=new Files;
                            $model->attributes = array(
                                'dir' => $dir,
                                'name' => $path
                            );
                            if($model->save()){
                                array_push($filesId, $model->primaryKey);
                            }else{
                                F::error("上传文件失败". CJSON::encode($model->getErrors()));
                            }
                        }
                    }
                }
            }

            if(count($filesId) > 0){
                $pics = implode(",", $filesId);
            }

            if(!$pics){
                $pics = "";
            }

            if(!$author){
                $author = "";
            }

            if(!$email){
                $email = "";
            }

            if(!$phone){
                $phone = "";
            }

            $model=new Feedback;

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            $model->attributes=array(
                "content" => $content,
                "pics" => $pics,
                "author" => $author,
                "email" => $email,
                "phone" => $phone,
                "time" => $time
            );

            if($model->save()){
                return F::returnSuccess(F::lang('FEEDBACK_SUBMIT_SUCCESS'));
            }else{
                F::error("反馈内容入库失败 ".CJSON::encode($model->getErrors()));
                return F::returnError(F::lang('FEEDBACK_SUBMIT_ERROR'));
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

		if(isset($_POST['Feedback']))
		{
			$model->attributes=$_POST['Feedback'];
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
		$dataProvider=new CActiveDataProvider('Feedback');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Feedback('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Feedback']))
			$model->attributes=$_GET['Feedback'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Feedback the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Feedback::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Feedback $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='feedback-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
