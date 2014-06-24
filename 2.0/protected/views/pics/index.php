<?php
/* @var $this PicsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Pics',
);

$this->menu=array(
	array('label'=>'Create Pics', 'url'=>array('create')),
	array('label'=>'Manage Pics', 'url'=>array('admin')),
);
?>

<h1>Pics</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
