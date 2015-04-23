<?php
use yii\helpers\Html;
use yii\web\View;
use matacms\widgets\ActiveForm;
use matacms\widgets\videourl\helpers\VideoUrlHelper;

?>

<div id="<?= $widget->id ?>" class="video-url">

    <div class="video-preview-container">
    <?php
    if(!empty($formModel->videoUrl)) {
        echo VideoUrlHelper::renderVideoPlayer($formModel->videoUrl);
    }
    ?>
    </div>
	
	<?php $form = ActiveForm::begin([
		'action' => $widget->endpoint,
        'enableClientValidation' => true,
        'id' => 'video-url-form-'.$widget->id
	]); ?>

	<?= $form->field($formModel, 'videoUrl'); ?>
    
    <?php
    if($widget->options['showSubmitButton']):
    ?>
    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
    </div>
    <?php 
    endif;
    ?>

    <?php ActiveForm::end(); ?>
	
	<?php 

	$this->registerJs("
        var oldValue = '" . $formModel->videoUrl . "';
        $('#" . $form->id . " input[name=\"VideoUrlForm[videoUrl]\"]').on('blur', function() {
            var vimeoPattern = /(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/,
            youtubePattern = /(?:https?:\/\/)?(?:www\.)?youtu(?:\.be|be\.com)\/(?:watch\?v=)?([\w-]{10,})/,
            value = this.value;
            
            if(value != oldValue) {
                var isVimeo = value.match(vimeoPattern), 
                isYoutube = value.match(youtubePattern);

                if(isVimeo) {
                    $('.video-preview-container').html('<iframe id=\"video-player\" src=\"//player.vimeo.com/video/' + isVimeo[5] + '?autoplay=0&api=1&player_id=video-player\" width=\"500\" height=\"281\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
                }
                if(isYoutube) {
                    $('.video-preview-container').html('<iframe width=\"500\" height=\"281\" src=\"http://www.youtube.com/embed/' + isYoutube[1] + '\"></iframe>');
                }
                oldValue = value;
            }
        });

		$('#" . $form->id . "').on('beforeSubmit', function(event, jqXHR, settings) {
            var form = $(this);
            if(form.find('.has-error').length) {
            	return false;
            }
            
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(data) {
                 	" . $widget->onComplete . "
                }
            });
            
            return false;
    	});", View::POS_READY);

?>
</div>
