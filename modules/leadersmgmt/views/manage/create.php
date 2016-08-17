<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\assets\DatatableAsset;
use app\assets\AutocompleteAsset;
use app\assets\VotersAsset;
use app\assets\MsgboxAsset;
DatatableAsset::register($this);
AutocompleteAsset::register($this);
VotersAsset::register($this);
MsgboxAsset::register($this);
$this->title = 'Add Members';
?>

<h2 class="title"><?php echo $this->title; ?></h2><span class="line"></span>
<div id="addLeaderContainer" class="content custom-wrapper1 center-block">
    <?php if(isset($error) && $error == 1) { ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>Error!</strong> An error occurred while saving data.
        </div>
    <?php } ?>
    <?php
        $form = ActiveForm::begin([
            'id'            => 'leaderMemberSave-form',
            'method'        => 'post',
            'layout'        => 'horizontal',
        ]);
    ?>

    <div class="upperArea ">
        <div class="form-group">
            <label class="col-lg-2 control-label text-left">Assigned Precinct</label>
            <div class="col-lg-10"><input type="text" class="form-control toUpper" name="assigned_precinct" id="precinct" value="<?= $model->assigned_precinct ?>"></div>
            <div class="col-lg-2"></div>
            <div class="col-lg-10"><p class="help-block help-block-error"></p></div>
        </div>
        <div class="clearfix"></div>
        <br>
        <div class="col-md-6 noPadding">
            <button type="button" class="btn btn-success" id="addMember-btn">Add Member</button>
        </div>
        <div class="col-md-6 noPadding">
            <ul class="list-inline pull-right">
                <li><a href="/leadersmgmt/manage/list"><button id="backBtn" type="button" class="btn btn-danger">Back</button></a></li>
                <li><button type="button" class="btn btn-primary" id="save-btn"><?= Yii::t('app', 'Save') ?></button></li>
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
                <?php
                    $flag = 0;
                    $toAppend = '';
                    foreach($members as $member) {
                ?>
                    <tr>
                        <td><input type="text" class="form-control toUpper dtInput membersAutoComplete membersInput" data-class="<?= 'member_'.$flag; ?>" value="<?= $member['name'] ?>"></td>
                        <td><div class="text-center"><button class="btn btn-default deleteMember-btn" id="<?= 'member_'.$flag ?>" type="button"><span class="glyphicon glyphicon-trash"></span></button></div></td>
                    </tr>
                <?php
                        $toAppend .= '<input type="hidden" name="members[]" class="members member_'. $flag .'" value="'.$member['voter_id'].'">';
                        $flag++;
                    }
                ?>
            </tbody>
        </table>
    </div>
    <?= $toAppend; ?>
    <?php ActiveForm::end(); ?>
</div>
