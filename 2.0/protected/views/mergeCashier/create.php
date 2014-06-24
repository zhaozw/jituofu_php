<?php
/* @var $this MergeCashierController */
/* @var $model MergeCashier */

$this->breadcrumbs=array(
	'Merge Cashiers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List MergeCashier', 'url'=>array('index')),
	array('label'=>'Manage MergeCashier', 'url'=>array('admin')),
);
?>

<h1>Create MergeCashier</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>