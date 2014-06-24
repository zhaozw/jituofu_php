<?php

class CashierController extends Controller
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

            if(!is_array($operation)){
                F::returnError(F::lang('CASHIER_NOT_IS_ARRAY'));
            }
            if(count($operation) <= 0){
                F::returnError(F::lang('CASHIER_EMPTY_DATA'));
            }

            $isMerge = 0;//是否是合并记账
            if(count($operation) >= 2){
                $isMerge = 1;
            }

            $isAddedMergerCashier = 0;//有否已经记录合并记账
            $totalCount = 0;//总销售数量
            $totalSellingPrice = 0;//总销售价格
            $mergerCashierDate = F::getCurrentDatetime();
            $mergerId = null;//合并记录的id

            foreach($operation as $k => $p){
                //如果没有商品id,则该条商品是手动输入记账台的,必须校验商品名称/进货价
                if(!@$p['pid']){
                    //校验名称
                    $name = @F::trimAll($p['name']);
                    if(!$name){
                        F::returnError(F::lang("PRODUCT_NAME_SPECIFY"), array("which"=>$k+1));
                        break;
                    }else if(F::minMaxRange(2, 15, $name)){
                        F::returnError(F::lang("PRODUCT_CHAR_LIMIT", array(2, 15)), array("which"=>$k+1));
                        break;
                    }
                    //校验进货价
                    $price = @F::trimAll($p['price']);
                    if(!$price){
                        F::returnError(F::lang("PRODUCT_PRICE_SPECIFY"), array("which"=>$k+1));
                        break;
                    }else if(!is_numeric($price)){
                        F::returnError(F::lang('PRODUCT_PRICE_INVALID'), array("which"=>$k+1));
                        break;
                    }
                }
                //校验销售数量
                $count = @F::trimAll($p['count']);
                if(!$count){
                    F::returnError(F::lang("CASHIER_COUNT_SPECIFY"), array("which"=>$k+1));
                    break;
                }else if(!is_numeric($count)){
                    F::returnError(F::lang("CASHIER_COUNT_INVALID"), array("which"=>$k+1));
                    break;
                }
                //校验销售价格
                $sellingPrice = @F::trimAll($p['sellingPrice']);
                if(!$sellingPrice){
                    F::returnError(F::lang("CASHIER_SELLINGPRICE_SPECIFY"), array("which"=>$k+1));
                    break;
                }else if(!is_numeric($sellingPrice)){
                    F::returnError(F::lang("CASHIER_SELLINGPRICE_INVALID"), array("which"=>$k+1));
                    break;
                }

                $totalCount += $count;
                $totalSellingPrice += $sellingPrice*$count;
                $mergerCashierDate = $p['date'] ? $p['date'] : F::getCurrentDatetime();//以最后一条商品的date做为合并记账的date

                //检查商品是否存在
                if(@$p['pid'] && !Products::isExistById($p['pid'])){
                    F::returnError(F::lang("PRODUCT_NO_EXIST"), array("which"=>$k+1));
                    break;
                }
            }

            //添加合并记账
            if($isAddedMergerCashier === 0 && $isMerge){
                $mergerId = MergeCashier::add(array(
                    'user_id' => $public['userId'],
                    'totalSellingPrice' => $totalSellingPrice,
                    'totalCount' => $totalCount,
                    'date' => $mergerCashierDate

                ));
                if(!$mergerId){
                    F::returnError(F::lang('CASHIER_MERGERCASHIER_ERROR'));
                }
                $isAddedMergerCashier = 1;
            }

            $savedCount = 0;//成功保存到cashier表的计数器
            foreach($operation as $k => $p){
                $remark = @F::trimAll($p['remark']);
                $sellingPrice = F::trimAll($p['sellingPrice']);
                $date = $p['date'] ? $p['date'] : F::getCurrentDatetime();
                $count = $p['count'];
                $pid = @$p['pid'];
                //商品入库
                if(!$pid){
                    $defaultType = Types::getDefaultType($public['userId'], "默认分类");
                    $name = $p['name'];
                    $count = $p['count'];
                    $price = $p['price'];
                    $pic = "";
                    $type = $defaultType->id;//使用默认的商品分类
                    $remark = $remark;

                    $model=new Products;
                    $model->attributes=array(
                        "user_id" => $public['userId'],
                        "name" => $name,
                        "count" => $count,
                        "price" => $price,
                        "pic" => $pic,
                        "type" => $type,
                        "remark" => $remark,
                        "date" => $date,
                        "status" => 1
                    );
                    $product = Products::add($model, false);
                    if(!$product){
                        F::returnError(F::lang('PRODUCT_ADD_ERROR'), $model->getErrors());
                        break;
                    }else{
                        $pid = $product['id'];
                    }
                }

                if(!$pid){
                    F::returnError(F::lang("CASHIER_PID_SPECIFY"), array("which"=>$k+1));
                    break;
                }
                $model = new Cashier();
                if($isMerge){
                    $model->attributes = array(
                        'user_id' => $public['userId'],
                        'pid' => $pid,
                        'count' => $count,
                        'selling_price' => $sellingPrice,
                        'date' => $date,
                        'remark' => $remark,
                        'merge_id' => $mergerId
                    );
                }else{
                    $model->attributes = array(
                        'user_id' => $public['userId'],
                        'pid' => $pid,
                        'count' => $count,
                        'selling_price' => $sellingPrice,
                        'date' => $date,
                        'remark' => $remark
                    );
                }
                if($model->save()){
                    $this->updateProductCount($pid, $count);
                    $savedCount++;
                }else{
                    F::error("记账失败 " . CJSON::encode($model->getErrors()));
                    break;
                }
            }

            if($savedCount === count($operation)){
                if(!$isMerge){
                    F::returnSuccess(F::lang('CASHIER_SUCCESS'));
                }else{
                    F::returnSuccess(F::lang('CASHIER_MERGERCASHIER_SUCCESS'));
                }
            }else{
                if(!$isMerge){
                    F::returnError(F::lang('CASHIER_ERROR'));
                }else{
                    F::returnError(F::lang('CASHIER_MERGERCASHIER_ERROR'));
                }
            }
        }
	}

    /**
     * 更新商品库存
     * @param $pid
     * @param $count
     */
    private function updateProductCount($pid, $count){
        $record = Products::isExistById($pid);
        if($record){
            $sellingCount = $count;
            $count = $record->getAttribute('count');
            $newCount = $count - $sellingCount;

            if($record->updateByPk($pid, array('count' => $newCount)) > 0){
                return true;
            }else{
                F::error("更新商品 $pid 的库存失败");
                return false;
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

		if(isset($_POST['Cashier']))
		{
			$model->attributes=$_POST['Cashier'];
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
		$dataProvider=new CActiveDataProvider('Cashier');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Cashier('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Cashier']))
			$model->attributes=$_GET['Cashier'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Cashier the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Cashier::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Cashier $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cashier-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
