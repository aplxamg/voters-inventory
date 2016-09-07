<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\assets\VotersAsset;
VotersAsset::register($this);
$this->title = 'Add Voter';
?>

<h2 class="title"><?php echo $this->title; ?></h2><span class="line"></span>
<div class="content" id="addVoterCnt" data-errorValue=<?= $error; ?>>
    <div class="custom-wrapper2 center-block">
<?php
    if($error == 1) {
        $msg = 'Error on saving data';
    } else if ($error == 2) {
        $msg = 'Record not saved. Voter already exists.';
    }

    if($error != 0) {
?>
        <div class="alert alert-danger alert-dismissible" role="alert" id="createVoterAlert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>Error!</strong> <?= $msg; ?> <?=  $error ?>
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
        <?= $form->field($model, 'voters_no')->textInput(['class' => 'form-control toUpper']); ?>
        <?= $form->field($model, 'first_name')->textInput(['class' => 'form-control toUpper']); ?>
        <?= $form->field($model, 'middle_name')->textInput(['class' => 'form-control toUpper']); ?>
        <?= $form->field($model, 'last_name')->textInput(['class' => 'form-control toUpper']); ?>
        <?= $form->field($model, 'birthdate')->textInput()->input('birthdate', ['placeholder' => "Format: MM/DD/YYYY"]); ?>
        <?= $form->field($model, 'address')->textarea(['class' => 'form-control toUpper']); ?>
        <?= $form->field($model, 'precinct_no')->textInput(['class' => 'form-control toUpper']); ?>

        <div class="text-right">
            <ul class="list-inline">
                <li><a href="/votersmgmt/manage/list"  class="btn btn-danger back-btn" name="back" id="backBtn">Back</a></li>
                <li><input type="submit" class="btn btn-primary save-btn" name="save" id="saveBtn" value="Save"></li>
                <?php if(!isset($id)) { ?>
                <li><input type="submit" class="btn btn-success save-btn" name="save" id="saveAnotherBtn" value="Save and Continue"></li>
                <?php } ?>
            </ul>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
