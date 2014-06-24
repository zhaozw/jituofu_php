<?php
/* @var $this WseController */
/* @var $model Wse */

$this->breadcrumbs=array(
	'Wses'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Wse', 'url'=>array('index')),
	array('label'=>'Manage Wse', 'url'=>array('admin')),
);
?>

<h1>Create Wse</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>