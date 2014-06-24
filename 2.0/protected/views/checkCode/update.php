<?php
/* @var $this CheckCodeController */
/* @var $model CheckCode */

$this->breadcrumbs=array(
	'Check Codes'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CheckCode', 'url'=>array('index')),
	array('label'=>'Create CheckCode', 'url'=>array('create')),
	array('label'=>'View CheckCode', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CheckCode', 'url'=>array('admin')),
);
?>

<h1>Update CheckCode <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>