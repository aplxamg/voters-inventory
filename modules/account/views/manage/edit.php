<?php

use app\assets\DatatableAsset;
use yii\bootstrap\ActiveForm;

DatatableAsset::register($this);
$this->title = 'Edit Account';
?>

<h2 class="title"><?php echo $this->title; ?></h2><span class="line"></span>
<div class="content">
    <div class="custom-wrapper2 center-block">
        <?php $form = ActiveForm::begin([
            'id'          => 'editVoter-form',
            'method'      => 'post',
            'options'     => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template'      => "{label}\n<div class=\"col-lg-8\">{input}</div>\n<div class=\"col-lg-3\"></div><div class=\"col-lg-8\">{error}</div>",
                'labelOptions'  => ['class' => 'col-lg-3 control-label'],
            ]
        ]); ?>

        <?= $form->field($user, 'user_type')->dropDownList(['encoder' => 'Encoder', 'leader' => 'Leader']
                                                            ,['prompt'=>'Select Option'],['class' => 'form-control']); ?>
        <?= $form->field($user, 'username')->textInput(array('placeholder'=>'Username'),['class' => 'form-control toUpper']); ?>
        <?= $form->field($user, 'password')->passwordInput(array('placeholder'=>'Password'),['class' => 'form-control toUpper']); ?>
        <div class="text-right">
           <ul class="list-inline">
                    <li><a href="/account/manage/list"  class="btn btn-danger back-btn" name="back" id="backBtn">Back</a></li>
                    <li><input type="submit" class="btn btn-primary save-btn" name="save" id="saveBtn" value="Save"></li>
            </ul>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
