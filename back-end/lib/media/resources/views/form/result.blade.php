@foreach($medias as $media)
    <div class="col-md-2 editImageSelected" data-id="{{ $media->id }}" data-name="{{ $media->file_name }}" data-filetype="{{ $media->mime_type }}" data-url="{{ $media->getUrl() }}" data-src="{{ Img::url($media->getUrl(), 300, 300) }}">
        <div class="card">
            <a href="#" class="icon-menu-item col-4 editImage" data-toggle="modal" data-target="#edit" data-id="{{$media->id}}">
                @php
                    $type = explode('/', $media->mime_type)[0];
                    $ext = '';
                @endphp
                @if ($type == 'image')
                    <img width="130px" height="130px" style="padding: 15px;" src="{{ Img::url($media->getUrl(), 300, 300) }}">
                @elseif ($type == 'audio')
                    <img width="130px" height="130px" style="padding: 15px;" src="{{ asset('vendor/media/images/types/mp3.png') }}">
                    <?php $ext = '.mp3' ?>
                @elseif ($type == 'video')
                    <img width="130px" height="130px" style="padding: 15px;" src="{{ asset('vendor/media/images/types/video.png') }}">
                    <?php $ext = '.mp4' ?>
                @else
                    <img width="130px" height="130px" style="padding: 15px;" src="{{ asset('vendor/media/images/types/text.png')}}">
                @endif
                <a style="text-align: center !important;" href="{{ $media->getUrl() }}" target="_blank">{{$media->name}}{{ $ext }}</a>
            </a>
        </div>
    </div>
@endforeach
