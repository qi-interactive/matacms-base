<?php
use yii\web\View;

?>

<h3>Rearrange <?= \Yii::$app->controller->id ?></h3>
<ol class="smooth-sortable" data-rearrange-action-url="<?= $rearrangeActionUrl ?>">
	<?php
	foreach($dataProvider->models as $model):
		?>
	<li data-entity-pk="<?= \mata\helpers\ActiveRecordHelper::getPk($model) ?>"><?= $model->getLabel(); ?>
        <?xml version="1.0" encoding="utf-8"?>
        <!-- Generator: Adobe Illustrator 18.1.1, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
        viewBox="0 0 21 16" enable-background="new 0 0 21 16" xml:space="preserve">
        <g class="rearrangeable-icon">

            <polyline fill="none" stroke="#FFFFFF" stroke-width="1.4031" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="
            11.6,5.1 15.8,0.9 20.1,5.1  "/>

            <polyline fill="none" stroke="#FFFFFF" stroke-width="1.4031" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="
            15.8,0.9 15.8,15.1 11.4,15.1    "/>

            <polyline fill="none" stroke="#FFFFFF" stroke-width="1.4031" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="
            9.4,10.9 5.2,15.1 0.9,10.9  "/>

            <polyline fill="none" stroke="#FFFFFF" stroke-width="1.4031" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="
            5.2,15.1 5.2,0.9 9.6,0.9    "/>
        </g>
        <g class="tick-icon">

            <polyline fill="none" stroke="#5bbc60" stroke-width="4.25" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="
            17.8,2.2 7.2,13.8 2.2,7.9   "/>
        </g>
    </svg>

</li>
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
            
            var tickIcon = $('.tick-icon', ui.helper);
            var rearrangeableIcon = $('.rearrangeable-icon', ui.helper);
            
            tickIcon.fadeOut(250);
            rearrangeableIcon.fadeOut(250);


            tickIcon.fadeIn(100, function() {
                setTimeout(function() {
                    tickIcon.fadeOut(250);
                    rearrangeableIcon.fadeIn(250);
                }, 2500);
            });
            $('.smooth-sortable li').draggable('enable');
        }, matacms.rearrange.sortable.transitionDuration);
    }

}
);
JS;

$this->registerJs($script, View::POS_READY);

?>

