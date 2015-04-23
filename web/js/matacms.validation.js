/**
 * Yii validation module.
 *
 * This JavaScript module provides the validation methods for the built-in validators.
 *
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
window.matacms = window.matacms || {};

matacms.validation = (function ($) {
    var pub = {
        isEmpty: function (value) {
            return value === null || value === undefined || value == [] || value === '';
        },

        addMessage: function (messages, message, value) {
            messages.push(message.replace(/\{value\}/g, value));
        },

        videourl: function (value, messages, options) {
            if (options.skipOnEmpty && pub.isEmpty(value)) {
                return;
            }

            var isVimeo = value.match(options.vimeoPattern), 
            isYoutube = value.match(options.youtubePattern);

            if (!value.match(options.vimeoPattern) && !value.match(options.youtubePattern)) {
                pub.addMessage(messages, options.message, value);
            }
        }
        
    };

    return pub;
})(jQuery);
