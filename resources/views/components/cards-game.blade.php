@props(['games'])

<div class="row row-cards justify-content-around">
    @foreach($games as $g)
        <div class="col-auto" style="max-width: 11rem;">
            <a href="{{ sprintf('/games/%d/%s', $g->id, $g->slug) }}" class="d-flex flex-column">
                <div class="avatar" style="--tblr-avatar-size: 10rem; background-image: url('{{ asset('static/media/avatar/not-found.png') }}'); background-size: cover;"></div>
                <div class="mt-2" style="min-height: 2.5rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                    <strong>{{ $g->title }}</strong>
                </div>
                <div class="mt-2 d-flex">
                    <div class="text-muted">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="icon icon-tabler icons-tabler-filled icon-tabler-thumb-up"
                        >
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M13 3a3 3 0 0 1 2.995 2.824l.005 .176v4h2a3 3 0 0 1 2.98 2.65l.015 .174l.005 .176l-.02 .196l-1.006 5.032c-.381 1.626 -1.502 2.796 -2.81 2.78l-.164 -.008h-8a1 1 0 0 1 -.993 -.883l-.007 -.117l.001 -9.536a1 1 0 0 1 .5 -.865a2.998 2.998 0 0 0 1.492 -2.397l.007 -.202v-1a3 3 0 0 1 3 -3z"/>
                            <path d="M5 10a1 1 0 0 1 .993 .883l.007 .117v9a1 1 0 0 1 -.883 .993l-.117 .007h-1a2 2 0 0 1 -1.995 -1.85l-.005 -.15v-7a2 2 0 0 1 1.85 -1.995l.15 -.005h1z"/>
                        </svg>
                        <span>0%</span>
                    </div>
                    <div class="text-muted ms-3">
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
    <div class="col-lg-12 mt-5">
        {{ $games->onEachSide(0)->links('vendor.pagination.bootstrap-5') }}
    </div>
</div>
