(function ($) {
    $(function () {
        initModalContent();
        initToolbar();
    });

    function initToolbar() {
        var toolbar = $('<div class="toolbar"></div>');
        var btnViewContent = $('<button type="button" class="view-content"><i class="fa fa-file-text-o"></i> Get content</button>');
        toolbar.appendTo(document.body);
        toolbar.append(btnViewContent);

        btnViewContent.on('click', function () {
            var modal = $('#modal-content');
            modal.find('.content-html').html(
                beautifyHtml(
                    $('#content-area').keditor('getContent')
                )
            );

            modal.modal('show');
        });
    }

    function initModalContent() {
        var modal = $(
            '<div id="modal-content" class="modal fade" tabindex="-1">' +
            '    <div class="modal-dialog modal-lg">' +
            '        <div class="modal-content">' +
            '            <div class="modal-header">' +
            '                <button type="button" class="close" data-dismiss="modal">&times;</button>' +
            '                <h4 class="modal-title">Content</h4>' +
            '            </div>' +
            '            <div class="modal-body">' +
            '                <pre class="prettyprint lang-html content-html"></pre>' +
            '            </div>' +
            '            <div class="modal-footer">' +
            '                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
            '            </div>' +
            '        </div>' +
            '    </div>' +
            '</div>'
        );

        modal.appendTo(document.body);
    }

    function beautifyHtml(htmlCode) {
        htmlCode = html_beautify(htmlCode, {
            'indent_size': '4',
            'indent_char': ' ',
            'space_after_anon_function': true,
            'end_with_newline': true
        });
        htmlCode = htmlCode.replace(/</g, '&lt;').replace(/>/g, '&gt;');

        return PR.prettyPrintOne(htmlCode, 'lang-html');
    }

    function beautifyJs(jsCode) {
        jsCode = js_beautify(jsCode, {
            'indent_size': '4',
            'indent_char': ' ',
            'space_after_anon_function': true,
            'end_with_newline': true
        });

        return PR.prettyPrintOne(jsCode, 'lang-js');
    }

})(jQuery);
