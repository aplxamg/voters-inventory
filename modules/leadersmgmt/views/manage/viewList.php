<?php
    use app\assets\DatatableAsset;
    use app\assets\MsgboxAsset;
    DatatableAsset::register($this);
    MsgboxAsset::register($this);
    $this->title = 'Members List';
?>

<h2 class="title"><?php echo $this->title; ?></h2><span class="line"></span>
<div class="content">
    <table id="view_members_list" class="table table-striped table-bordered dataTable display">
        <thead>
            <th class="text-center" width="30%">VIN</th>
            <th class="text-center" width="60%">Voter's Name</th>
            <th class="text-center" width="10%">Action</th>
        </thead>
        <tbody>
            <?php foreach($list as $value) { ?>
                <tr>
                    <td><?= $value['vin']; ?></td>
                    <td><?= $value['name']; ?></td>
                    <td class="text-center">
                        <ul class="list-inline">
                            <li>
                                <a href="/leadersmgmt/manage/vote/<?= $value['id']; ?>">
                                    <button type="button" class="btn btn-success" aria-label="list" data-toggle="tooltip" data-placement="top" title="Set as voted">
                                        <span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>
                                </button></a>
                            </li>
                            <li>
                                <button type="button" class="btn btn-success" aria-label="list" data-toggle="tooltip" data-placement="top" title="Member voted" disabled="disabled">
                                    <span class="glyphicon glyphicon-check" aria-hidden="true"></span>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="btn btn-danger delete-button delete-member" aria-label="list" data-toggle="tooltip" data-placement="top" title="Delete Member" id="<?= $value['id'] ?>">
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
