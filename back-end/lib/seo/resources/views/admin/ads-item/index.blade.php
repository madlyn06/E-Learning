@if ($item && $item->id)
    @if ($items->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-bordered dt-responsive nowrap bootstrap4-styling">
                <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('seo::ads.keyword') }}</th>
                    <th>{{ __('seo::ads.image') }}</th>
                    <th>{{ __('seo::ads.image_search_google') }}</th>
                    <th>{{ __('seo::ads.is_active') }}</th>
                    <th nowrap>{{ __('seo::ads.created_at') }}</th>
                    <th nowrap></th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $adsItem)
                    <tr>
                        <td>{{ $adsItem->id }}</td>
                        <td>
                            <a href="{{ route('seo.admin.ads-items.edit', $adsItem->id) }}">
                                {{ $adsItem->title }}
                            </a>
                        </td>
                        <td>
                            <img src="{{ $adsItem->image->url ?? null }}" width="50px" height="50px"/>
                        </td>
                        <td>
                            <img src="{{ $adsItem->image_search_google->url ?? null }}" width="50px" height="50px"/>
                        </td>
                        <td>
                            @if($adsItem->is_active)
                                <i class="fas fa-check text-success"></i>
                            @endif
                        </td>
                        <td nowrap>{{ $adsItem->created_at }}</td>
                        <td nowrap class="text-right">
                            @admincan('seo.admin.ads.edit')
                                <a href="{{ route('seo.admin.ads-items.edit', $adsItem->id) }}" class="btn btn-success-soft btn-sm mr-1">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                            @endadmincan

                            @admincan('seo.admin.ads.destroy')
                                <table-button-delete url-delete="{{ route('seo.admin.ads-items.destroy', $adsItem->id) }}"></table-button-delete>
                            @endadmincan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
    <input name="ads_id" value="{{ $item->id }}" type="hidden"/>
    <a href="{{ route('seo.admin.ads-items.create', ['adsId' => $item->id]) }}"
        class="btn btn-sm btn-outline-primary mt-2">
        + Tạo mới
    </a>
@else
    <div class="alert alert-warning">Vui lòng tạo nhóm quảng cáo trước khi tạo từng quảng cáo.</div>
@endif