<?php
use app\assets\DatatableAsset;
use app\assets\MsgboxAsset;
DatatableAsset::register($this);
MsgboxAsset::register($this);
$this->title = 'Voters Management List';
?>

<h2 class="title"><?php echo $this->title; ?></h2><span class="line"></span>
<div class="content">

<?php if (Yii::$app->session->hasFlash('error')): ?>
  <div class="alert alert-danger alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <?= Yii::$app->session->getFlash('error') ?>
  </div>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
  <div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <?= Yii::$app->session->getFlash('success') ?>
  </div>
<?php endif; ?>
    <div class="text-center">
        <a href="/votersmgmt/manage/add"><button type="button" class="btn btn-primary btn-lg">Add Voter</button></a>
    </div>
    <br>
    <table id="voters_list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
        <thead>
            <th class="text-center">VIN</th>
            <th class="text-center">Voter's Name</th>
            <th class="text-center">Address</th>
            <th class="text-center">Birthdate</th>
            <th class="text-center">Precinct No</th>
            <th class="text-center">Assigned Precinct No</th>
            <th class="text-center">Voting Status</th>
            <th class="text-center">Action</th>
        </thead>
        <tbody>
            <?php foreach($records as $rec) {
            ?>
                <tr>
                    <td><?= $rec['vin'] ?></td>
                    <td><?= $rec['name'] ?></td>
                    <td class="break-word"><?= $rec['address']?></td>
                    <td class="text-center"><?= $rec['birthdate'] ?></td>
                    <td class="text-center"><?= $rec['precinct'] ?></td>
                    <td class="text-center"><?= $rec['assigned_precinct'] ?></td>
                    <td class="text-center">
                        <?php
                            if($rec['voting_status'] == 'N') {
                                $btn = 'btn-danger';
                                $btnClass = 'set-vote';
                                $action = 'set';
                                $name = 'Not Voted';
                            } else {
                                $btn = 'btn-success';
                                $btnClass = 'reset-vote';
                                $action = 'reset';
                                $name = 'Voted';
                            }

                            $disabled = 'disabled="disabled"';
                            if ($identity->user_type == 'admin') {
                               $disabled = '';
                            }

                        ?>
                        <button type="button" class="btn <?= $btn ?> msgbox-button <?= $btnClass ?>" value="<?= $rec['id'] ?>" <?= $disabled ?>><?= $name ?></button>
                    </td>
                    <td class="text-center">
                        <ul class="list-inline">
                            <?php if ($identity->user_type == 'admin') { ?>
                            <?php if($rec['leader'] == 0) { ?>
                            <li>
                                <button type="button" class="btn btn-warning msgbox-button approve-leader" aria-label="View" data-toggle="tooltip"
                                        data-placement="top" title="Assign as leader" value="<?= $rec['id'] ?>">
                                        <span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>
                                </button></a>
                            </li>
                            <?php } else { ?>
                            <li>
                                <button type="button" class="btn btn-warning msgbox-button remove-leader" aria-label="View" data-toggle="tooltip"
                                        data-placement="top" title="Remove as leader" value="<?= $rec['id'] ?>">
                                        <span class="glyphicon glyphicon-check" aria-hidden="true"></span>
                                </button>
                            </li>
                            <?php } } ?>
                            <li>
                                <a href="/votersmgmt/manage/view/<?= $rec['id']; ?>">
                                    <button type="button" class="btn btn-primary" aria-label="View" data-toggle="tooltip" data-placement="top" title="View Voter Details">
                                        <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                </button></a>
                            </li>
                            <li>
                                <a href="/votersmgmt/manage/edit/<?php echo $rec['id']; ?>">
                                    <button type="button" class="btn btn-success" aria-label="Pencil" data-toggle="tooltip" data-placement="top" title="Edit Voter">
                                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                </button></a>
                            </li>
                            <li>
                                <button type="button" class="btn btn-danger msgbox-button delete-voter" aria-label="Trash" data-toggle="tooltip" data-placement="top" title="Delete Voter" value="<?= $rec['id'] ?>">
                                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                </button>
                            </li>
                        </ul>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
