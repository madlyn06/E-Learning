@extends('core::admin.master')

@section('meta_title', __('cms::story.edit.page_title'))

@section('page_title', __('cms::story.edit.page_title'))

@section('page_subtitle', __('cms::story.edit.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cms.admin.stories.index') }}">{{ trans('cms::story.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('cms::story.edit.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fs-17 font-weight-600 mb-0">
                                {{ $story->name }}
                            </h6>
                            <div>
                                <code> [story_code="{{ $item->slug }}"] </code>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="actions">
                                @admincan('cms.admin.stories.create')
                                <a href="{{ route('cms.admin.story-item.create', ['story_id' => $story->id]) }}" class="action-item">
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
                            <th nowrap>@translatableHeader</th>
                            <th nowrap></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $storyItem)
                            <?php
                            $srcFile = null;
                            if ($storyItem->addition_image) {
                                $srcFile = $storyItem->addition_image;
                            } else {
                                $mineType = $storyItem->image ? $storyItem->image->mime_type : null;
                                if ($mineType == 'video/mp4') {
                                    $srcFile = asset('vendor/media/images/types/video.png');
                                } elseif ( $mineType == 'audio/mpeg') {
                                    $srcFile = asset('vendor/media/images/types/mp3.png');
                                } else {
                                    $srcFile = $storyItem->getFirstMediaUrl('image');
                                }
                            }
                            ?>
                            <tr>
                                <td nowrap>{{ $storyItem->id }}</td>
                                <td nowrap>
                                    <a href="{{ route('cms.admin.story-item.edit', $storyItem->id) }}" style="height: 100px; display: block;">
                                        <img src="{{ $srcFile }}" alt="{{ $storyItem->name }}" class="img-thumbnail">
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('cms.admin.story-item.edit', $storyItem->id) }}">
                                        {{ $storyItem->name }}
                                    </a>
                                </td>
                                <td nowrap>
                                    @if($storyItem->is_active)
                                        <i class="fa fa-check text-success"></i>
                                    @else
                                        <i class="fa fa-times text-inverse"></i>
                                    @endif
                                </td>
                                <td nowrap>{{ $storyItem->sort_order }}</td>
                                <td nowrap>
                                    @translatableStatus(['editUrl' => route('cms.admin.story-item.edit', $storyItem->id), 'item' => $storyItem])
                                </td>
                                <td class="text-right" nowrap>
                                    @admincan('cms.admin.stories.edit')
                                    <a href="{{ route('cms.admin.story-item.edit', $storyItem->id) }}" class="btn btn-success-soft btn-sm mr-1">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    @endadmincan

                                    @admincan('cms.admin.stories.destroy')
                                    <table-button-delete url-delete="{{ route('cms.admin.story-item.destroy', $storyItem->id) }}"></table-button-delete>
                                    @endadmincan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <form action="{{ route('cms.admin.stories.update', $item->id) }}" method="POST">
                @method('PUT')
                @csrf

                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fs-17 font-weight-600 mb-0">
                                    {{ __('cms::story.edit.page_title') }}
                                </h6>
                            </div>
                            <div class="text-right">
                                <div class="btn-group">
                                    <button class="btn btn-success" type="submit">{{ __('core::button.save') }}</button>
                                    <button class="btn btn-primary" name="continue" value="1" type="submit">{{ __('core::button.save_and_edit') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('cms::admin.story._fields', ['item' => $item])
                    </div>
                    <div class="card-footer text-right">
                        <div class="btn-group">
                            <button class="btn btn-success" type="submit">{{ __('core::button.save') }}</button>
                            <button class="btn btn-primary" name="continue" value="1" type="submit">{{ __('core::button.save_and_edit') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
