<?php
use app\components\helpers\User;
use app\assets\ChartAsset;
use app\assets\DatatableAsset;
use app\assets\MsgboxAsset;
ChartAsset::register($this);
DatatableAsset::register($this);
MsgboxAsset::register($this);
$this->title = 'Summary';

$identity = User::initUser();
$col = 'col-md-6 center-block';
$addCol = '<div class="col-md-3"></div>';
if($identity->user_type == 'admin') {
    $col = 'col-md-6';
    $addCol = '';
}
?>

<h2 class="title"><?php echo $this->title; ?></h2><span class="line"></span>
<div class="content" id="dashboard" data-id="<?= $identity->id ?>" data-user="<?= $identity->user_type ?>">
    <div class="container-fluid">
        <div class="row">
            <?= $addCol ?>
            <div class="<?= $col ?>">
                <?php if($identity->user_type == 'admin') { ?>
                <div>
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                    <select id="selectLeader" class="form-control">
                        <?php foreach($leaders as $key => $value)  { ?>
                            <option value="<?= $key ?>"><?= $value ?></option>
                        <?php } ?>
                    </select>
                    </div>
                </div>
                <div class="clearfix"></div>
                <br>
                <?php } ?>
                <canvas id="voter-chart" ></canvas>
                <br><br>
                <table class="table">
                    <thead>
                        <th class="text-center">No. of Voters</th>
                        <th class="text-center">No. of voted Voters</th>
                        <th class="text-center">No. of not voted Voters</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center" id="total-voter">1</td>
                            <td class="text-center" id="total-voted">1</td>
                            <td class="text-center" id="total-not-voted">1</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?= $addCol ?>
            <?php if($identity->user_type == 'admin') { ?>
            <div class="col-md-6">
                <div class="text-right">
                    <ul class="list-inline">
                        <li><button type="button" class="btn btn-warning msgbox-button reset-voters">Reset Votes</button></li>
                        <li><button type="button" class="btn btn-danger msgbox-button delete-data">Delete Data</button></li>
                    </ul>
                </div>

                <table id="summary_list" class="table table-striped table-bordered dataTable display myTable">
                    <thead>
                        <th>Leader</th>
                        <th>No. of voted members</th>
                        <th>No. of not voted members</th>
                    </thead>
                    <tbody>
                        <?php foreach($records as $rec) { ?>
                            <tr>
                                <td><?= $rec['name'] ?></td>
                                <td><?= $rec['voted'] ?></td>
                                <td><?= $rec['not_voted'] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
