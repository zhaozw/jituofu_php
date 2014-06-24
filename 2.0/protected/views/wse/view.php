<?php
/* @var $this WseController */
/* @var $model Wse */

$this->breadcrumbs=array(
	'Wses'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Wse', 'url'=>array('index')),
	array('label'=>'Create Wse', 'url'=>array('create')),
	array('label'=>'Update Wse', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Wse', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Wse', 'url'=>array('admin')),
);
?>

<h1>View Wse #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'address',
		'subject',
		'content',
		'time',
	),
)); ?>
