@if(Helper::GeneralWebmasterSettings("text_editor") ==2)
    <script src="{{ asset("assets/dashboard/js/ckeditor/ckeditor.js") }}"></script>
    <script>
        CKEDITOR.editorConfig = function (config) {
            config.language = '{{ @Helper::currentLanguage()->code }}';
            config.height = 500;
            config.uiColor = '#ffffff';
            config.toolbarCanCollapse = true;
            config.filebrowserImageBrowseUrl = '/file-manager/ckeditor';
            config.contentsCss = '{{ URL::asset('assets/dashboard/css/bootstrap/dist/css/bootstrap.min.css') }}?v={{ Helper::system_version() }}';
            config.extraPlugins = ['youtube', 'toc', 'codesnippet', 'twitterembed'];
        };
    </script>
@elseif(Helper::GeneralWebmasterSettings("text_editor") ==1)
    @if(!@$StopEditorCode)
        @include('dashboard.layouts.tinymce')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                initTinyMCE();
            });
        </script>
    @endif
@else
    <link rel="stylesheet" href="{{ asset('assets/dashboard/js/summernote/dist/summernote.css') }}">
    <script src="{{ asset("assets/dashboard/js/summernote/dist/summernote.js") }}"></script>
    <script>
        var activeSummernote = null;
        $(document).ready(function () {
            // File manager button (image icon)
            const FMButton = function (context) {
                const ui = $.summernote.ui;
                const button = ui.button({
                    contents: '<i class="note-icon-picture"></i> ',
                    tooltip: '{{ __("backend.fileManager") }}',
                    click: function () {
                        activeSummernote = context;
                        let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                        let y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;
                        let w = (x * 0.8);
                        let h = (y * 0.8);
                        var l = (screen.width / 2) - (w / 2);
                        var t = (screen.height / 2) - (h / 2);
                        window.open('/file-manager/summernote', 'fm', 'width=' + w + ',height=' + h + ', top=' + t + ', left=' + l);
                    }
                });
                return button.render();
            };

            $('.summernote').summernote({
                tabsize: 2,
                height: 500,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['height', ['height']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'fm', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                fontSizes: ['8', '9', '10', '11', '12', '13', '14', '16', '18', '20', '24', '30', '36', '32', '48', '64', '82', '150'],
                buttons: {
                    fm: FMButton
                }
            });
        });

        // set file link
        function fmSetLink(url) {
            if (activeSummernote) {
                activeSummernote.invoke('editor.insertImage', url);
            }
        }
    </script>
@endif
