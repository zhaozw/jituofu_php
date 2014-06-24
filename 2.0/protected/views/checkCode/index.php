<?php
/* @var $this CheckCodeController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Check Codes',
);

$this->menu=array(
	array('label'=>'Create CheckCode', 'url'=>array('create')),
	array('label'=>'Manage CheckCode', 'url'=>array('admin')),
);
?>

<h1>Check Codes</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
