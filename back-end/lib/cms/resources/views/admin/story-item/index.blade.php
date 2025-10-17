@extends('core::admin.master')

@section('meta_title', __('cms::story-item.index.page_title'))

@section('page_title', __('cms::story-item.index.page_title'))

@section('page_subtitle', $story->name)

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a
                    href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item"><a
                    href="{{ route('cms.admin.stories.index') }}">{{ trans('cms::story.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ $story->name }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ $story->name }}
                    </h6>
                    <div>
                        <code> [story_code="{{ $story->slug }}"] </code>
                    </div>
                </div>
                <div class="text-right">
                    <div class="actions">
                        @admincan('cms.admin.story.create')
                            <a href="{{ route('cms.admin.story-item.create', ['story_id' => $story->id]) }}"
                                class="action-item">
                                <i class="fa fa-plus"></i>
                                {{ __('core::button.add') }}
                            </a>
                        @endadmincan
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered dt-responsive nowrap bootstrap4-styling">
                <thead>
                    <tr>
                        <th nowrap>{{ __('ID') }}</th>
                        <th nowrap>{{ __('cms::story-item.image') }}</th>
                        <th nowrap>{{ __('cms::story-item.name') }}</th>
                        <th nowrap>{{ __('cms::story-item.is_active') }}</th>
                        <th nowrap>{{ __('cms::story-item.sort_order') }}</th>
                        <th nowrap>{{ __('cms::story-item.created_at') }}</th>
                        <th nowrap>@translatableHeader</th>
                        <th nowrap></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <?php
                        $srcFile = null;
                        if ($item->addition_image) {
                            $srcFile = $item->addition_image;
                        } else {
                            if ($item->image) {
                                $mineType = $item->image->mime_type;
                                if ($mineType == 'video/mp4') {
                                    $srcFile = asset('vendor/media/images/types/video.png');
                                } elseif ($mineType == 'audio/mpeg') {
                                    $srcFile = asset('vendor/media/images/types/mp3.png');
                                } else {
                                    $srcFile = $item->getFirstMediaUrl('image');
                                }
                            }
                        }
                        ?>
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>
                                <a href="{{ route('cms.admin.story-item.edit', $item->id) }}"
                                    style="height: 100px; display: block;">
                                    <img src="{{ $srcFile }}" alt="{{ $item->name }}" class="img-thumbnail">
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('cms.admin.story-item.edit', $item->id) }}">
                                    {{ $item->name }}
                                </a>
                            </td>
                            <td nowrap>
                                @if ($item->is_active)
                                    <i class="fa fa-check text-success"></i>
                                @else
                                    <i class="fa fa-times text-inverse"></i>
                                @endif
                            </td>
                            <td>{{ $item->sort_order }}</td>
                            <td nowrap>{{ $item->created_at }}</td>
                            <td>
                                @translatableStatus(['editUrl' => route('cms.admin.story-item.edit', $item->id)])
                            </td>
                            <td nowrap class="text-right">
                                @admincan('cms.admin.story-item.edit')
                                    <a href="{{ route('cms.admin.story-item.edit', $item->id) }}"
                                        class="btn btn-success-soft btn-sm mr-1">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                @endadmincan

                                @admincan('cms.admin.story-item.destroy')
                                    <table-button-delete
                                        url-delete="{{ route('cms.admin.story-item.destroy', $item->id) }}"></table-button-delete>
                                @endadmincan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- {!! $items->appends(Request::all())->render() !!} --}}
        </div>
    </div>
@stop
