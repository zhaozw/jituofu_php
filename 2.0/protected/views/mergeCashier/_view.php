<?php
/* @var $this MergeCashierController */
/* @var $data MergeCashier */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('totalSalePrice')); ?>:</b>
	<?php echo CHtml::encode($data->totalSalePrice); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('totalSaleCount')); ?>:</b>
	<?php echo CHtml::encode($data->totalSaleCount); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />


</div>