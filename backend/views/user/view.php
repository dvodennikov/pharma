<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            'email:email',
            [
				'attribute' => 'role',
				'value' => $model->getRole(),
            ],
            [
				'attribute'=> 'status',
				'value' => Yii::t('app', ($model->status == User::STATUS_ACTIVE)?'Active':'Disabled'),
            ],
            'created_at:datetime',
            //~ [
				//~ 'attribute' => 'created_at',
				//~ 'value' => date('d/M/Y h:i:s', $model->created_at),
			//~ ],
			'updated_at:datetime',
            //~ [
				//~ 'attribute' => 'updated_at',
				//~ 'value' => date('d/M/Y h:i:s', $model->updated_at),
			//~ ],
            //'verification_token',
        ],
    ]) ?>

</div>
