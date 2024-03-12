<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Drug $model */

$this->title = Yii::t('app', 'Create Drug');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Drugs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="drug-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
