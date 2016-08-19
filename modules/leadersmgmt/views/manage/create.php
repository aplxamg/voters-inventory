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

<!-- Select Voter Modal -->
<div class="modal fade" id="selectVoterModal" tabindex="-1" role="dialog" aria-labelledby="selectVoterModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Select Voter</h4>
      </div>
      <div class="modal-body">
        <div id="loader"></div>
        <div id="select-voter-div">
          <table class="table" id="select-voter-table" width="100%">
                <thead>
                    <th>Voter's No</th>
                    <th>Name</th>
                    <th>Precinct No</th>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="selected-voter-btn">Add as Member</button>
      </div>
    </div>
  </div>
</div>

<!-- End Select Voter Modal -->


<h2 class="title"><?php echo $this->title; ?></h2><span class="line"></span>
<div id="addLeaderContainer" class="content custom-wrapper1 center-block">
    <?php if(isset($error) && $error == 1) { ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>Error!</strong> An error occurred while saving data.
        </div>
    <?php } else if (isset($error) && gettype($error) == 'array') { ?>
        <div class="alert alert-warning alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>Warning!</strong> The following are not added as your members since it is already a leader or a member of another leader:
            <ol>
            <?php foreach($error as $er) { ?>
                <li><?= $er ?></li>
            <?php } ?>
            </ol>
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
                        <td><input type="text" class="form-control toUpper dtInput membersInput" disabled value="<?= $member['name'] ?>"></td>
                        <td>
                            <div class="text-center">
                                <ul class="list-inline">
                                    <li>
                                        <button class="btn btn-default addVoter-btn" id="<?= 'member_'.$flag ?>" type="button"><span class="glyphicon glyphicon-list"></span></button>
                                    </li>
                                    <li>
                                        <button class="btn btn-default deleteMember-btn" id="<?= $flag ?>" type="button"><span class="glyphicon glyphicon-trash"></span></button>
                                    </li>
                                </ul>
                            </div>
                        </td>
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
