<?php
use app\assets\DatatableAsset;
DatatableAsset::register($this);
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
    <div class="text-center">
        <a href="/leadersmgmt/manage/create"><button type="button" class="btn btn-primary btn-lg">Add Leader</button></a>
    </div>
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
                                <a href="/leadersmgmt/manage/viewmembers/".<?= $rec['id']; ?>>
                                    <button type="button" class="btn btn-primary" aria-label="list">
                                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                                </button></a>
                            </li>
                            <li>
                                <a href="/leadersmgmt/manage/delete/<?php echo $rec['id'] ?>">
                                    <button type="button" class="btn btn-danger" aria-label="Trash">
                                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                    </button></a>
                            </li>
                        </ul>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
