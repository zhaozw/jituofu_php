<?php
/* @var $this PicsController */
/* @var $model Pics */

$this->breadcrumbs=array(
	'Pics'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Pics', 'url'=>array('index')),
	array('label'=>'Manage Pics', 'url'=>array('admin')),
);
?>

<h1>Create Pics</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>