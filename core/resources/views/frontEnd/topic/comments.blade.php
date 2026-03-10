@if($WebmasterSection->comments_status)
    <div id="comments">
        @if(count($Topic->approvedComments)>0)
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="mb-3 mt-4"><i
                            class="fa-regular fa-comments"></i> {{ __('frontend.comments') }}
                    </h3>
                </div>
            </div>
            <div class="card mb-3">
                @foreach($Topic->approvedComments as $comment)
                        <?php
                        $dtformated = date('d M Y h:i A', strtotime($comment->date));
                        ?>

                    <div class="d-flex flex-row p-3"><img
                            src="{{ route("fileView",["path" =>'contacts/profile.jpg' ]) }}" width="40"
                            height="40" class="rounded-circle me-3">
                        <div class="w-100">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-row align-items-center"><strong
                                        class="me-2">{{$comment->name}}</strong></div>
                                <small class="text-muted">{{ $dtformated }}</small>
                            </div>
                            <p class="text-justify comment-text mb-0">
                                {!! nl2br(strip_tags($comment->comment)) !!}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                <br>
                <h4><i class="fa-solid fa-plus"></i> {{ __('frontend.newComment') }}</h4>
                <div class="bottom-article">
                    <form method="POST" action="{{ route("commentSubmit") }}" class="commentForm" id="commentForm">
                        @csrf
                        @honeypot
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="comment_name"
                                       class="form-control-label">{!!  __('frontend.name') !!}</label>
                                <input type="text" autocomplete="off" name="comment_name" id="comment_name" value="{{ @Auth::user()->name }}" required maxlength="100" placeholder="{{ __('frontend.yourName') }}" class="form-control"/>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="comment_email"
                                       class="form-control-label">{!!  __('frontend.email') !!}</label>
                                <input type="email" autocomplete="off" name="comment_email" id="comment_email" value="{{ @Auth::user()->email }}" required maxlength="100" placeholder="{{ __('frontend.yourEmail') }}" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea name="comment_message" id="comment_message" class="form-control" placeholder="{{ __('frontend.comment') }}" rows="5" required></textarea>
                        </div>

                        @if(config('smartend.nocaptcha_status'))
                            <div class="form-group mb-3">
                                {!! NoCaptcha::renderJs(@Helper::currentLanguage()->code) !!}
                                {!! NoCaptcha::display() !!}
                            </div>
                        @endif
                        <div class="submit-message"></div>
                        <div>
                            <input type="hidden" name="topic_id" value="{{$Topic->id}}">
                            <button type="submit" id="commentFormSubmit"
                                    class="btn btn-lg btn-theme"><i
                                    class="fa-solid fa-paper-plane"></i> {{ __('frontend.sendComment') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('after-scripts')
        <script type="text/javascript">
            $(document).ready(function () {
                $('#commentForm').submit(function (evt) {
                    evt.preventDefault();
                    let btn = $('#commentFormSubmit');
                    btn.html("<img src=\"{{ asset('assets/dashboard/images/loading.gif') }}\" style=\"height: 20px\"/> {!! __('frontend.sendComment') !!}");
                    btn.prop('disabled', true);
                    var formData = new FormData(this);
                    $.ajax({
                        type: "POST",
                        url: "{{ route("commentSubmit") }}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (result) {
                            let stat = 'alert-danger';
                            if (result.stat === 'success') {
                                stat = 'alert-success';
                                $('#commentForm')[0].reset();
                            }
                            let confirm = '<div class="alert ' + stat + ' alert-dismissible fade show mt-3" role="alert">' + result.msg + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                            $("#commentForm .submit-message").html(confirm);
                            btn.html('<i class="fa-solid fa-paper-plane"></i> {!! __('frontend.sendComment') !!}');
                            btn.prop('disabled', false);
                        }
                    });
                    return false;
                });
            });
        </script>
    @endpush
@endif
