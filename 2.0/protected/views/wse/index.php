<?php
/* @var $this WseController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Wses',
);

$this->menu=array(
	array('label'=>'Create Wse', 'url'=>array('create')),
	array('label'=>'Manage Wse', 'url'=>array('admin')),
);
?>

<h1>Wses</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
