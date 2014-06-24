<?php
/* @var $this WaitSendEmailController */
/* @var $model WaitSendEmail */

$this->breadcrumbs=array(
	'Wait Send Emails'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List WaitSendEmail', 'url'=>array('index')),
	array('label'=>'Create WaitSendEmail', 'url'=>array('create')),
	array('label'=>'View WaitSendEmail', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage WaitSendEmail', 'url'=>array('admin')),
);
?>

<h1>Update WaitSendEmail <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>