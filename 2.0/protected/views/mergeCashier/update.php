<?php
/* @var $this MergeCashierController */
/* @var $model MergeCashier */

$this->breadcrumbs=array(
	'Merge Cashiers'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List MergeCashier', 'url'=>array('index')),
	array('label'=>'Create MergeCashier', 'url'=>array('create')),
	array('label'=>'View MergeCashier', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage MergeCashier', 'url'=>array('admin')),
);
?>

<h1>Update MergeCashier <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>