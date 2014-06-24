<?php
/* @var $this MarketingController */
/* @var $model Marketing */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'marketing-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'productVersion'); ?>
		<?php echo $form->textField($model,'productVersion',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'productVersion'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'productId'); ?>
		<?php echo $form->textField($model,'productId',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'productId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'channelId'); ?>
		<?php echo $form->textField($model,'channelId',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'channelId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'network'); ?>
		<?php echo $form->textField($model,'network',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'network'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'display'); ?>
		<?php echo $form->textField($model,'display',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'display'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'model'); ?>
		<?php echo $form->textField($model,'model',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'model'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'os'); ?>
		<?php echo $form->textField($model,'os',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'os'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'imsi'); ?>
		<?php echo $form->textField($model,'imsi',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'imsi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'imei'); ?>
		<?php echo $form->textField($model,'imei',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'imei'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'mac'); ?>
		<?php echo $form->textField($model,'mac',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'mac'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->