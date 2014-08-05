<?php

class StoreSettingsController extends Controller
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
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view', 'create', 'delete'),
                'users' => array('admin'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('update', 'get'),
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

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new StoreSettings;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['StoreSettings'])) {
            $model->attributes = $_POST['StoreSettings'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionGet()
    {
        if (F::loggedCommonVerify(true)) {
            $public = F::getPublicData();
            $operation = F::getOperationData();
            $userId = $public['userId'];

            if(!@$operation['clientId']){
               return F::returnError(F::lang('MEMO_NO_CLIENTID'));
            }

            $record = StoreSettings::model()->findByAttributes(array('user_id' => $userId));

            if(!$record->name){
                $record->name = "";
            }

            if (!$record) {
                return F::returnError(F::lang("STORE_NO_EXIST"));
            }else{
                return F::returnSuccess(F::lang("COMMON_QUERY_SUCCESS"), array("storeSettings" => $record));
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
            $userId = $public['userId'];

            @$name = $operation['name'];

            if ($name) {
                $name = F::trimAll($name);
            } else {
                return F::returnError(F::lang("STORE_NAME_SPECIFY"));
            }

            $record = StoreSettings::model()->findByAttributes(array('user_id' => $userId));

            if (!$record) {
                return F::returnError(F::lang("STORE_NO_EXIST"));
            }

            // 计算中文字符串长度
            function utf8_strlen($string = null)
            {
// 将字符串分解为单元
                preg_match_all("/./us", $string, $match);
// 返回单元个数
                return count($match[0]);
            }

            if ($name && utf8_strlen($name) < 2) {
                return F::returnError(F::lang("STORE_NAME_CHAR_LIMIT", array(2, 25)));
            }
            //商户名称没有更新
            if ($record->getAttribute("name") == $name) {
                F::returnError(F::lang("STORE_NAME_NO_UPDATE"));
                return false;
            }

            $attribute = array();
            if ($name) {
                $attribute['name'] = $name;
            }

            $criteria = new CDbCriteria;
            $criteria->condition = "user_id = $userId";
            $rows = StoreSettings::model()->updateAll(
                $attribute,
                $criteria
            );
            if ($rows < 1) {
                F::returnError(F::lang("SOTRE_UPDATE_ERROR"));
            } else {
                F::returnSuccess(F::lang("SOTRE_UPDATE_SUCCESS"));
            }
        }
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
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('StoreSettings');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new StoreSettings('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['StoreSettings']))
            $model->attributes = $_GET['StoreSettings'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return StoreSettings the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = StoreSettings::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param StoreSettings $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'store-settings-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
