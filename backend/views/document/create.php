<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Document $model */

$this->title = Yii::t('app', 'Create Document');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Documents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
