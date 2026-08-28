@props(['rateable'])

<form
    action="{{--{{ route('ratings.store') }}--}}"
    method="post"
    {{ $attributes->merge() }}
>
    @csrf
    <input type="hidden" name="rateable[type]" value="{{ $rateable::class }}" autocomplete="off">
    <input type="hidden" name="rateable[id]" value="{{ $rateable->id }}" autocomplete="off">
    <button type="submit" class="btn btn-link p-0" name="rating[type]" value="upvote" title="Поставить плюсик" data-loading-text>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-arrow-big-up m-0">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M10.586 3l-6.586 6.586a2 2 0 0 0 -.434 2.18l.068 .145a2 2 0 0 0 1.78 1.089h2.586v7a2 2 0 0 0 2 2h4l.15 -.005a2 2 0 0 0 1.85 -1.995l-.001 -7h2.587a2 2 0 0 0 1.414 -3.414l-6.586 -6.586a2 2 0 0 0 -2.828 0z"/>
        </svg>
    </button>
    <div class="text-center" style="min-width: 40px;">{{ $rateable->rating_total }}</div>
    <button type="submit" class="btn btn-link p-0" name="rating[type]" value="downvote" title="Поставить минус" data-loading-text>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-arrow-big-down m-0">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M10 2l-.15 .005a2 2 0 0 0 -1.85 1.995v6.999l-2.586 .001a2 2 0 0 0 -1.414 3.414l6.586 6.586a2 2 0 0 0 2.828 0l6.586 -6.586a2 2 0 0 0 .434 -2.18l-.068 -.145a2 2 0 0 0 -1.78 -1.089l-2.586 -.001v-6.999a2 2 0 0 0 -2 -2h-4z"/>
        </svg>
    </button>
</form>
