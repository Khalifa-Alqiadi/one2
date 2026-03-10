<?php
$tiny_key = Helper::GeneralWebmasterSettings("tiny_key");
if ($tiny_key == "") {
    $tiny_key = "no-key";
}
?>
<script src="https://cdn.tiny.cloud/1/{{ $tiny_key }}/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>

<script>
    function initTinyMCE(selector = '.tinymce') {
        tinymce.remove(selector);
        tinymce.init({
            selector: selector,
            height: 550,
            directionality: '{{ @Helper::currentLanguage()->direction }}',
            language: '{{ @Helper::currentLanguage()->code }}',
            relative_urls: false,
            remove_script_host: false,
            convert_urls: true,
            plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap emoticons',
            imagetools_cors_hosts: ['picsum.photos'],
            menubar: 'file edit view insert format tools table help',
            toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save | insertfile image media link anchor codesample | ltr rtl',
            promotion: false,
            branding: false,
            content_css: "{{ URL::asset('assets/dashboard/css/bootstrap/dist/css/bootstrap.min.css') }}?v={{ Helper::system_version() }}",
            toolbar_sticky: false,
            toolbar_mode: 'sliding',
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            },
            file_picker_callback(callback, value, meta) {
                let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth
                let y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight

                tinymce.activeEditor.windowManager.openUrl({
                    url: '/file-manager/tinymce5',
                    title: '{{ __("backend.fileManager") }}',
                    width: x * 0.8,
                    height: y * 0.8,
                    onMessage: (api, message) => {
                        callback(message.content, {text: message.text})
                    }
                })
            }
        });
    }
</script>
<style>
    .tox-notification--warning {
        display: none !important;
    }
</style>
