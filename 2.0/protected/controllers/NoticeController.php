<?php

class NoticeController extends Controller
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
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
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
        $model = new Notice;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Notice'])) {
            $model->attributes = $_POST['Notice'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Notice'])) {
            $model->attributes = $_POST['Notice'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
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
        if (!isset($_GET['ajax']))
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

            $version = @$operation['version'];

            if (!$version) {
                return F::returnError(F::lang("NOTICE_VERSION_SPECIFY"));
            }

            $record = Notice::model()->findByAttributes(array('is_last' => 1));

            $result = array();
            if (!$record) {
                return F::returnError(F::lang("COMMON_QUERY_SUCCESS"));
            }

            $min_version = @$record->getAttribute("min_version");
            $max_version = @$record->getAttribute("max_version");

            //比较最小版本
            if ($min_version && !$max_version) {
                $compare_min = version_compare($min_version, $version);
                //最小的版本小于传入的版本号
                if ($compare_min > 0) {
                    return F::returnError(F::lang("COMMON_QUERY_SUCCESS"));
                }else{
                    $result = array(
                        "title" => $record->getAttribute("title"),
                        "content" => $record->getAttribute("content"),
                        "date" => $record->getAttribute("date"),
                        "position" => $record->getAttribute("position")
                    );
                }
            } else if ($max_version && !$min_version) {
                $compare_max = version_compare($max_version, $version);
                //最大的版本小于传入的版本号
                if ($compare_max < 0) {
                    return F::returnError(F::lang("COMMON_QUERY_SUCCESS"));
                }else{
                    $result = array(
                        "title" => $record->getAttribute("title"),
                        "content" => $record->getAttribute("content"),
                        "date" => $record->getAttribute("date"),
                        "position" => $record->getAttribute("position")
                    );
                }
            } else if ($max_version && $min_version) {
                $compare_max = version_compare($max_version, $version);
                $compare_min = version_compare($min_version, $version);
                if ($compare_max < 0 || $compare_min > 0) {
                    return F::returnError(F::lang("COMMON_QUERY_SUCCESS"));
                }else{
                    $result = array(
                        "title" => $record->getAttribute("title"),
                        "content" => $record->getAttribute("content"),
                        "date" => $record->getAttribute("date"),
                        "position" => $record->getAttribute("position")
                    );
                }
            } else {
                $result = array(
                    "title" => $record->getAttribute("title"),
                    "content" => $record->getAttribute("content"),
                    "date" => $record->getAttribute("date"),
                    "position" => $record->getAttribute("position")
                );
            }

            return F::returnSuccess(F::lang("COMMON_QUERY_SUCCESS"), $result);
        }
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Notice('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Notice']))
            $model->attributes = $_GET['Notice'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Notice the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Notice::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Notice $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'notice-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
