<?php
use app\assets\DatatableAsset;
DatatableAsset::register($this);
$this->title = 'Voters Management List';
?>

<h2 class="title"><?php echo $this->title; ?></h2><span class="line"></span>
<div class="content">
    <div class="text-center">
        <a href="/votersmgmt/manage/add"><button type="button" class="btn btn-primary btn-lg">Add Voter</button></a>
    </div>
    <br>
    <table id="voters_list" class="table table-striped table-bordered dataTable display myTable">
        <thead>
            <th class="text-center" width="10%">VIN</th>
            <th class="text-center" width="20%">Voter's Name</th>
            <th class="text-center" width="30%">Address</th>
            <th class="text-center" width="10%">Birthdate</th>
            <th class="text-center" width="10%">Precinct No</th>
            <th class="text-center" width="10%">Action</th>
        </thead>
        <tbody>
            <?php foreach($records as $rec) { ?>
                <tr>
                    <td><?= $rec['voters_no'] ?></td>
                    <td><?= ucfirst($rec['first_name'])." ".ucfirst($rec['middle_name'])." ".ucfirst($rec['last_name']); ?></td>
                    <td class="break-word"><?= $rec['address'] ?></td>
                    <td class="text-center"><?= $rec['birthdate'] ?></td>
                    <td class="text-center"><?= $rec['precinct_no'] ?></td>
                    <td class="text-center">
                        <ul class="list-inline">
                            <li>
                                <a href="/votersmgmt/manage/edit/".<?= $rec['id']; ?>>
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
