<?php

class SalesReportController extends Controller
{
    public $start, $end, $sort, $limit, $pageNum, $uid;
    public $csort;

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

    public function prepareParameters()
    {
        $operation = F::getOperationData();
        $public = F::getPublicData();

        $this->uid = $public['userId'];;

        $this->start = @$operation['start'];
        $this->end = @$operation['end'];
        $this->sort = @$operation['sort'];
        $this->limit = @$operation['limit'];
        $this->pageNum = @$operation['pageNum'];

        if ($this->start && $this->end) {
            $this->start .= ' 00:00:00';
            $this->end .= ' 23:59:59';
        }

        //排序
        $this->csort = new CSort;
        if (!$this->sort) {
            $this->sort = 1;
        }
        if ($this->sort == 1) {
            //date倒序
            $this->csort->defaultOrder = 'date DESC';
        } else if ($this->sort == 2) {
            //date升序
            $this->csort->defaultOrder = 'date ASC';
        }


        //翻页
        if (!$this->pageNum) {
            $this->pageNum = 1;
        }
        if (!$this->limit) {
            $this->limit = 10;
        }
        $this->pageNum = $this->pageNum - 1;
    }

    public function getProfits()
    {
        if (F::loggedCommonVerify()) {
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $this->prepareParameters();
            $cashier_profit_results = array();

            //记账台相关的数据
            $cashier_criteria = new CDbCriteria;
            $cashier_criteria->addCondition('user_id=' . $this->uid);
            $cashier_criteria->addCondition("merge_id IS NULL");
            $cashier_criteria->addCondition("date >= '$this->start'");
            $cashier_criteria->addCondition("date <= '$this->end'");

            $cashier_result = Cashier::model()->findAll($cashier_criteria);
            $cashier_count = count($cashier_result);
            $cashier_pages = new CPagination($cashier_count);
            $cashier_pages->setPageSize($this->limit);
            $cashier_pages->setCurrentPage($this->pageNum);
            $cashier_pages->applyLimit($cashier_criteria);

            $cashier_dataProvider = new CArrayDataProvider(
                $cashier_result,
                array(
                    'sort' => $this->csort,
                    'pagination' => $cashier_pages
                )
            );

            $cashier_lastPage = $cashier_count / $this->limit;
            if (is_float($cashier_lastPage)) {
                $cashier_lastPage = $cashier_lastPage + 1;
            }

            //获取合并记账利润列表
            $mergecashier_profit_results = $this->getMergeCashierProfits(false);

            if (($this->pageNum + 1) > $cashier_lastPage) {
                F::returnSuccess(F::lang('COMMON_QUERY_SUCCESS'), array("profits" => $mergecashier_profit_results));
                return;
            }

            foreach ($cashier_dataProvider->getData() as $k => $v) {
                $result = array("date" => $v->date, "profit" => "", "id" => $v->id, "isMerge" => 0);

                $selling_count = $v->selling_count;
                $price = $v->price;
                $selling_price = $v->selling_price;

                $result['profit'] = F::roundPrice($selling_price * $selling_count) - F::roundPrice($price * $selling_count);

                array_push($cashier_profit_results, $result);
            }

            //合并结果
            $totalResults = array();
            $date_array = array();//存储cashier数据的日期
            foreach ($cashier_profit_results as $k => $v) {
                $cdate = preg_split('/\\s/', $v['date']);
                array_push($totalResults, $v);
                array_push($date_array, $cdate[0]);
            }
            foreach ($mergecashier_profit_results as $k => $v) {
                $mcdate = preg_split('/\\s/', $v['date']);
                //将合并记账里的日期与cashier中相同的日期，进行数据合并
                if(array_search($mcdate[0], $date_array) !== false){
                    $pos = array_search($mcdate[0], $date_array);
                    $totalResults[$pos]['profit'] += $v['profit'];
                }else{
                    array_push($totalResults, $v);
                }
            }

            //排序
            function dateCmpDESC($a, $b)
            {

                if ($a['date'] == $b['date']) {
                    return 0;
                }

                return ($a['date'] < $b['date']) ? 1 : -1;
            }

            function lrCmpDESC($a, $b)
            {

                if ($a['profit'] == $b['profit']) {
                    return 0;
                }

                return ($a['profit'] < $b['profit']) ? 1 : -1;
            }

            function dateCmpASC($a, $b)
            {

                if ($a['date'] == $b['date']) {
                    return 0;
                }

                return ($a['date'] < $b['date']) ? -1 : 1;
            }

            function lrCmpASC($a, $b)
            {

                if ($a['profit'] == $b['profit']) {
                    return 0;
                }

                return ($a['profit'] < $b['profit']) ? -1 : 1;
            }

            if ($this->sort == 1) {
                usort($totalResults, 'dateCmpDESC');
            } else if ($this->sort == 2) {
                usort($totalResults, 'dateCmpASC');
            } else if ($this->sort == 3) { //利润倒序
                usort($totalResults, 'lrCmpDESC');
            } else if ($this->sort == 4) { //利润升序
                usort($totalResults, 'lrCmpASC');
            }
            F::returnSuccess(F::lang('COMMON_QUERY_SUCCESS'), array("profits" => $totalResults));
        }
    }

    public function getMergeCashierProfits($verfiyLoginState = true)
    {
        $mergecashier_profit_results = array();

        if ($verfiyLoginState && !F::loggedCommonVerify()) {
            return $mergecashier_profit_results;
        }

        $this->prepareParameters();

        //合并记账台相关数据
        $mergecashier_criteria = new CDbCriteria;
        $mergecashier_criteria->addCondition('user_id=' . $this->uid);
        $mergecashier_criteria->addCondition("date >= '$this->start'");
        $mergecashier_criteria->addCondition("date <= '$this->end'");

        $mergecashier_result = MergeCashier::model()->findAll($mergecashier_criteria);
        $mergecashier_count = count($mergecashier_result);
        $mergecashier_pages = new CPagination($mergecashier_count);
        $mergecashier_pages->setPageSize($this->limit);
        $mergecashier_pages->setCurrentPage($this->pageNum);
        $mergecashier_pages->applyLimit($mergecashier_criteria);

        $mergecashier_dataProvider = new CArrayDataProvider(
            $mergecashier_result,
            array(
                'sort' => $this->csort,
                'pagination' => $mergecashier_pages
            )
        );

        $mergecashier_lastPage = $mergecashier_count / $this->limit;
        if (is_float($mergecashier_lastPage)) {
            $mergecashier_lastPage = $mergecashier_lastPage + 1;
        }
        if (($this->pageNum + 1) <= $mergecashier_lastPage) {
            foreach ($mergecashier_dataProvider->getData() as $k => $v) {
                $result = array("date" => $v->date, "profit" => "", "id" => $v->id, "isMerge" => 1);
                $cashier_record = Cashier::model()->findAllByAttributes(array('merge_id' => $v->id, 'user_id' => $this->uid));

                $profits = 0;
                if ($cashier_record && count($cashier_record) > 0) {
                    foreach ($cashier_record as $c => $ck) {
                        $selling_count = $ck->selling_count;
                        $price = $ck->price;
                        $selling_price = $ck->selling_price;

                        $profits += F::roundPrice($selling_price * $selling_count) - F::roundPrice($price * $selling_count);
                    }
                }
                $result['profit'] = $profits;
                array_push($mergecashier_profit_results, $result);
            }
        }

        return $mergecashier_profit_results;
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        if (F::loggedCommonVerify(true)) {
            $public = F::getPublicData();
            $operation = F::getOperationData();

            //报表类型
            $reportType = @$operation['reportType'];

            //利润报表
            if ($reportType && $reportType === "profits") {
                return $this->getProfits();
            }

            $this->prepareParameters();

            //返回给客户端的结果
            $result = array("totalCost" => 0, "totalCount" => 0, "totalPrice" => 0, "salesList" => array());

            //记账台
            $cashier_criteria = new CDbCriteria;
            $cashier_criteria->addCondition('user_id=' . $this->uid);
            $cashier_criteria->addCondition("merge_id IS NULL");
            $cashier_criteria->addCondition("date >= '$this->start'");
            $cashier_criteria->addCondition("date <= '$this->end'");

            $cashier_result = Cashier::model()->findAll($cashier_criteria);
            $cashier_count = count($cashier_result);
            $cashier_pages = new CPagination($cashier_count);
            $cashier_pages->setPageSize($this->limit);
            $cashier_pages->setCurrentPage($this->pageNum);
            $cashier_pages->applyLimit($cashier_criteria);

            $cashier_records = array();

            //统计记账台的总销售量和总销售额
            foreach ($cashier_result as $k => $v) {
                $result['totalCost'] += F::roundPrice($v->price * $v->selling_count);
                $result['totalCount'] += $v->selling_count;
                $result['totalPrice'] += F::roundPrice($v->selling_price * $v->selling_count);
            }

            $cashier_dataProvider = new CArrayDataProvider(
                $cashier_result,
                array(
                    'sort' => $this->csort,
                    'pagination' => $cashier_pages
                )
            );
            $cashier_lastPage = $cashier_count / $this->limit;
            if (is_float($cashier_lastPage)) {
                $cashier_lastPage = $cashier_lastPage + 1;
            }

            //获取合并记账台的相关数据
            $mergecashier_records = $this->getMergeCashierSalesReport(false);

            //汇总合并记账表和记账表中的总销售量和总销售额的数据
            $result['totalCount'] += $mergecashier_records['totalCount'];
            $result['totalPrice'] += $mergecashier_records['totalPrice'];
            $result['totalCost'] += $mergecashier_records['totalCost'];

            //如果记账台的数据已经加载结束，尝试看看合并记账是否有数据
            if (($this->pageNum + 1) > $cashier_lastPage) {
                $result['salesList'] = $mergecashier_records['salesList'];
                F::returnSuccess(F::lang('COMMON_QUERY_SUCCESS'), $result);
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

                if ($this->sort == 1) {
                    usort($cashier_records, 'cmpDESC');
                } else if ($this->sort == 2) {
                    usort($cashier_records, 'cmpASC');
                }
            }
            $salesList = $cashier_records;

            $result['salesList'] = $salesList;
            $result['totalPrice'] = $result['totalPrice'];

            F::returnSuccess(F::lang('COMMON_QUERY_SUCCESS'), $result);
        }
    }

    public function getMergeCashierSalesReport($verfiyLoginState = true)
    {
        $result = array("totalCost" => 0, "totalCount" => 0, "totalPrice" => 0, "salesList" => array());

        if ($verfiyLoginState && !F::loggedCommonVerify()) {
            return $result;
        }

        $public = F::getPublicData();
        $operation = F::getOperationData();

        $this->prepareParameters();

        //合并记账台
        $mergecashier_criteria = new CDbCriteria;
        $mergecashier_criteria->addCondition('user_id=' . $this->uid);
        $mergecashier_criteria->addCondition("date >= '$this->start'");
        $mergecashier_criteria->addCondition("date <= '$this->end'");

        $mergecashier_result = MergeCashier::model()->findAll($mergecashier_criteria);
        $mergecashier_count = count($mergecashier_result);
        $mergecashier_pages = new CPagination($mergecashier_count);
        $mergecashier_pages->setPageSize($this->limit);
        $mergecashier_pages->setCurrentPage($this->pageNum);
        $mergecashier_pages->applyLimit($mergecashier_criteria);

        $mergecashier_records = array();

        foreach ($mergecashier_result as $k => $v) {
            $cashier_record = Cashier::model()->findAllByAttributes(array('merge_id' => $v->id, 'user_id' => $this->uid));
            if ($cashier_record && count($cashier_record) > 0) {
                foreach ($cashier_record as $c => $ck) {
                    $result['totalCount'] += $ck->selling_count;
                    $result['totalPrice'] += F::roundPrice($ck->selling_count * $ck->selling_price);
                    $result['totalCost'] += F::roundPrice($ck->price * $ck->selling_count);
                }
            }
        }

        $mergecashier_dataProvider = new CArrayDataProvider(
            $mergecashier_result,
            array(
                'sort' => $this->csort,
                'pagination' => $mergecashier_pages
            )
        );
        $mergecashier_lastPage = $mergecashier_count / $this->limit;
        if (is_float($mergecashier_lastPage)) {
            $mergecashier_lastPage = $mergecashier_lastPage + 1;
        }

        //如果不是最后一页
        if (($this->pageNum + 1) <= $mergecashier_lastPage) {
            foreach ($mergecashier_dataProvider->getData() as $k => $record) {
                $cashier_record = Cashier::model()->findAllByAttributes(array('merge_id' => $record->id, 'user_id' => $this->uid));
                $totalCount = 0;
                $totalPrice = 0;
                $totalCost = 0;
                if ($cashier_record && count($cashier_record) > 0) {
                    foreach ($cashier_record as $c => $ck) {
                        $totalCount += $ck->selling_count;
                        $totalPrice += F::roundPrice($ck->selling_count * $ck->selling_price);
                        $totalCost += F::roundPrice($ck->price * $ck->selling_count);
                    }
                }
                array_push($mergecashier_records, array(
                    'isMerge' => 1,
                    'id' => $record->id,
                    'totalCost' => $totalCost,
                    'totalSalePrice' => $totalPrice,
                    'totalSaleCount' => $totalCount,
                    'date' => $record->date,
                    'list' => $cashier_record
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
