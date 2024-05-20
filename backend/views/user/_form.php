<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;
use yii\rbac\DbManager;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput() ?>

    <?= $form->field($model, 'email')->textInput(['type' => 'email']) ?>

    <?= $form->field($model, 'password')->textInput(['type' => 'password']) ?>

    <?= $form->field($model, 'password_repeat')->textInput(['type' => 'password']) ?>

    <?php echo $form->field($model, 'status')->dropDownList([
		User::STATUS_INACTIVE => Yii::t('app', 'Inactive'),
		User::STATUS_ACTIVE => Yii::t('app', 'Active')
		]) 
	?>
	
	<?php 
		$auth = \Yii::$app->authManager;
		$userRoles = isset($model->id)?$auth->getRolesByUser($model->id):[\Yii::$app->params['user.defaultRole'] => 0];
		$model->role = array_key_first($userRoles);
		$roles = [
			'none' => 'none'
		];
		
		foreach (array_keys($auth->getRoles()) as $role) {
			$roles[$role] = $role;
		}
		
		echo $form->field($model, 'role')->dropDownList($roles, 
			[
				'options' => [
					array_key_first($userRoles) => ['selected' => 'selected']
				]
			]
		);
	?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
