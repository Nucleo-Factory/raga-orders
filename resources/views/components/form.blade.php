@props(["action" => "#", "method" => "POST"])

<form action="{{ $action }}" method="{{ $method }}" class="space-y-8">
    @csrf
    {{ $slot }}
</form>
