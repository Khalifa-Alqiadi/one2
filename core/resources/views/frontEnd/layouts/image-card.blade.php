<div class="image">
    @if ($model->photo_file != '')
        <img class="card-img-top" src="{{ route('fileView', ['path' => 'topics/' . $model->photo_file]) }}?w=450&h=450"
            width="100%" height="100%" alt="{{ $model->$title_var }}" loading="lazy" />
    @else
        <?php
        $img_url = '';
        ?>
        @if ($model->video_type == 1)
            <?php
            $url = Helper::getThumbnail($model->video_file);
            $img_url = $url['url'] ?? $url['webp'];
            ?>
            <img class="card-img-top" src="{{ $img_url }}" alt="{{ $model->$title_var }}" loading="lazy" />
        @else
            <div class="bg-secondary w-100 rounded-top h-200px"></div>
        @endif
    @endif
</div>
