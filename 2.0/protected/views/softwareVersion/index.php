<?php
/* @var $this SoftwareVersionController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Software Versions',
);

$this->menu=array(
	array('label'=>'Create SoftwareVersion', 'url'=>array('create')),
	array('label'=>'Manage SoftwareVersion', 'url'=>array('admin')),
);
?>

<h1>Software Versions</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
