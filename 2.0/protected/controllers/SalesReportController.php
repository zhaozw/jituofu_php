<?php

class SalesReportController extends Controller
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

            if ($start && $end) {
                $start .= ' 00:00:00';
                $end .= ' 23:59:59';
            }

            //排序
            $csort = new CSort;
            if (!$sort) {
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
            if (!$pageNum) {
                $pageNum = 1;
            }
            if (!$limit) {
                $limit = 10;
            }
            $pageNum = $pageNum - 1;

            //返回给客户端的结果
            $result = array("totalCount" => 0, "totalPrice" =>0, "salesList" => array());

            //记账台
            $cashier_criteria = new CDbCriteria;
            $cashier_criteria->addCondition('user_id=' . $userId);
            $cashier_criteria->addCondition("merge_id IS NULL");
            $cashier_criteria->addCondition("date >= '$start'");
            $cashier_criteria->addCondition("date <= '$end'");

            $cashier_result = Cashier::model()->findAll($cashier_criteria);
            $cashier_count = count($cashier_result);
            $cashier_pages = new CPagination($cashier_count);
            $cashier_pages->setPageSize($limit);
            $cashier_pages->setCurrentPage($pageNum);
            $cashier_pages->applyLimit($cashier_criteria);

            $cashier_records = array();


            //统计记账台的总销售量和总销售额
            foreach($cashier_result as $k => $v){
                $result['totalCount'] += $v -> selling_count;
                $result['totalPrice'] += ($v -> selling_price * $v -> selling_count);
            }

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

            //获取合并记账台的相关数据
            $mergecashier_records = $this->getMergeCashierSalesReport(false);

            //汇总合并记账表和记账表中的总销售量和总销售额的数据
            $result['totalCount'] += $mergecashier_records['totalCount'];
            $result['totalPrice'] += $mergecashier_records['totalPrice'];

            //如果记账台的数据已经加载结束，尝试看看合并记账是否有数据
            if (($pageNum + 1) > $cashier_lastPage) {
                $result['salesList'] = $mergecashier_records['salesList'];
                F::returnSuccess(F::lang('COMMON_QUERY_SUCCESS'), array("salesList" => $result));
                return;
            }

            foreach ($cashier_dataProvider->getData() as $k => $record) {
                $product_record = Products::model()->findByAttributes(array('id' => $record->pid, 'user_id' => $userId));
                array_push($cashier_records, array(
                    'isMerge' => 0,
                    'id' => $record->id,
                    'name' => $product_record->name,
                    'typeId' => $product_record->type,
                    'selling_count' => $record->selling_count,
                    'selling_price' => $record->selling_price,
                    'price' => $record->price,
                    'date' => $record->date,
                    'remark' => $record->remark
                ));
            }

            //将合并记账列表push到cashier列表，然后再排序
            if (count($mergecashier_records['salesList']) > 0) {
                foreach ($mergecashier_records['salesList'] as $k => $record) {
                    array_push($cashier_records, $record);
                }

                function cmpDESC($a, $b)
                {

                    if ($a['date'] == $b['date']) {
                        return 0;
                    }

                    return ($a['date'] < $b['date']) ? 1 : -1;
                }

                function cmpASC($a, $b)
                {

                    if ($a['date'] == $b['date']) {
                        return 0;
                    }

                    return ($a['date'] < $b['date']) ? -1 : 1;
                }

                if ($sort == 1) {
                    usort($cashier_records, 'cmpDESC');
                } else {
                    usort($cashier_records, 'cmpASC');
                }
            }
            $salesList = $cashier_records;

            $result['salesList'] = $salesList;
            $result['totalPrice'] = round($result['totalPrice'], 2);
            if(stripos($result['totalCount'], '.')){
                $result['totalCount'] = round($result['totalCount'], 2);
            }

            F::returnSuccess(F::lang('COMMON_QUERY_SUCCESS'), array("salesList" => $result));
        }
    }

    public function getMergeCashierSalesReport($verfiyLoginState = true)
    {
        $result = array("totalCount" => 0, "totalPrice" =>0, "salesList" => array());
        if ($verfiyLoginState && !F::loggedCommonVerify()) {
            return $result;
        }

        $public = F::getPublicData();
        $operation = F::getOperationData();

        $userId = $public['userId'];

        $start = @$operation['start'];
        $end = @$operation['end'];
        $sort = @$operation['sort'];
        $limit = @$operation['limit'];
        $pageNum = @$operation['pageNum'];

        if ($start && $end) {
            $start .= ' 00:00:00';
            $end .= ' 23:59:59';
        }

        //排序
        $csort = new CSort;
        if (!$sort) {
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
        if (!$pageNum) {
            $pageNum = 1;
        }
        if (!$limit) {
            $limit = 10;
        }
        $pageNum = $pageNum - 1;

        //合并记账台
        $mergecashier_criteria = new CDbCriteria;
        $mergecashier_criteria->addCondition('user_id=' . $userId);
        $mergecashier_criteria->addCondition("date >= '$start'");
        $mergecashier_criteria->addCondition("date <= '$end'");

        $mergecashier_result = MergeCashier::model()->findAll($mergecashier_criteria);
        $mergecashier_count = count($mergecashier_result);
        $mergecashier_pages = new CPagination($mergecashier_count);
        $mergecashier_pages->setPageSize($limit);
        $mergecashier_pages->setCurrentPage($pageNum);
        $mergecashier_pages->applyLimit($mergecashier_criteria);

        $mergecashier_records = array();


        foreach($mergecashier_result as $k => $v){
            $result['totalCount'] += $v -> totalSaleCount;
            $result['totalPrice'] += $v -> totalSalePrice;
        }

        $mergecashier_dataProvider = new CArrayDataProvider(
            $mergecashier_result,
            array(
                'sort' => $csort,
                'pagination' => $mergecashier_pages
            )
        );
        $mergecashier_lastPage = $mergecashier_count / $limit;
        if (is_float($mergecashier_lastPage)) {
            $mergecashier_lastPage = $mergecashier_lastPage + 1;
        }

        //如果不是最后一页
        if (($pageNum + 1) <= $mergecashier_lastPage) {
            foreach ($mergecashier_dataProvider->getData() as $k => $record) {
                array_push($mergecashier_records, array(
                    'isMerge' => 1,
                    'id' => $record->id,
                    'totalSalePrice' => $record->totalSalePrice,
                    'totalSaleCount' => $record->totalSaleCount,
                    'date' => $record->date,
                ));
            }
        }

        $result['salesList'] = $mergecashier_records;

        return $result;
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
