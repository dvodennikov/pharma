<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Document $model */

$this->title = Yii::t('app', 'Update Document: {name}', [
    'name' => $model->number,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Documents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="document-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
