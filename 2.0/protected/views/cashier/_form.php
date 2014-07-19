<?php
/* @var $this CashierController */
/* @var $model Cashier */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cashier-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
		<?php echo $form->error($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pid'); ?>
		<?php echo $form->textField($model,'pid'); ?>
		<?php echo $form->error($model,'pid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'selling_count'); ?>
		<?php echo $form->textField($model,'selling_count'); ?>
		<?php echo $form->error($model,'selling_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'selling_price'); ?>
		<?php echo $form->textField($model,'selling_price'); ?>
		<?php echo $form->error($model,'selling_price'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'price'); ?>
        <?php echo $form->textField($model,'price'); ?>
        <?php echo $form->error($model,'price'); ?>
    </div>

	<div class="row">
		<?php echo $form->labelEx($model,'who'); ?>
		<?php echo $form->textField($model,'who',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'who'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php echo $form->textField($model,'date'); ?>
		<?php echo $form->error($model,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'remark'); ?>
		<?php echo $form->textArea($model,'remark',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'remark'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'merge_id'); ?>
		<?php echo $form->textField($model,'merge_id'); ?>
		<?php echo $form->error($model,'merge_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->