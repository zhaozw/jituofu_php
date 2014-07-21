<?php

class SalesReportController extends Controller
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

    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {

    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {

    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {

    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        if (F::loggedCommonVerify()) {
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $userId = $public['userId'];

            $start = @$operation['start'];
            $end = @$operation['end'];
            $sort = @$operation['sort'];
            $limit = @$operation['limit'];
            $pageNum = @$operation['pageNum'];

            if($start && $end){
                $start .= ' 00:00:00';
                $end .= ' 23:59:59';
            }

            //排序
            $csort = new CSort;
            if(!$sort){
                $sort = 1;
            }
            if ($sort == 1) {
                //date倒序
                $csort->defaultOrder = 'date DESC';
            } else if ($sort == 2) {
                //date升序
                $csort->defaultOrder = 'date ASC';
            }


            //翻页
            if(!$pageNum){
                $pageNum = 1;
            }
            if(!$limit){
                $limit = 10;
            }
            $pageNum = $pageNum - 1;

            //记账台
            $cashier_criteria = new CDbCriteria;
            $cashier_criteria->addCondition('user_id=' . $userId);
            $cashier_criteria->addCondition("merge_id IS NULL");
            $cashier_criteria->addCondition("date >= '$start'");
            $cashier_criteria->addCondition("date <= '$end'");

            $cashier_count = Cashier::model()->count($cashier_criteria);
            $cashier_pages = new CPagination($cashier_count);
            $cashier_pages->setPageSize($limit);
            $cashier_pages->setCurrentPage($pageNum);
            $cashier_pages->applyLimit($cashier_criteria);

            $records = array();
            $cashier_result = Cashier::model()->findAll($cashier_criteria);
            $cashier_dataProvider = new CArrayDataProvider(
                $cashier_result,
                array(
                    'sort' => $csort,
                    'pagination' => $cashier_pages
                )
            );
            $cashier_lastPage = $cashier_count / $limit;
            if (is_float($cashier_lastPage)) {
                $cashier_lastPage = $cashier_lastPage + 1;
            }
            if (($pageNum + 1) > $cashier_lastPage) {
                F::returnSuccess(F::lang('COMMON_QUERY_SUCCESS'), array("products"=>$records));
                return;
            }

            var_dump($cashier_dataProvider->getData());
        }
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {

    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Wse the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {

    }

    /**
     * Performs the AJAX validation.
     * @param Wse $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {

    }
}
