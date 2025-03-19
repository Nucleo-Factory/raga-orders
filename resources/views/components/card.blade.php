@props(['title', 'content'])

<div {{ $attributes->merge(['class' => 'bg-[#FDFDFD] rounded-[0.625rem] px-2 py-4 font-dm-sans']) }}>
    <h3 {{ $title->attributes }}>
        {{ $title }}
    </h3>
    <p {{ $content->attributes }}>
        {{ $content }}
    </p>
</div>
