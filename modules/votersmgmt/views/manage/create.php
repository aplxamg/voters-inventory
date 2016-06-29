<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = 'Add Voter';
?>

<h2 class="title"><?php echo $this->title; ?></h2><span class="line"></span>
<div class="content">
    <div class="custom-wrapper2 center-block">
        <?php $form = ActiveForm::begin([
            'id'          => 'addVoter-form',
            'method'      => 'post',
            'options'     => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template'      => "{label}\n<div class=\"col-lg-10\">{input}</div>\n<div class=\"col-lg-2\"></div><div class=\"col-lg-10\">{error}</div>",
                'labelOptions'  => ['class' => 'col-lg-2 control-label'],
            ]
        ]); ?>
        <?= $form->field($model, 'voters_no'); ?>
        <?= $form->field($model, 'first_name'); ?>
        <?= $form->field($model, 'middle_name'); ?>
        <?= $form->field($model, 'last_name'); ?>
        <?= $form->field($model, 'birthdate')->textInput()->input('birthdate', ['placeholder' => "Format: MM/DD/YYYY"]); ?>
        <?= $form->field($model, 'address')->textarea(); ?>
        <?= $form->field($model, 'precinct_no'); ?>

        <div class="text-right">
            <ul class="list-inline">
                <li><input type="submit" name="save" class="btn btn-primary" value="Save"></li>
                <li><input type="submit" name="save" class="btn btn-success" value="Save and Continue"></li>
            </ul>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
