<?php
/* @var $this StoreSettingsController */
/* @var $model StoreSettings */

$this->breadcrumbs=array(
	'Store Settings'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List StoreSettings', 'url'=>array('index')),
	array('label'=>'Create StoreSettings', 'url'=>array('create')),
	array('label'=>'Update StoreSettings', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete StoreSettings', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage StoreSettings', 'url'=>array('admin')),
);
?>

<h1>View StoreSettings #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'tip_rent',
        'name'
	),
)); ?>
