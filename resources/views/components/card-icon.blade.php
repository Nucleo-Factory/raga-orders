@props(['icon', 'title', 'content'])

<div {{ $attributes->merge(['class' => 'rounded-2xl bg-[#FFFFFF] p-4 font-inter space-y-4 h-full']) }}>
    <div {{ $icon->attributes }}>
        {{ $icon }}
    </div>
    <div class="space-y-[0.375rem]">
        <h3 {{ $title->attributes }}>
            {{ $title }}
        </h3>
        <p {{ $content->attributes }}>
            {{ $content }}
        </p>
    </div>
</div>
