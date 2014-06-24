<?php
/* @var $this WaitSendEmailController */
/* @var $model WaitSendEmail */

$this->breadcrumbs=array(
	'Wait Send Emails'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List WaitSendEmail', 'url'=>array('index')),
	array('label'=>'Manage WaitSendEmail', 'url'=>array('admin')),
);
?>

<h1>Create WaitSendEmail</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>