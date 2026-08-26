<div
    {{
        $attributes->merge([
            'class' => 'card',
            'style' => 'white-space: pre-wrap;',
        ])
    }}
>
    <div class="card-body">{{ $slot }}</div>
</div>
