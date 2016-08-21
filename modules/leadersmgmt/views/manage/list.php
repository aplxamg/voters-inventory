<?php
use app\assets\DatatableAsset;
use app\assets\AutocompleteAsset;
use app\assets\MsgboxAsset;
DatatableAsset::register($this);
AutocompleteAsset::register($this);
MsgboxAsset::register($this);
$this->title = 'Leaders Management List';
?>

<h2 class="title"><?php echo $this->title; ?></h2><span class="line"></span>
<div class="content">
<?php if (Yii::$app->session->hasFlash('success')): ?>
  <div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <?= Yii::$app->session->getFlash('success') ?>
  </div>
<?php endif; ?>
<!--
    <div class="text-center">
        <a href="/leadersmgmt/manage/add"><button type="button" class="btn btn-primary btn-lg">Add Leader</button></a>
    </div>
-->
    <table id="leaders_list" class="table table-striped table-bordered dataTable display">
        <thead>
            <th>VIN</th>
            <th>Voter's Name</th>
            <th>Assigned Precinct No</th>
            <th>Actions</th>
        </thead>
        <tbody>
            <?php foreach($records as $rec) { ?>
                <tr>
                    <td class="text-center"><?= $rec['voters_no'] ?></td>
                    <td  class="text-center"><?= ucfirst($rec['first_name'])." ".ucfirst($rec['middle_name'])." ".ucfirst($rec['last_name']); ?></td>
                    <td class="text-center"><?= $rec['assigned_precinct'] ?></td>
                    <td class="text-center">
                        <ul class="list-inline">
                            <li>
                                <a href="/leadersmgmt/manage/memberlist/<?= $rec['leader_id']; ?>">
                                    <button type="button" class="btn btn-success" aria-label="list" data-toggle="tooltip" data-placement="top" title="View Members">
                                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                                </button></a>
                            </li>
                            <li>
                                <a href="/leadersmgmt/manage/edit/<?php echo $rec['leader_id']; ?>">
                                    <button type="button" class="btn btn-primary" aria-label="Pencil" data-toggle="tooltip" data-placement="top" title="Edit Members">
                                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                </button></a>
                            </li>
                            <li>
                                <button type="button" class="btn btn-danger msgbox-button delete-leader" aria-label="Trash" data-toggle="tooltip" data-placement="top" title="Delete Leader" value="<?= $rec['leader_id'] ?>">
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
