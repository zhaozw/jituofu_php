<?php
/* @var $this StoreSettingsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Store Settings',
);

$this->menu=array(
	array('label'=>'Create StoreSettings', 'url'=>array('create')),
	array('label'=>'Manage StoreSettings', 'url'=>array('admin')),
);
?>

<h1>Store Settings</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
