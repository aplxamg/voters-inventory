<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\assets\AccountAsset;
use app\assets\MsgboxAsset;
MsgboxAsset::register($this);
AccountAsset::register($this);
$this->title = 'Add Account';
if(isset($id))
    $this->title = 'Edit Account';
?>

<h2 class="title"><?php echo $this->title; ?></h2><span class="line"></span>
<div class="content" id="create-account" data-id=<?= (isset($id)) ? $id : 0 ?>>
    <div class="custom-wrapper2 center-block">
        <?php if(!empty($error)) { ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>Error!</strong> <?= $error; ?>
            </div>
        <?php } ?>


        <?php $form = ActiveForm::begin([
            'id'          => 'addVoter-form',
            'method'      => 'post',
            'options'     => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template'      => "{label}\n<div class=\"col-lg-8\">{input}</div>\n<div class=\"col-lg-3\"></div><div class=\"col-lg-8\">{error}</div>",
                'labelOptions'  => ['class' => 'col-lg-3 control-label'],
            ]
        ]); ?>
        <?= $form->field($model, 'user_type')->dropDownList(['encoder' => 'Encoder', 'leader' => 'Leader']
                                                            ,['prompt'=>'Select Option'],['class' => 'form-control']); ?>
        <?= $form->field($model, 'username')->textInput(array('placeholder'=>'Username','class' => 'form-control toLower')); ?>
        <?= $form->field($model, 'password')->passwordInput(array('placeholder'=>'Password', 'value' => ''),['class' => 'form-control']); ?>

        <div class="text-right">
            <ul class="list-inline">
                <li><a href="/account/manage/list"  class="btn btn-danger back-btn" name="back" id="backBtn">Back</a></li>
                <li><button type="button" class="btn btn-primary" id="save-button">Save</button></li>
            </ul>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
