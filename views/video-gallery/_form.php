<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;

/**
* @var yii\web\View $this
* @var dmstr\modules\news\models\VideoGallery $model
* @var yii\widgets\ActiveForm $form
*/

// Cut off returnUrl from request url for only save record option
$actionUrl = Yii::$app->request->url;
if (strpos($actionUrl, 'returnUrl') !== false) {
    $actionUrl = urldecode(substr($actionUrl, 0, strpos($actionUrl, 'returnUrl') - 1));
}
?>

<div class="video-gallery-form">

    <?php $form = ActiveForm::begin([
                        'id'     => 'VideoGallery',
                        'layout' => 'horizontal',
                        'enableClientValidation' => false,
                    ]
                );
    ?>

    <div class="">
        <?php echo $form->errorSummary($model); ?>
        <?php $this->beginBlock('main'); ?>

        <p>
            
			<?= // generated by schmunk42\giiant\crud\providers\RelationProvider::activeField
$form->field($model, 'news_id')->dropDownList(
    \yii\helpers\ArrayHelper::map(dmstr\modules\news\models\News::find()->all(),'id','title'),
    ['prompt' => Yii::t('app', 'Select')]
); ?>
			<?= $form->field($model, 'name')->textInput(['maxlength' => 100]) ?>
        </p>
        <?php $this->endBlock(); ?>
        
        <?=
    Tabs::widget(
                 [
                   'encodeLabels' => false,
                     'items' => [ [
    'label'   => 'VideoGallery',
    'content' => $this->blocks['main'],
    'active'  => true,
], ]
                 ]
    );
    ?>
        <hr/>

        <?= Html::submitButton(
                '<span class="glyphicon glyphicon-check"></span> ' . ($model->isNewRecord
                            ? Yii::t('app', 'Create') : Yii::t('app', 'Save')),
                [
                    'id'    => 'save-' . $model->formName(),
                    'class' => 'btn btn-success'
                ]
            );
        ?>
        <?= (!$model->isNewRecord && \Yii::$app->request->getQueryParam('returnUrl') !== null) ? Html::submitButton(
                '<span class="glyphicon glyphicon-fast-backward"></span> ' .
                    Yii::t('app', 'Save and go back') . '',
                    ['class' => 'btn btn-primary']
                ) : null;
        ?>


        <?php ActiveForm::end(); ?>

    </div>

</div>

<?php
$js = <<<JS
// get the form id and set the action url
$('#save-{$model->formName()}').on('click', function(e) {
    $('form#{$model->formName()}').attr("action","{$actionUrl}");
});
JS;
$this->registerJs($js);