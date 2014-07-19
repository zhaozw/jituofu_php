<?php
/* @var $this CashierController */
/* @var $data Cashier */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pid')); ?>:</b>
	<?php echo CHtml::encode($data->pid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('selling_count')); ?>:</b>
	<?php echo CHtml::encode($data->selling_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('selling_price')); ?>:</b>
	<?php echo CHtml::encode($data->selling_price); ?>
	<br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('price')); ?>:</b>
    <?php echo CHtml::encode($data->price); ?>
    <br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('who')); ?>:</b>
	<?php echo CHtml::encode($data->who); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('remark')); ?>:</b>
	<?php echo CHtml::encode($data->remark); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('merge_id')); ?>:</b>
	<?php echo CHtml::encode($data->merge_id); ?>
	<br />

	*/ ?>

</div>