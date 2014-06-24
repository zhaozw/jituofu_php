<?php
/* @var $this StoreSettingsController */
/* @var $model StoreSettings */

$this->breadcrumbs=array(
	'Store Settings'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List StoreSettings', 'url'=>array('index')),
	array('label'=>'Manage StoreSettings', 'url'=>array('admin')),
);
?>

<h1>Create StoreSettings</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>