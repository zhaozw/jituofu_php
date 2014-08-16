<?php
/* @var $this NoticeController */
/* @var $model Notice */

$this->breadcrumbs=array(
	'Notices'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Notice', 'url'=>array('index')),
	array('label'=>'Create Notice', 'url'=>array('create')),
	array('label'=>'Update Notice', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Notice', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Notice', 'url'=>array('admin')),
);
?>

<h1>View Notice #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'content',
		'author',
		'min_version',
		'max_version',
		'date',
		'is_last',
		'position',
	),
)); ?>
