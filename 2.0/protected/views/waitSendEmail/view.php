<?php
/* @var $this WaitSendEmailController */
/* @var $model WaitSendEmail */

$this->breadcrumbs=array(
	'Wait Send Emails'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List WaitSendEmail', 'url'=>array('index')),
	array('label'=>'Create WaitSendEmail', 'url'=>array('create')),
	array('label'=>'Update WaitSendEmail', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete WaitSendEmail', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WaitSendEmail', 'url'=>array('admin')),
);
?>

<h1>View WaitSendEmail #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'address',
		'subject',
		'content',
	),
)); ?>
