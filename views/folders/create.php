<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Folders $model */

$this->title = Yii::t('app', 'Create Folders');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Folders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="folders-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
