<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Blog */

$this->title = '创建博客';
$this->params['breadcrumbs'][] = ['label' => '所有博客', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
