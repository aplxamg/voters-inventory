<?php
use app\assets\DatatableAsset;
use app\assets\AutocompleteAsset;
DatatableAsset::register($this);
AutocompleteAsset::register($this);
$this->title = 'Leaders Management List';
?>

<h2 class="title"><?php echo $this->title; ?></h2><span class="line"></span>
<div class="content">
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
                                <a href="/votersmgmt/manage/edit/".<?= $rec['id']; ?>
                                    <button type="button" class="btn btn-primary" aria-label="Pencil">
                                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                </button></a>
                            </li>
                            <li>
                                <button type="button" class="btn btn-danger" aria-label="Trash">
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
