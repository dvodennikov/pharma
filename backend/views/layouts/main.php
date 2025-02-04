<?php

/** @var \yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use common\helpers\Pharma;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);
    
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
    ];
    
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
		$menuItems[] = ['label' => \Yii::t('app', 'Receipts'), 'url' => ['/receipt/index']];
		$menuItems[] = ['label' => \Yii::t('app', 'Drugs'), 'url' => ['/drug/index']];
		$menuItems[] = ['label' => \Yii::t('app', 'Persons'), 'url' => ['/person/index']];
		$menuItems[] = ['label' => \Yii::t('app', 'Documents'), 'url' => ['/document/index']];
		
		$userPermissions = [];
		foreach (\Yii::$app->authManager->getPermissionsByUser(\Yii::$app->user->id) as $permission) {
			$userPermissions[$permission->name] = true;
		}
		
		if (array_key_exists('createDocumentType', $userPermissions)) 
			$menuItems[] = ['label' => \Yii::t('app', 'Document types'), 'url' => ['/document-type/index']];
			
		if (array_key_exists('createUnit', $userPermissions))
			$menuItems[] = ['label' => \Yii::t('app', 'Units'), 'url' => ['/unit/index']];
			
		if (array_key_exists('createUser', $userPermissions))
			$menuItems[] = ['label' => \Yii::t('app', 'Users'), 'url' => ['/user/index']];
	}
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
        'items' => $menuItems,
    ]);
    
	echo Pharma::getLanguageSwitchingHtml();
    
    if (Yii::$app->user->isGuest) {
        echo Html::tag('div',Html::a('Login',['/site/login'],['class' => ['btn btn-link login text-decoration-none']]),['class' => ['d-flex']]);
    } else {
        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
            . Html::submitButton(
                Yii::t('app', 'Logout') . ' (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout text-decoration-none']
            )
            . Html::endForm();
    }
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-start">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p class="float-end"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
