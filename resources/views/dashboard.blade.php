@php
    $etapaArray = ["e1" => "Etapa 1", "e2" => "Etapa 2"];
@endphp

<x-app-layout>
    <div class="iframe-container">
        <div class="responsive-iframe">
            <iframe src="https://lookerstudio.google.com/embed/reporting/f268693f-20aa-474e-9e4d-8385e033cd41/page/8tSBF" frameborder="0" allowfullscreen sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox"></iframe>
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
