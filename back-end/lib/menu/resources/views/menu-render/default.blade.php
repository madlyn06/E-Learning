<ul>
    @foreach($items as $item)
        <li class="menu-item menu-item-{{ $item->id }} {{ $item->class }} {{ $item->isActive() ? 'active' : '' }}">
            <a href="{{ $item->getUrl() }}" target="{{ $item->target }}">
                {!! $item->icon !!}
                <span class="menu-item-label">
                    {{ $item->label }}
                </span>
            </a>
            @if($item->children->count())
                @include('menu::menu-render.default', ['items' => $item->children])
            @endif
        </li>
    @endforeach
</ul>
