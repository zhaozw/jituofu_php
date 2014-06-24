<?php
/* @var $this MergeCashierController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Merge Cashiers',
);

$this->menu=array(
	array('label'=>'Create MergeCashier', 'url'=>array('create')),
	array('label'=>'Manage MergeCashier', 'url'=>array('admin')),
);
?>

<h1>Merge Cashiers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
