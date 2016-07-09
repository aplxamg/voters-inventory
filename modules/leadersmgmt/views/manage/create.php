<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\assets\DatatableAsset;
use app\assets\AutocompleteAsset;
use app\assets\VotersAsset;
DatatableAsset::register($this);
AutocompleteAsset::register($this);
VotersAsset::register($this);
$this->title = 'Add Leader and Members';
?>

<h2 class="title"><?php echo $this->title; ?></h2><span class="line"></span>
<div id="addLeaderContainer" class="content custom-wrapper1 center-block">
    <?php
        $form = ActiveForm::begin([
            'id'            => 'leaderMemberSave-form',
            'method'        => 'post',
            'layout'        => 'horizontal',
        ]);
    ?>

    <div class="upperArea ">
        <div class="form-group">
            <label class="col-lg-2 control-label text-left">Leader</label>
            <div class="col-lg-10"><input type="text" id="autocomplete" class="form-control toUpper"></div>
            <input type="hidden" name="leader" id="leader">
        </div>
        <div class="clearfix"></div>
        <br>
        <div class="col-md-6 noPadding">
            <button type="button" class="btn btn-success" id="addMember-btn">Add Member</button>
        </div>
        <div class="col-md-6 noPadding">
            <ul class="list-inline pull-right">
                <li><a href="/votersmgmt/manage/list"><button id="backBtn" type="button" class="btn btn-danger">Back</button></a></li>
                <li><button type="button" class="btn btn-primary"><?= Yii::t('app', 'Save') ?></button></li>
            </ul>
        </div>
    </div>
    <br /> <br>
    <div class="lowerArea">
        <table id="members_list" class="table table-bordered">
            <thead>
                <th class="text-center" width="85%">Member Name</th>
                <th class="text-center" width="15%">Action</th>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    <?php ActiveForm::end(); ?>
</div>
