<?php
/* @var $this CashierController */
/* @var $model Cashier */

$this->breadcrumbs=array(
	'Cashiers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Cashier', 'url'=>array('index')),
	array('label'=>'Manage Cashier', 'url'=>array('admin')),
);
?>

<h1>Create Cashier</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>