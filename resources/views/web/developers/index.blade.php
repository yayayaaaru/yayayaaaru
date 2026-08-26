@props(['developers', 'source'])

@section('title', 'Разработчики — ' . $source->label())

<x-layouts::main>
{{--    <div class="container mt-4">--}}
{{--        {{ Breadcrumbs::render('developers', $source) }}--}}
{{--    </div>--}}
    <div class="page-header">
        <div class="container text-uppercase">
            <div class="page-title">Разработчики</div>
            <div class="text-muted">{{ $source->label() }}</div>
        </div>
    </div>
    <div class="page-body">
        <div class="container">
            <div class="row row-cards justify-content-around">
                @foreach($developers as $d)
                    <div class="col-auto" style="max-width: 11rem;">
                        <a
                            href="{{ route('developers.show', [$d->id, $d->slug]) }}"
                            class="d-flex flex-column"
                        >
                            <div class="avatar" style="--tblr-avatar-size: 10rem; background-image: url('{{ asset('static/media/avatar/not-found.png') }}'); background-size: cover;"></div>
                            <div class="mt-2" style="min-height: 1rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical;">
                                <strong>{{ $d->name }}</strong>
                            </div>
                            <div class="mt-2 d-flex">
                                <div class="text-muted">
                                    <span>0 подписчиков</span>
                                </div>
                                <div class="text-muted ms-3 d-none">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="currentColor"
                                        class="icon icon-tabler icons-tabler-filled icon-tabler-eye"
                                    >
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 4c4.29 0 7.863 2.429 10.665 7.154l.22 .379l.045 .1l.03 .083l.014 .055l.014 .082l.011 .1v.11l-.014 .111a.992 .992 0 0 1 -.026 .11l-.039 .108l-.036 .075l-.016 .03c-2.764 4.836 -6.3 7.38 -10.555 7.499l-.313 .004c-4.396 0 -8.037 -2.549 -10.868 -7.504a1 1 0 0 1 0 -.992c2.831 -4.955 6.472 -7.504 10.868 -7.504zm0 5a3 3 0 1 0 0 6a3 3 0 0 0 0 -6"/>
                                    </svg>
                                    <span>0</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
                <div class="col-lg-12  mt-5">
                    {{ $developers->onEachSide(0)->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</x-layouts::main>
