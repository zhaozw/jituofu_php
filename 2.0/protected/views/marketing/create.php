<?php
/* @var $this MarketingController */
/* @var $model Marketing */

$this->breadcrumbs=array(
	'Marketings'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Marketing', 'url'=>array('index')),
	array('label'=>'Manage Marketing', 'url'=>array('admin')),
);
?>

<h1>Create Marketing</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>