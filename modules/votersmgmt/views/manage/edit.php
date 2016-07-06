<?php

use app\assets\DatatableAsset;
use yii\bootstrap\ActiveForm;

DatatableAsset::register($this);
$this->title = 'Edit Voter\'s Profile';
?>

<h2 class="title"><?php echo $this->title; ?></h2><span class="line"></span>
<div class="content">
    <div class="custom-wrapper2 center-block">
        <?php $form = ActiveForm::begin([
            'id'          => 'editVoter-form',
            'method'      => 'post',
            'options'     => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template'      => "{label}\n<div class=\"col-lg-10\">{input}</div>\n<div class=\"col-lg-2\"></div><div class=\"col-lg-10\">{error}</div>",
                'labelOptions'  => ['class' => 'col-lg-2 control-label'],
            ]
        ]); ?>

        <?= $form->field($voter, 'voters_no')->textInput(['class' => 'form-control toUpper']); ?>
        <?= $form->field($voter, 'first_name')->textInput(['class' => 'form-control toUpper']); ?>
        <?= $form->field($voter, 'middle_name')->textInput(['class' => 'form-control toUpper']); ?>
        <?= $form->field($voter, 'last_name')->textInput(['class' => 'form-control toUpper']); ?>
        <?= $form->field($voter, 'birthdate')->textInput(['class' => 'form-control toUpper']); ?>
        <?= $form->field($voter, 'address')->textInput(['class' => 'form-control toUpper']); ?>
        <?= $form->field($voter, 'precinct_no')->textInput(['class' => 'form-control toUpper']); ?>

        <div class="text-right">
            <ul class="list-inline">
                <li><input type="submit" class="btn btn-primary back-btn" name="back" id="backBtn" value="Cancel"></li>
                <li><input type="submit" class="btn btn-primary save-btn" name="edit" id="saveBtn" value="Save"></li>
            </ul>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
