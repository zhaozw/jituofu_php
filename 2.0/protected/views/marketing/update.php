<?php
/* @var $this MarketingController */
/* @var $model Marketing */

$this->breadcrumbs=array(
	'Marketings'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Marketing', 'url'=>array('index')),
	array('label'=>'Create Marketing', 'url'=>array('create')),
	array('label'=>'View Marketing', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Marketing', 'url'=>array('admin')),
);
?>

<h1>Update Marketing <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>