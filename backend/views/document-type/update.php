<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\DocumentType $model */
/** @var array $customFields */

$this->title = Yii::t('app', 'Update Document Type: {name}', [
    'name' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Document Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="document-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
		'model' => $model,
    ]) ?>

</div>
