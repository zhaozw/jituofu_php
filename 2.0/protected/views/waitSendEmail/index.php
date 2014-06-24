<?php
/* @var $this WaitSendEmailController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Wait Send Emails',
);

$this->menu=array(
	array('label'=>'Create WaitSendEmail', 'url'=>array('create')),
	array('label'=>'Manage WaitSendEmail', 'url'=>array('admin')),
);
?>

<h1>Wait Send Emails</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
