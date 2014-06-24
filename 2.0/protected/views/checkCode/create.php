<?php
/* @var $this CheckCodeController */
/* @var $model CheckCode */

$this->breadcrumbs=array(
	'Check Codes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CheckCode', 'url'=>array('index')),
	array('label'=>'Manage CheckCode', 'url'=>array('admin')),
);
?>

<h1>Create CheckCode</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>