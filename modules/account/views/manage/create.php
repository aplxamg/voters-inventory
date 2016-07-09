<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\assets\VotersAsset;
VotersAsset::register($this);
$this->title = 'Add Account';
?>

<h2 class="title"><?php echo $this->title; ?></h2><span class="line"></span>
<div class="content" id="addVoterCnt" data-errorValue=<?= $error; ?>>
<?php
    if($error == 1) {
        $msg = 'Error on saving data';
    } else if ($error == 2) {
        $msg = 'Record not saved. Voter already exists.';
    }

    if($error != 0) { ?>
        <div class="alert alert-danger alert-dismissible" role="alert" id="createVoterAlert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>Error!</strong> <?= $msg; ?>
        </div>
<?php } else {

    } ?>

    <div class="custom-wrapper2 center-block">
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
        <?= $form->field($model, 'username')->textInput(array('placeholder'=>'Username'),['class' => 'form-control']); ?>
        <?= $form->field($model, 'password')->passwordInput(array('placeholder'=>'Password'),['class' => 'form-control']); ?>

        <div class="text-right">
            <ul class="list-inline">
                <li><a href="/account/manage/list"  class="btn btn-danger back-btn" name="back" id="backBtn">Back</a></li>
                <li><input type="submit" class="btn btn-primary save-btn" name="save" id="saveBtn" value="Save"></li>
            </ul>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
