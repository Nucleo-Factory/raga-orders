@props(["action" => "#", "method" => "POST", "class" => ""])

<form action="{{ $action }}" method="{{ $method }}" class="{{ $class }} space-y-8">
    @csrf
    {{ $slot }}
</form>
