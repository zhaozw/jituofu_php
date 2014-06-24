<?php
/* @var $this WseController */
/* @var $model Wse */

$this->breadcrumbs=array(
	'Wses'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Wse', 'url'=>array('index')),
	array('label'=>'Create Wse', 'url'=>array('create')),
	array('label'=>'View Wse', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Wse', 'url'=>array('admin')),
);
?>

<h1>Update Wse <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>