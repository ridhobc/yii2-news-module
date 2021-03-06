<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;

/**
* @var yii\web\View $this
* @var dmstr\modules\news\models\ImageGallery $model
*/

$this->title = 'Image Gallery ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Image Galleries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'View');
$returnUrl                     = (\Yii::$app->request->get('returnUrl') !== null)
                                    ? \Yii::$app->request->get('returnUrl') : null;
?>
<div class="image-gallery-view">

    <p class='pull-left'>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('app', 'Edit'), ['update', 'id' => $model->id, 'returnUrl' => $returnUrl],
        ['class' => 'btn btn-info']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('app', 'New') . ' Image Gallery', ['create'], ['class' => 'btn
        btn-success']) ?>
    </p>
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <?= \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>        <p class='pull-right'>
        <?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . Yii::t('app', 'List'), ['index'], ['class'=>'btn btn-default']) ?>
    </p><div class='clearfix'></div> 

    
    <h3>
        <?= $model->name ?>    </h3>


    <?php $this->beginBlock('dmstr\modules\news\models\ImageGallery'); ?>

    <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
    			'id',
// generated by schmunk42\giiant\crud\providers\RelationProvider::attributeFormat
[
    'format'=>'html',
    'attribute'=>'news_id',
    'value' => ($model->getNews()->one() ? Html::a($model->getNews()->one()->title, ['news/view', 'id' => $model->getNews()->one()->id,]) : '<span class="label label-warning">?</span>'),
],
			'name',
			'created_at',
			'updated_at',
    ],
    ]); ?>

    <hr/>

    <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('app', 'Delete'), ['delete', 'id' => $model->id, 'returnUrl' => $returnUrl],
    [
    'class' => 'btn btn-danger',
    'data-confirm' => '' . Yii::t('app', 'Are you sure to delete this item?') . '',
    'data-method' => 'post',
    ]); ?>
    <?php $this->endBlock(); ?>


    
<?php $this->beginBlock('Images'); ?>
<p class='pull-right'>
  <?= Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('app', 'List All') . ' Images',
            ['image/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('app', 'New') . ' Image',
            ['image/create', 'Image' => ['photo_gallery_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</p><div class='clearfix'></div>
<?php Pjax::begin(['id'=>'pjax-Images','linkSelector'=>'#pjax-Images ul.pagination a']) ?>
<?= \yii\grid\GridView::widget([
    'dataProvider' => new \yii\data\ActiveDataProvider(['query' => $model->getImages(), 'pagination' => ['pageSize' => 10]]),
    'columns' => [			'id',
[
    'format' => 'html',
    'label'=>'Image',
    'attribute' => 'image',
    'value'=> function($model){
        return \yii\helpers\Html::img($model->image, ['class' => 'img-responsive']);
    }

],
			'title',
			'text_html:ntext',
			'published_at',
			'source',
			'tags',
[
    'class'      => 'yii\grid\ActionColumn',
    'template'   => '{view} {update}',
    'contentOptions' => ['nowrap'=>'nowrap'],
    'urlCreator' => function($action, $model, $key, $index) {
        // using the column name as key, not mapping to 'id' like the standard generator
        $returnUrl = \Yii::$app->request->url;
        if (strpos($returnUrl, 'returnUrl') !== false) {
            $returnUrl = urldecode(substr($returnUrl, strpos($returnUrl, 'returnUrl') + 10, strlen($returnUrl)));
        } else {
            $returnUrl = (Tabs::getParentRelationRoute(\Yii::$app->controller->id) !== null) ?
                Tabs::getParentRelationRoute(\Yii::$app->controller->id) : null;
        }
        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key, 'returnUrl' => $returnUrl];
        $params[0] = 'image' . '/' . $action;
        return Url::toRoute($params);
    },
    'buttons'    => [
        
    ],
    'controller' => 'image'
],]
]);?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


    <?= Tabs::widget(
                 [
                     'id' => 'relation-tabs',
                     'encodeLabels' => false,
                     'items' => [ [
    'label'   => '<span class="glyphicon glyphicon-asterisk"></span> ImageGallery',
    'content' => $this->blocks['dmstr\modules\news\models\ImageGallery'],
    'active'  => true,
],[
    'label'   => '<small><span class="glyphicon glyphicon-paperclip"></span> Images</small>',
    'content' => $this->blocks['Images'],
    'active'  => false,
], ]
                 ]
    );
    ?></div>
