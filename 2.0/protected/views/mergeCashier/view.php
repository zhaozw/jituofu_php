<?php
/* @var $this MergeCashierController */
/* @var $model MergeCashier */

$this->breadcrumbs=array(
	'Merge Cashiers'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List MergeCashier', 'url'=>array('index')),
	array('label'=>'Create MergeCashier', 'url'=>array('create')),
	array('label'=>'Update MergeCashier', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete MergeCashier', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage MergeCashier', 'url'=>array('admin')),
);
?>

<h1>View MergeCashier #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'totalSellingPrice',
		'totalCount',
		'date',
	),
)); ?>
