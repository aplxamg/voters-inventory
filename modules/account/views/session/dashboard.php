<?php
use app\assets\ChartAsset;
ChartAsset::register($this);
$this->title = 'Summary';
?>

<h2 class="title"><?php echo $this->title; ?></h2><span class="line"></span>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <canvas id="voter-chart" width="100%" height="500"></canvas>
            </div>
            <div class="col-md-4">


            </div>
        </div>
    </div>
</div>
