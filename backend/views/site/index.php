<?php

/** @var yii\web\View $this */

$this->title = \Yii::$app->name;
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4"><?= \Yii::t('app', 'Control panel') ?></h1>
    </div>

    <div class="control-panel body-content">

        <div class="row">
            <div class="col-lg-4">
			<?= $this->render('_receipts') ?>
            </div>
            <div class="col-lg-4">
            <?= $this->render('_drugs') ?>
            </div>
            <div class="col-lg-4">
            <?= $this->render('_stats') ?>
            </div>
        </div>

    </div>
</div>
