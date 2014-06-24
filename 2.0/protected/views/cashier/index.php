<?php
/* @var $this CashierController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Cashiers',
);

$this->menu=array(
	array('label'=>'Create Cashier', 'url'=>array('create')),
	array('label'=>'Manage Cashier', 'url'=>array('admin')),
);
?>

<h1>Cashiers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
