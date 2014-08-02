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
                'actions' => array('index', 'view', 'returnsale'),
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

    public function actionReturnsale()
    {
        if (F::loggedCommonVerify()) {
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $userId = $public['userId'];
            $id = @$operation['id'];//销售记录id
            $count = @$operation['count'];//退货数量
            $reason = @$operation['reason'];
            $remark = @$operation['remark'];
            $date = @$operation['date'];

            if(!$remark){
                $remark = "";
            }

            if (!$id) {
               return F::returnError(F::lang('RETURNSALE_ID_SPECIFY'));
            } else if (!is_numeric($id)) {
               return F::returnError(F::lang('RETURNSALE_ID_INVALID'));
            }

            if (!$count) {
                return F::returnError(F::lang('RETURNSALE_COUNT_SPECIFY'));
            }else if($count <= 0){
                return F::returnError(F::lang('RETURNSALE_COUNT_INVALID'));
            }

            if(!$reason){
                return F::returnError(F::lang('RETURNSALE_REASON_SPECIFY'));
            }

            $record = Cashier::model()->findByAttributes(array('id'=>$id, 'user_id' => $userId));

            if(!$record){
                return F::returnError(F::lang('RETURNSALE_NO_EXIST'));
            }
            $record_count = $record->getAttribute("selling_count");

            if($record_count < $count){
                return F::returnError(F::lang('RETURNSALE_COUNT_INVALID_MAX'));
            }

            $new_count = $record_count - $count;
            $record->selling_count = $new_count;
            if($record->save("selling_count")){
                $return_sale_model = new ReturnSale();
                $return_sale_model->attributes = array(
                    "user_id" => $userId,
                    "sale_id" => $id,
                    "reason" => $reason,
                    "remark" => $remark,
                    "date" => $date ? $date : F::getCurrentDatetime(),
                    "count" => $count,
                );
                if($return_sale_model -> save()){
                    F::returnSuccess(F::lang('RETURNSALE_SUCCESS', array("newSaleCount" => $new_count)));
                }else{
                    F::returnError(F::lang('RETURNSALE_ERROR'));
                }
            }
        }
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

    public function getCosts()
    {
        if (F::loggedCommonVerify(true)) {
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $this->prepareParameters();
            $cashier_cost_results = array();

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

            //获取合并记账成本列表
            $mergecashier_cost_results = $this->getMergeCashierCosts(false);

            if (($this->pageNum + 1) > $cashier_lastPage) {
                F::returnSuccess(F::lang('COMMON_QUERY_SUCCESS'), array("costs" => $mergecashier_cost_results));
                return;
            }

            foreach ($cashier_dataProvider->getData() as $k => $v) {
                $result = array("date" => $v->date, "cost" => "", "id" => $v->id);

                $selling_count = $v->selling_count;
                $price = $v->price;

                $result['cost'] = F::roundPrice($price * $selling_count);

                array_push($cashier_cost_results, $result);
            }

            //合并结果
            $totalResults = array();
            $date_array = array();//存储cashier数据的日期
            foreach ($cashier_cost_results as $k => $v) {
                $cdate = preg_split('/\\s/', $v['date']);
                $v['date'] = $cdate[0];

                //将记账里的日期与$totalResults中相同的日期，进行数据合并
                if(array_search($cdate[0], $date_array) !== false){
                    $pos = array_search($cdate[0], $date_array);
                    $totalResults[$pos]['cost'] += $v['cost'];
                }else{
                    array_push($totalResults, $v);
                    array_push($date_array, $cdate[0]);
                }
            }
            foreach ($mergecashier_cost_results as $k => $v) {
                $mcdate = preg_split('/\\s/', $v['date']);
                $v['date'] = $mcdate[0];
                //将合并记账里的日期与cashier中相同的日期，进行数据合并
                if(array_search($mcdate[0], $date_array) !== false){
                    $pos = array_search($mcdate[0], $date_array);
                    $totalResults[$pos]['cost'] += $v['cost'];
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

            function cbCmpDESC($a, $b)
            {

                if ($a['cost'] == $b['cost']) {
                    return 0;
                }

                return ($a['cost'] < $b['cost']) ? 1 : -1;
            }

            function dateCmpASC($a, $b)
            {

                if ($a['date'] == $b['date']) {
                    return 0;
                }

                return ($a['date'] < $b['date']) ? -1 : 1;
            }

            function cbCmpASC($a, $b)
            {

                if ($a['cost'] == $b['cost']) {
                    return 0;
                }

                return ($a['cost'] < $b['cost']) ? -1 : 1;
            }

            if ($this->sort == 1) {
                usort($totalResults, 'dateCmpDESC');
            } else if ($this->sort == 2) {
                usort($totalResults, 'dateCmpASC');
            } else if ($this->sort == 3) { //成本倒序
                usort($totalResults, 'cbCmpDESC');
            } else if ($this->sort == 4) { //成本升序
                usort($totalResults, 'cbCmpASC');
            }
            F::returnSuccess(F::lang('COMMON_QUERY_SUCCESS'), array("costs" => $totalResults));
        }
    }

    public function getMergeCashierCosts($verfiyLoginState = true)
    {
        $mergecashier_cost_results = array();

        if ($verfiyLoginState && !F::loggedCommonVerify()) {
            return $mergecashier_cost_results;
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
                $result = array("date" => $v->date, "cost" => "", "id" => $v->id);
                $cashier_record = Cashier::model()->findAllByAttributes(array('merge_id' => $v->id, 'user_id' => $this->uid));

                $costs = 0;
                if ($cashier_record && count($cashier_record) > 0) {
                    foreach ($cashier_record as $c => $ck) {
                        $selling_count = $ck->selling_count;
                        $price = $ck->price;

                        $costs += F::roundPrice($price * $selling_count);
                    }
                }
                $result['cost'] = $costs;
                array_push($mergecashier_cost_results, $result);
            }
        }

        return $mergecashier_cost_results;
    }

    public function getProfits()
    {
        if (F::loggedCommonVerify(true)) {
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
                $result = array("date" => $v->date, "profit" => "", "id" => $v->id);

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
                $v['date'] = $cdate[0];
                //将记账里的日期与$totalResults中相同的日期，进行数据合并
                if(array_search($cdate[0], $date_array) !== false){
                    $pos = array_search($cdate[0], $date_array);
                    $totalResults[$pos]['profit'] += $v['profit'];
                }else{
                    array_push($totalResults, $v);
                    array_push($date_array, $cdate[0]);
                }
            }
            foreach ($mergecashier_profit_results as $k => $v) {
                $mcdate = preg_split('/\\s/', $v['date']);
                $v['date'] = $mcdate[0];
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
                $result = array("date" => $v->date, "profit" => "", "id" => $v->id);
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

    public function getProducts()
    {
        if (F::loggedCommonVerify(true)) {
            $public = F::getPublicData();
            $operation = F::getOperationData();

            $this->prepareParameters();

            //返回给客户端的结果
            $result = array("products" => array());

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
            $mergecashier_records = $this->getMergeCashierProducts(false);

            //如果记账台的数据已经加载结束，尝试看看合并记账是否有数据
            if (($this->pageNum + 1) > $cashier_lastPage) {
                $result['products'] = $mergecashier_records['products'];
                F::returnSuccess(F::lang('COMMON_QUERY_SUCCESS'), $result);
                return;
            }

            foreach ($cashier_dataProvider->getData() as $k => $record) {
                $product_record = Products::model()->findByAttributes(array('id' => $record->pid, 'user_id' => $this->uid));
                $typeName = Types::getTypeNameById($product_record->type);
                array_push($cashier_records, array(
                    'isMerge' => 0,
                    'pic' => Files::getImg($product_record->pic),
                    'pid' => $product_record->id,
                    'id' => $record->id,
                    'name' => $product_record->name,
                    'typeName' => $typeName ? $typeName : "",
                    'selling_count' => $record->selling_count,
                    'selling_price' => $record->selling_price,
                    'price' => $record->price,
                    'date' => $record->date,
                    'remark' => $record->remark
                ));
            }

            //将合并记账列表push到cashier列表，然后再排序
            if (count($mergecashier_records['products']) > 0) {
                foreach ($mergecashier_records['products'] as $k => $record) {
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

                function cmpCountDESC($a, $b)
                {
                    $aIsMerge = $a['isMerge'];
                    $bIsMerge = $b['isMerge'];

                    $a_count = 0;
                    $b_count = 0;

                    if($aIsMerge == 0){
                        $a_count = $a['selling_count'];
                    }else if($aIsMerge == 1){
                        $a_count = $a['totalCount'];
                    }

                    if($bIsMerge == 0){
                        $b_count = $b['selling_count'];
                    }else if($bIsMerge == 1){
                        $b_count = $b['totalCount'];
                    }

                    if ($a_count == $b_count) {
                        return 0;
                    }

                    return ($a_count < $b_count) ? 1 : -1;
                }

                function cmpCountASC($a, $b)
                {

                    $aIsMerge = $a['isMerge'];
                    $bIsMerge = $b['isMerge'];

                    $a_count = 0;
                    $b_count = 0;

                    if($aIsMerge == 0){
                        $a_count = $a['selling_count'];
                    }else if($aIsMerge == 1){
                        $a_count = $a['totalCount'];
                    }

                    if($bIsMerge == 0){
                        $b_count = $b['selling_count'];
                    }else if($bIsMerge == 1){
                        $b_count = $b['totalCount'];
                    }

                    if ($a_count == $b_count) {
                        return 0;
                    }

                    return ($a_count < $b_count) ? -1 : 1;
                }

                if ($this->sort == 1) {
                    usort($cashier_records, 'cmpDESC');
                } else if ($this->sort == 2) {
                    usort($cashier_records, 'cmpASC');
                } else if ($this->sort == 3) {
                    usort($cashier_records, 'cmpCountDESC');
                } else if ($this->sort == 4) {
                    usort($cashier_records, 'cmpCountASC');
                }
            }
            $salesList = $cashier_records;

            $result['products'] = $salesList;

            F::returnSuccess(F::lang('COMMON_QUERY_SUCCESS'), $result);
        }
    }

    public function getMergeCashierProducts($verfiyLoginState = true)
    {
        $result = array("products" => array());

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

                $products = array();
                foreach($cashier_record as $kk => $vv){
                    $product_record = Products::model()->findByAttributes(array('id' => $vv->pid, 'user_id' => $this->uid));
                    $typeName = Types::getTypeNameById($product_record->type);
                    $product_data = array(
                        'pic' => Files::getImg($product_record->pic),
                        'pid' => $product_record->id,
                        'name' => $product_record->name,
                        'typeName' => $typeName ? $typeName : ""
                    );
                    //避免$products中出现重复的商品信息
                    if(array_search($product_data, $products) === false){
                        array_push($products, $product_data);
                    }
                }
                array_push($mergecashier_records, array(
                    'isMerge' => 1,
                    'id' => $record->id,
                    'totalPrice' => $totalPrice,
                    'totalCount' => $totalCount,
                    'date' => $record->date,
                    'cashierList' => $cashier_record,
                    'products' => $products
                ));
            }
        }

        $result['products'] = $mergecashier_records;

        return $result;
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

            //成本报表
            if ($reportType && $reportType === "costs") {
                return $this->getCosts();
            }

            //售出商品列表
            if ($reportType && $reportType === "products") {
                return $this->getProducts();
            }

            $this->prepareParameters();

            //返回给客户端的结果
            $result = array("totalCost" => 0, "totalCount" => 0, "totalPrice" => 0);

            //记账台
            $cashier_criteria = new CDbCriteria;
            $cashier_criteria->addCondition('user_id=' . $this->uid);
            $cashier_criteria->addCondition("merge_id IS NULL");
            $cashier_criteria->addCondition("date >= '$this->start'");
            $cashier_criteria->addCondition("date <= '$this->end'");

            $cashier_result = Cashier::model()->findAll($cashier_criteria);

            $cashier_records = array();

            //统计记账台的总销售量和总销售额
            foreach ($cashier_result as $k => $v) {
                $result['totalCost'] += F::roundPrice($v->price * $v->selling_count);
                $result['totalCount'] += $v->selling_count;
                $result['totalPrice'] += F::roundPrice($v->selling_price * $v->selling_count);
            }

            //获取合并记账台的相关数据
            $mergecashier_records = $this->getMergeCashierSalesReport(false);

            //汇总合并记账表和记账表中的总销售量和总销售额的数据
            $result['totalCount'] += $mergecashier_records['totalCount'];
            $result['totalPrice'] += $mergecashier_records['totalPrice'];
            $result['totalCost'] += $mergecashier_records['totalCost'];

            F::returnSuccess(F::lang('COMMON_QUERY_SUCCESS'), $result);
        }
    }

    public function getMergeCashierSalesReport($verfiyLoginState = true)
    {
        $result = array("totalCost" => 0, "totalCount" => 0, "totalPrice" => 0);

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
