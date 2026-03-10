<div class="tab-pane {{  ( Session::get('active_tab') == 'socialTab') ? 'active' : '' }}"
     id="tab-3">
    <div class="p-a-md"><h5><i class="material-icons">&#xe80d;</i>
            &nbsp; {!!  __('backend.siteSocialSettings') !!}</h5></div>
    <div class="p-a-md col-md-12">
        <button class="btn info m-b" type="button" id="addSocialLinkBtn">
            <i class="material-icons">&#xe145;</i> {{ __("backend.addNewLink") }}
        </button>
        <div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped white dk" id="socialLinksTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th width="5%" class="text-center">#</th>
                        <th>{{ __("backend.sectionIcon") }}</th>
                        <th>{{ __("backend.title") }}</th>
                        <th>{{ __("backend.link") }}</th>
                        <th style="width: 120px" class="text-center">{{ __("backend.options") }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <button class="btn white dk" type="button" id="saveSocialOrderBtn">
                <i class="material-icons">&#xe161;</i> {{ __("backend.saveOrder") }}
            </button>
        </div>
    </div>
</div>

@push("after-scripts")

    <div class="modal fade" id="socialLinkModal" tabindex="-1" role="dialog" aria-labelledby="socialLinkModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="socialLinkModalLabel">
                        <i class="material-icons">&#xe145;</i> {{ __("backend.addNewLink") }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="socialLinkForm">
                    <div class="modal-body">
                        <input type="hidden" id="row_id" name="row_id">
                        <div class="form-group">
                            <label for="title">{{ __("backend.title") }}</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="url">{{ __("backend.link") }}</label>
                            <input type="text" class="form-control" id="url" autocomplete="off" name="url" required>
                        </div>
                        <div class="form-group">
                            <label for="icon">{{ __("backend.sectionIcon") }}</label>
                            <div class="input-group">
                                        <span class="input-group-addon">
                                            <i id="iconPreview" class=""></i>
                                        </span>
                                <input type="text" class="form-control" autocomplete="off" name="icon" id="iconPicker" value="" required>
                                <span class="input-group-btn">
            <button class="btn white" type="button" id="iconPickerButton">{!!  __('backend.chooseIcon') !!}</button>
          </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="icon">{{ __("backend.colorBackground") }}</label>
                            <div id="iconColorPicker" class="input-group colorpicker-component">
                                <input type="text" autocomplete="off" name="color" id="iconColor" value="#000000" required dir="ltr" class="form-control"/>
                                <span class="input-group-addon" id="iconColorPickerBG"><i></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.cancel') }}</button>
                        <button type="submit" class="btn info">{{ __('backend.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" id="animate">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('backend.confirmation') }}</h5>
                </div>
                <div class="modal-body text-center p-lg">
                    <h5 class="m-b-0">
                        {{ __('backend.confirmationDeleteMsg') }}
                    </h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark-white p-x-md"
                            data-dismiss="modal">{{ __('backend.no') }}</button>
                    <button type="button" id="confirmDelete" class="btn danger p-x-md">{{ __('backend.yes') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div>
    </div>

    <div class="modal fade" id="iconModal" tabindex="-1" role="dialog" aria-labelledby="iconModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="iconModalLabel">{!!  __('backend.chooseIcon') !!}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-a-0 m-b">
                    <div class="icon-search-container p-a">
                        <div class="row">
                            <div class="col-sm-7 col-xs-6">
                                <div class="form-group mb-0">
                                    <input type="text" class="form-control" id="iconSearch" placeholder="{!!  __('backend.search') !!}...">
                                </div>
                            </div>
                            <div class="col-sm-5 col-xs-6">
                                <div class="form-group mb-0">
                                    <select class="form-control c-select" id="iconStyle">
                                        <option value="all">All Styles</option>
                                        <option value="solid">Solid</option>
                                        <option value="regular">Regular</option>
                                        <option value="brands">Brands</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="iconsContainer" class="p-a" dir="ltr">
                        <!-- Icons will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker-custom.js") }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            // Load social links on page load
            loadSocialLinks();

            // Add button click handler
            $('#addSocialLinkBtn').click(function () {
                $('#socialLinkModalLabel').html('<i class="material-icons">&#xe145;</i>  {{ __("backend.addNewLink") }}');
                $('#socialLinkForm')[0].reset();
                $('#row_id').val('');
                $('#iconColorPickerBG i').css('background-color', "#000000");
                $("#iconPreview").removeClass();
                $('#socialLinkModal').modal('show');
            });

            // Form submit handler
            $('#socialLinkForm').submit(function (e) {
                e.preventDefault();

                const formData = $(this).serializeArray();
                const socialLink = {};

                formData.forEach(item => {
                    socialLink[item.name] = item.value;
                });

                saveSocialLink(socialLink);
            });

            // Delete button click handler (delegated for dynamic elements)
            $(document).on('click', '.delete-btn', function () {
                const rowID = $(this).data('row-id');
                $('#confirmDelete').data('row-id', rowID);
                $('#deleteModal').modal('show');
            });

            $('#saveSocialOrderBtn').click(function () {
                var rowInputs = $('#socialLinksTable input[name="row_nums[]"]');
                var orderData = [];
                var hasChanges = false;

                // Loop through each input to compare values
                rowInputs.each(function () {
                    var $input = $(this);
                    var currentValue = parseInt($input.val());
                    var originalValue = parseInt($input.data('row-id'));

                    if (currentValue !== originalValue) {
                        hasChanges = true;
                    }

                    orderData.push({
                        row_id: originalValue,
                        new_position: currentValue
                    });
                });

                var $btn = $(this);
                $btn.prop('disabled', true).html('<i class="material-icons">&#xe161;</i> {{ __("backend.saveOrder") }}...');

                $.ajax({
                    url: '{{ route("settingsSaveSocialLinksOrder") }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        order_data: orderData,
                        _token: $('meta[name="csrf-token"]').attr('content') // For Laravel, if applicable
                    },
                    success: function (response) {
                        loadSocialLinks();
                        swal({
                            title: "<span class='text-success'>" + response.message + "</span>",
                            text: "",
                            html: true,
                            type: "success",
                            confirmButtonText: "{{ __("backend.close") }}",
                            confirmButtonColor: "#acacac",
                            timer: 5000,
                        });

                    },
                    error: function (xhr) {
                        console.error(xhr);
                        swal({
                            title: "<span class='text-danger'>" + xhr.responseJSON.message + "</span>",
                            text: "",
                            html: true,
                            type: "error",
                            confirmButtonText: "{{ __("backend.close") }}",
                            confirmButtonColor: "#acacac",
                        });
                    },
                    complete: function () {
                        $btn.prop('disabled', false).html('<i class="material-icons">&#xe161;</i> {{ __("backend.saveOrder") }}');
                    }
                });
            });

            $(document).on('input', 'input[name="row_nums[]"]', function() {
                // Remove any non-digit characters
                $(this).val($(this).val().replace(/[^0-9]/g, ''));
                let val = parseInt($(this).val());
                if (isNaN(val) || val < 1) {
                    $(this).val(1); // Set default to 1 if invalid
                }
            });

            // Confirm delete handler
            $('#confirmDelete').click(function () {
                const rowID = $(this).data('row-id');
                deleteSocialLink(rowID);
            });
            let social_colors = {
                'facebook': '#1877f2',
                'twitter': '#1da1f2',
                'instagram': '#e4405f',
                'linkedin': '#0077b5',
                'youtube': '#ff0000',
                'pinterest': '#bd081c',
                'snapchat': '#fffc00',
                'whatsapp': '#25d366',
                'telegram': '#0088cc',
                'flickr': '#ff0084',
                'vimeo': '#1ab7ea',
            };
            $('#iconColorPicker').colorpicker({
                colorSelectors: social_colors
            });
        });

        function loadSocialLinks() {
            $.ajax({
                url: '{{ route("settingsGetSocialLinks") }}',
                method: 'GET',
                success: function (response) {
                    const tbody = $('#socialLinksTable tbody');
                    tbody.empty();

                    if (response.social_links && response.social_links.length > 0) {
                        response.social_links.forEach(link => {
                            const row = `
                        <tr>
                            <td class="text-center"><input type="text" name="row_nums[]" data-row-id="${link.row_id}" value="${link.row_no}" class="form-control row_no light has-value"></td>
                            <td><a class="social-link-item" style="background-color: ${link.color}"><i class="${link.icon} fa-lg"></i></a></td>
                            <td>${link.title}</td>
                            <td><a href="${link.url}" target="_blank">${link.url}</a></td>
                            <td class="text-center">
                                <button class="btn btn-sm primary edit-btn" type="button" data-row-id="${link.row_id}">
                                    <i class="material-icons">&#xe150;</i>
                                </button>
                                <button class="btn btn-sm danger delete-btn"  type="button" data-row-id="${link.row_id}">
                                    <i class="material-icons">&#xe872;</i>
                                </button>
                            </td>
                        </tr>
                    `;
                            tbody.append(row);
                        });

                        // Edit button click handler
                        $('.edit-btn').click(function () {
                            const rowID = parseInt($(this).data('row-id'));
                            const link = response.social_links.find(l => parseInt(l.row_id) === rowID);
                            if (link) {
                                $('#socialLinkModalLabel').html('<i class="material-icons">&#xe150;</i>  {{ __("backend.editLink") }}');
                                $('#row_id').val(link.row_id);
                                $('#title').val(link.title);
                                $('#url').val(link.url);
                                $('#iconPicker').val(link.icon);
                                $("#iconPreview").removeClass();
                                $('#iconPreview').addClass(link.icon);
                                $('#iconColor').val(link.color);
                                $('#iconColorPickerBG i').css('background-color', link.color);
                                $('#socialLinkModal').modal('show');
                            }
                        });
                        $('#saveSocialOrderBtn').show();
                    } else {
                        $('#saveSocialOrderBtn').hide();
                        tbody.append('<tr><td colspan="5" class="text-center">{{ __('backend.noData') }}</td></tr>');
                    }
                },
                error: function (xhr) {
                    console.error(xhr);
                }
            });
        }

        function saveSocialLink(link) {
            $.ajax({
                url: '{{ route("settingsSaveSocialLinks") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    social_link: link
                },
                success: function (response) {
                    $('#socialLinkModal').modal('hide');
                    loadSocialLinks();

                    swal({
                        title: "<span class='text-success'>" + response.message + "</span>",
                        text: "",
                        html: true,
                        type: "success",
                        confirmButtonText: "{{ __("backend.close") }}",
                        confirmButtonColor: "#acacac",
                        timer: 5000,
                    });
                },
                error: function (xhr) {
                    console.error(xhr);
                    swal({
                        title: "<span class='text-danger'>" + xhr.responseJSON.message + "</span>",
                        text: "",
                        html: true,
                        type: "error",
                        confirmButtonText: "{{ __("backend.close") }}",
                        confirmButtonColor: "#acacac",
                    });
                }
            });
        }

        function deleteSocialLink(rowID) {
            $.ajax({
                url: '{{ route("settingsDeleteSocialLinks") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    row_id: rowID
                },
                success: function (response) {
                    $('#deleteModal').modal('hide');
                    loadSocialLinks();
                    swal({
                        title: "<span class='text-success'>" + response.message + "</span>",
                        text: "",
                        html: true,
                        type: "success",
                        confirmButtonText: "{{ __("backend.close") }}",
                        confirmButtonColor: "#acacac",
                        timer: 5000,
                    });
                },
                error: function (xhr) {
                    console.error(xhr);
                    swal({
                        title: "<span class='text-danger'>" + xhr.responseJSON.message + "</span>",
                        text: "",
                        html: true,
                        type: "error",
                        confirmButtonText: "{{ __("backend.close") }}",
                        confirmButtonColor: "#acacac",
                    });
                }
            });
        }
    </script>
@endpush
