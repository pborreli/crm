/* global require */
require(['jquery', 'oro/widget-manager', 'oro/dialog-widget'],
function($, WidgetManager, DialogWidget){
    'use strict';
    $(function () {
        $(document).on('click', '.call-info-widget-link', function (e) {
            var element = $(e.currentTarget);
            var callId = element.data('call-id');
            var dialogAlias = 'call_info_widget_' + callId;

            // only one instance of widget is allowed
            if (element.data('widget-opened')) {
                return;
            } else {
                element.data('widget-opened', true);
            }

            // create and open widget
            var dialogUrl = element.data('call-info-widget-url');
            var dialogTitle = element.data('call-info-widget-title');
            var widget = new DialogWidget({
                url: dialogUrl,
                alias: dialogAlias,
                dialogOptions: {
                    width: 600,
                    title: dialogTitle
                }
            });

            widget.on('widgetRemove', function() {
                element.data('widget-opened', false);
            });

            widget.render();

            e.preventDefault();
        });
    });
});
