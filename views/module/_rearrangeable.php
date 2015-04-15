<?php
use yii\web\View;

?>

<h3>Rearrange <?= \Yii::$app->controller->id ?></h3>
<ol class="smooth-sortable" data-rearrange-action-url="<?= $rearrangeActionUrl ?>">
	<?php
	foreach($dataProvider->models as $model):
		?>
	<li data-entity-pk="<?= \mata\helpers\ActiveRecordHelper::getPk($model) ?>"><?= $model->getLabel(); ?></li>
<?php endforeach; ?>
</ol>

<?php 

$csrf = \Yii::$app->request->getCsrfToken();
$script = <<< JS

matacms.rearrange.init();

$('.smooth-sortable li').draggable(
  {
    axis: 'y',
    containment: 'parent',
    scroll: 'true',
    helper: 'original',
    start: matacms.rearrange.sortable.start,
    drag: matacms.rearrange.sortable.drag.throttle(17),
    stop: function(event, ui) {
    	$('.smooth-sortable li').draggable('disable');

        $(matacms.rearrange.sortable.items[matacms.rearrange.sortable.dragItemIndex].node).css({
        'top': matacms.rearrange.sortable.items[matacms.rearrange.sortable.dragItemIndex].displacement,
        'z-index': 9999
        });

        setTimeout(function() {
            // Keep the dragged item on top of other items during transition and then reset the Z-Index
            $(matacms.rearrange.sortable.items[matacms.rearrange.sortable.dragItemIndex].node)[0].style.zIndex = '';

            // Rewrite the dom to match the new order after everthing else is done.
            matacms.rearrange.sortable.items.forEach(function(item, i, items) {
              $(item.node).css('top', 0);
              $('.smooth-sortable').append(item.node);
            });

            // Re-enable dragging.
            $('.smooth-sortable li').draggable('enable');
            var actionUrl = $('.smooth-sortable').data('rearrange-action-url');
            var csrf = "$csrf";
            var items = $('.smooth-sortable li');
            var pks = $.map(items, function(item) {
                return $(item).data("entity-pk");
            });


            $.ajax({
                type: "POST",
                url: actionUrl,
                data: {"pks":pks, "_matacmscsrf": csrf},
                dataType: "json",
                success: function(data) {
                    console.log("success");
                },
              error: function() {
                       console.log("error");
                   }
               });
        }, matacms.rearrange.sortable.transitionDuration);
  }

}
);
JS;

$this->registerJs($script, View::POS_READY);

?>

