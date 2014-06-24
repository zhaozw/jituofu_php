<?php
/* @var $this MarketingController */
/* @var $data Marketing */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('productVersion')); ?>:</b>
	<?php echo CHtml::encode($data->productVersion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('productId')); ?>:</b>
	<?php echo CHtml::encode($data->productId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('channelId')); ?>:</b>
	<?php echo CHtml::encode($data->channelId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('network')); ?>:</b>
	<?php echo CHtml::encode($data->network); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('display')); ?>:</b>
	<?php echo CHtml::encode($data->display); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('model')); ?>:</b>
	<?php echo CHtml::encode($data->model); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('os')); ?>:</b>
	<?php echo CHtml::encode($data->os); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('imsi')); ?>:</b>
	<?php echo CHtml::encode($data->imsi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('imei')); ?>:</b>
	<?php echo CHtml::encode($data->imei); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mac')); ?>:</b>
	<?php echo CHtml::encode($data->mac); ?>
	<br />

	*/ ?>

</div>