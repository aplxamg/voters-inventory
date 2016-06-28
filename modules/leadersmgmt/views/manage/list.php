<?php
use app\assets\DatatableAsset;
DatatableAsset::register($this);
$this->title = 'Leaders Management List';
?>

<h2 class="title"><?php echo $this->title; ?></h2><span class="line"></span>
<div class="content">
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
                    <td><?= $rec['voters_no'] ?></td>
                    <td><?= ucfirst($rec['first_name'])." ".ucfirst($rec['middle_name'])." ".ucfirst($rec['last_name']); ?></td>
                    <td><?= $rec['assigned_precinct'] ?></td>
                    <td></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
