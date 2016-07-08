<?php
use app\assets\DatatableAsset;
DatatableAsset::register($this);
$this->title = 'Account Management List';
?>

<h2 class="title"><?php echo $this->title; ?></h2><span class="line"></span>
<div class="content">
    <div class="text-center">
        <a href="/account/manage/add"><button type="button" class="btn btn-primary btn-lg">Add Account</button></a>
    </div>
    <br>
    <table id="account_list" class="table table-striped table-bordered dataTable display myTable">
        <thead>
            <th class="text-center" width="10%">User Type</th>
            <th class="text-center" width="10%">Username</th>
            <th class="text-center" width="20%">E-mail Address</th>
            <th class="text-center" width="30%">Inserted Time</th>
            <th class="text-center" width="10%">Action</th>
        </thead>
        <tbody>
             <?php foreach($records as $rec) { ?>
                <tr>
                    <td class="break-word"><?= $rec['user_type'] ?></td>
                    <td class="break-word"><?= $rec['username'] ?></td>
                    <td class="break-word"><?= $rec['email_address']?></td>
                    <td class="text-center"><?= $rec['ins_time'] ?></td>
                    <td class="text-center">
                        <ul class="list-inline">
                            <li>
                                <a href="/account/manage/view/<?= $rec['id']; ?>">
                                    <button type="button" class="btn btn-primary" aria-label="View">
                                        <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                </button></a>
                            </li>
                            <li>
                                <a href="/account/manage/edit/<?php echo $rec['id']; ?>">
                                    <button type="button" class="btn btn-success" aria-label="Pencil">
                                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                </button></a>
                            </li>
                            <li>
                                <a href="/account/manage/delete/<?php echo $rec['id']; ?>">
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
