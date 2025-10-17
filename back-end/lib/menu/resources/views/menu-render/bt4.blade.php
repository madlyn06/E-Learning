<ul class="navbar-nav">
    @foreach($items as $item)
        @php($hasChild = $item->children->count())
        <li class="nav-item {{ $hasChild ? 'dropdown has-hover' : '' }} menu-item menu-item-{{ $item->id }} {{ $item->class }} {{ $item->isActive() ? 'active' : '' }}">
            <a class="nav-link {{ $hasChild ? 'dropdown-toggle' : '' }}"
               href="{{ $item->getUrl() }}"
               target="{{ $item->target }}"
            >
                {!! $item->icon !!}
                <span class="menu-item-label">
                    {{ $item->label }}
                </span>
            </a>
            @if($item->children->count())
                <div class="dropdown-menu">
                    @foreach($item->children as $child)
                        <a class="dropdown-item" href="{{ $child->getUrl() }}">
                            {{ $child->label }}
                        </a>
                    @endforeach
                </div>
            @endif
        </li>
    @endforeach
</ul>

@php(Asset::container('footer')->add('bt4-navbar-hover-dropdown', 'vendor/menu/css/web/bt4-navbar-hover-dropdown.css'))
