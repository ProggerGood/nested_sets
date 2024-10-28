<?php

use yii\helpers\Html;
use app\models\FoldersQuery;
use app\models\Folders;

/** @var yii\web\View $this */
/** @var app\models\Folders[] $items */

$this->title = Yii::t('app', 'Nestable set');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Folders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="folders-view">

    <h1><?= Html::encode($this->title);?></h1>

    <?= \klisl\nestable\Nestable::widget([
        'query' => new FoldersQuery(Folders::class),
        'update' => '/folders/update',
        'delete' => '/folders/delete',
        'viewItem' => '/folders/view',
    ]) ?>

</div>