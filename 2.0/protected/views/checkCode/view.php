<?php
/* @var $this CheckCodeController */
/* @var $model CheckCode */

$this->breadcrumbs=array(
	'Check Codes'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List CheckCode', 'url'=>array('index')),
	array('label'=>'Create CheckCode', 'url'=>array('create')),
	array('label'=>'Update CheckCode', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete CheckCode', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CheckCode', 'url'=>array('admin')),
);
?>

<h1>View CheckCode #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'check_code',
		'time',
	),
)); ?>
