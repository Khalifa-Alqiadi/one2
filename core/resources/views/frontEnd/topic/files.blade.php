@if(count($Topic->attachFiles)>0)
    <div class="extra-files mb-3 mt-3">
        <div class="row">
            @foreach($Topic->attachFiles as $attachFile)
                <?php
                if ($attachFile->$title_var != "") {
                    $file_title = $attachFile->$title_var;
                } else {
                    $file_title = $attachFile->$title_var2;
                }
                ?>
                <div class="col-lg-4 col-md-12 text-center">
                    <a class="card sidebar-card mb-3" href="{{ route("fileView",["path" =>'topics/'.$attachFile->file ]) }}"
                       target="_blank">
                        {!! Helper::GetIcon(route("fileView",["path" =>'topics/'.$attachFile->file ]),"64px",5) !!}
                        <div class="h5 mb-0 mt-2">{{ $file_title }}</div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif
