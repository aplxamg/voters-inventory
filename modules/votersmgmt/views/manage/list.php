<?php
use app\assets\DatatableAsset;
DatatableAsset::register($this);
$this->title = 'Voters Management List';
?>

<h2 class="title"><?php echo $this->title; ?></h2><span class="line"></span>
<div class="content">
    <table id="voters_list" class="table table-striped table-bordered dataTable display">
        <thead>
            <th>VIN</th>
            <th>Voter's Name</th>
            <th>Address</th>
            <th>Birthdate</th>
            <th>Precinct No</th>
        </thead>
        <tbody>
            <?php foreach($records as $rec) { ?>
                <tr>
                    <td><?= $rec['voters_no'] ?></td>
                    <td><?= ucfirst($rec['first_name'])." ".ucfirst($rec['middle_name'])." ".ucfirst($rec['last_name']); ?></td>
                    <td><?= $rec['address'] ?></td>
                    <td><?= $rec['birthdate'] ?></td>
                    <td><?= $rec['precinct_no'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
