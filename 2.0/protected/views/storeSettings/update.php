<?php
/* @var $this StoreSettingsController */
/* @var $model StoreSettings */

$this->breadcrumbs=array(
	'Store Settings'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List StoreSettings', 'url'=>array('index')),
	array('label'=>'Create StoreSettings', 'url'=>array('create')),
	array('label'=>'View StoreSettings', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage StoreSettings', 'url'=>array('admin')),
);
?>

<h1>Update StoreSettings <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>