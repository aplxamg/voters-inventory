<?php

use app\assets\DatatableAsset;
use yii\bootstrap\ActiveForm;

DatatableAsset::register($this);
$this->title = 'View Voter\'s Profile';
?>

<h2 class="title"><?php echo $this->title; ?></h2><span class="line"></span>
<div class="content">
    <div class="custom-wrapper2 center-block">
        <?php $form = ActiveForm::begin([
            'id'          => 'viewVoter-form',
            'method'      => 'post',
            'options'     => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template'      => "{label}\n<div class=\"col-lg-10\">{input}</div>\n<div class=\"col-lg-2\"></div><div class=\"col-lg-10\">{error}</div>",
                'labelOptions'  => ['class' => 'col-lg-2 control-label'],
            ]
        ]); ?>

        <?= $form->field($voter, 'voters_no')->textInput(['readonly'=>true]); ?>
        <?= $form->field($voter, 'first_name')->textInput(['readonly'=>true]); ?>
        <?= $form->field($voter, 'middle_name')->textInput(['readonly'=>true]); ?>
        <?= $form->field($voter, 'last_name')->textInput(['readonly'=>true]); ?>
        <?= $form->field($voter, 'birthdate')->textInput(['readonly'=>true]); ?>
        <?= $form->field($voter, 'address')->textInput(['readonly'=>true]); ?>
        <?= $form->field($voter, 'precinct_no')->textInput(['readonly'=>true]); ?>

        <div class="text-right">
            <ul class="list-inline">
                <li><input type="submit" class="btn btn-primary back-btn" name="view" id="backBtn" value="Back"></li>
            </ul>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
