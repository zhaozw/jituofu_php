<?php
/* @var $this PicsController */
/* @var $model Pics */

$this->breadcrumbs=array(
	'Pics'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Pics', 'url'=>array('index')),
	array('label'=>'Create Pics', 'url'=>array('create')),
	array('label'=>'View Pics', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Pics', 'url'=>array('admin')),
);
?>

<h1>Update Pics <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>