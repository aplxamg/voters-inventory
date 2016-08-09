<?php
    use app\components\helpers\User;
    use app\assets\DatatableAsset;
    use app\assets\MsgboxAsset;
    DatatableAsset::register($this);
    MsgboxAsset::register($this);
    $this->title = 'Members List';

    $identity = User::initUser();
?>

<h2 class="title"><?php echo $this->title; ?></h2><span class="line"></span>
<div class="content">
    <div class="text-center">
        <a href="/leadersmgmt/manage/edit/<?= $id ?>"><button type="button" class="btn btn-primary btn-lg">Add Members</button></a>
    </div>
    <table id="view_members_list" class="table table-striped table-bordered dataTable display">
        <thead>
            <th class="text-center" width="20%">VIN</th>
            <th class="text-center" width="60%">Voter's Name</th>
            <th class="text-center" width="10">Voting Status</th>
            <th class="text-center" width="10%">Action</th>
        </thead>
        <tbody>
            <?php foreach($list as $value) { ?>
                <tr>
                    <td><?= $value['vin']; ?></td>
                    <td><?= $value['name']; ?></td>
                    <td class="text-center">
                        <?php
                            if($value['vote'] == 'N') {
                                $btn = 'btn-danger';
                                $btnClass = 'member-set-vote';
                                $action = 'set';
                                $name = 'Not Voted';
                            } else {
                                $btn = 'btn-success';
                                $btnClass = 'member-reset-vote';
                                $action = 'reset';
                                $name = 'Voted';
                            }
                            $disabled = '';
                            if($identity->user_type != 'leader') {
                                $disabled = 'disabled="disabled"';
                            }

                        ?>
                        <button type="button" class="btn <?= $btn ?> msgbox-button <?= $btnClass ?>" value="<?= $value['voter_id'] ?>" <?= $disabled ?> data-leader="<?= $id ?>"><?= $name ?></button>
                    </td>
                    <td class="text-center">
                        <ul class="list-inline">
                            <li>
                                <button type="button" class="btn btn-danger msgbox-button delete-member" aria-label="list" data-toggle="tooltip" data-placement="top" title="Delete Member" value="<?= $value['id'] ?>" data-leader="<?= $id ?>">
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
