@if ($item->faqs->count() > 0)
<div class="card-body table-responsive p-0">
    <table class="table table-centered table-striped table-bordered mb-0 toggle-circle">
        <thead>
            <tr>
                <th>{{ __('STT') }}</th>
                <th>{{ __('Question') }}</th>
                <th>{{ __('Answer') }}</th>
                <th>{{ __('Position') }}</th>
                <th nowrap>{{ __('Created at') }}</th>
                <th nowrap></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td> {{ $item->question }}</td>
                    <td>{{ $item->answer }}</td>
                    <td>{{ $item->position }}</td>
                    <td nowrap>{{ $item->created_at }}</td>
                    <td nowrap class="text-right">
                        @admincan('cms.admin.page.edit')
                            <a href="{{ route('cms.admin.cms-faqs.edit', $item->id) }}" class="btn btn-success-soft btn-sm mr-1">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            @endadmincan
                        @admincan('cms.admin.ratings.destroy')
                            <table-button-delete url-delete="{{ route('cms.admin.cms-faqs.destroy', $item->id) }}"></table-button-delete>
                        @endadmincan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
    <div class="alert alert-warning">Chưa có thông tin FAQ.</div>
@endif

<a href="{{ route('cms.admin.cms-faqs.create', ['postId' => $post->id]) }}" class="btn btn-sm btn-outline-primary mt-2">
    + Tạo FAQ
</a>
