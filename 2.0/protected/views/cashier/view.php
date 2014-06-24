<?php
/* @var $this CashierController */
/* @var $model Cashier */

$this->breadcrumbs=array(
	'Cashiers'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Cashier', 'url'=>array('index')),
	array('label'=>'Create Cashier', 'url'=>array('create')),
	array('label'=>'Update Cashier', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Cashier', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Cashier', 'url'=>array('admin')),
);
?>

<h1>View Cashier #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'pid',
		'count',
		'selling_price',
		'who',
		'date',
		'remark',
		'merge_id',
	),
)); ?>
