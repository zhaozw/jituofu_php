<?php
/* @var $this CashierController */
/* @var $model Cashier */

$this->breadcrumbs=array(
	'Cashiers'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Cashier', 'url'=>array('index')),
	array('label'=>'Create Cashier', 'url'=>array('create')),
	array('label'=>'View Cashier', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Cashier', 'url'=>array('admin')),
);
?>

<h1>Update Cashier <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>