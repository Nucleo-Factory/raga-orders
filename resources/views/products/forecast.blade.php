@php
    $etapaArray = ["e1" => "Etapa 1", "e2" => "Etapa 2"];
@endphp

<x-app-layout>
    <div class="iframe-container">
        <div class="responsive-iframe">
            <iframe width="600" height="450" src="https://lookerstudio.google.com/embed/u/0/reporting/7376cafd-6ff1-455f-9db7-e4da3f1a100b/page/8tSBF" frameborder="0" style="border:0" allowfullscreen sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox"></iframe>
        </div>
        <style>
            .iframe-container {
                height: calc(100vh - 100px); /* Altura total de la ventana menos espacio para header/footer */
            }
            .responsive-iframe {
                position: relative;
                overflow: hidden;
                height: 100%; /* Usa toda la altura disponible */
            }
            .responsive-iframe iframe {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                border: 0;
            }
        </style>
    </div>
</x-app-layout>
