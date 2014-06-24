<?php
/* @var $this MarketingController */
/* @var $model Marketing */

$this->breadcrumbs=array(
	'Marketings'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Marketing', 'url'=>array('index')),
	array('label'=>'Create Marketing', 'url'=>array('create')),
	array('label'=>'Update Marketing', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Marketing', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Marketing', 'url'=>array('admin')),
);
?>

<h1>View Marketing #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'productVersion',
		'productId',
		'channelId',
		'network',
		'display',
		'model',
		'os',
		'imsi',
		'imei',
		'mac',
	),
)); ?>
