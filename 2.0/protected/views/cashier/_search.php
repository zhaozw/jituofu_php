<?php
/* @var $this CashierController */
/* @var $model Cashier */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pid'); ?>
		<?php echo $form->textField($model,'pid'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'selling_count'); ?>
		<?php echo $form->textField($model,'selling_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'selling_price'); ?>
		<?php echo $form->textField($model,'selling_price'); ?>
	</div>

    <div class="row">
        <?php echo $form->label($model,'price'); ?>
        <?php echo $form->textField($model,'price'); ?>
    </div>

	<div class="row">
		<?php echo $form->label($model,'who'); ?>
		<?php echo $form->textField($model,'who',array('size'=>40,'maxlength'=>40)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date'); ?>
		<?php echo $form->textField($model,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'remark'); ?>
		<?php echo $form->textArea($model,'remark',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'merge_id'); ?>
		<?php echo $form->textField($model,'merge_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->