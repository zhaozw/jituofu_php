<?php
/* @var $this MarketingController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Marketings',
);

$this->menu=array(
	array('label'=>'Create Marketing', 'url'=>array('create')),
	array('label'=>'Manage Marketing', 'url'=>array('admin')),
);
?>

<h1>Marketings</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
