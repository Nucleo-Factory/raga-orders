@props(['title', 'content'])

<div>
    <h1 {{ $title->attributes->merge(['class' => 'text-[3.25rem] font-black leading-[3.75rem]']) }}>
        {{ $title }}
    </h1>

    <p {{ $content->attributes->merge(['class' => 'text-lg']) }}>
        {{ $content }}
    </p>
</div>
