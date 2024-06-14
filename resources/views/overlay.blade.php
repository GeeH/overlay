<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Stream Overlay</title>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
    <style>
        @foreach($panes as $pane)
            #{{ str_replace(' ', '-', $pane->name) }} {
                top: {{ $pane->top }}px;
                left: {{$pane->left}}px;
                @if($pane->width !== 0) width: {{ $pane->width .'px;'}} @endif;
                @if($pane->height !== 0) height: {{ $pane->height .'px;'}} @endif;
                background-color: {{ $pane->bgColour }};
                color: {{ $pane->colour }};
                font-size: {{ $pane->size }};
                @if($pane->font !== '') font-face: {{ $pane->font .';'}} @endif;
                {{ $pane->extraCss }}
            }
        @endforeach
    </style>
</head>
<body>

<div id="chrome" style="width: 1920px; height: 1080px;" class="bg-gray-100 absolute">
    @foreach($panes as $pane)
        <div id="{{ str_replace(' ', '-', $pane->name) }}"
             class="absolute text-center animate__animated {{ $pane->extraClasses }}
         @if(!$debug) hidden @endif
         ">
            {{ $pane->text }}
        </div>
    @endforeach
</div>
<script>
    window.addEventListener('DOMContentLoaded', function () {

        Echo.channel('overlay.1')
            .listen('OverlayTriggerEvent', (e) => {
                const pane = e.data;
                let shown = false;
                console.log(pane);
                const elBeingHandled = document.getElementById(pane.elementId);
                elBeingHandled.removeEventListener('animationend', () => {
                    if (!shown) {
                        elBeingHandled.classList.add('hidden');
                        shown = false;
                    }
                });
                elBeingHandled.classList.remove(pane.animationOut);
                elBeingHandled.classList.add(pane.animationIn);
                elBeingHandled.classList.remove('hidden');
                shown = true;
                window.setInterval(() => {
                    elBeingHandled.classList.remove(pane.animationIn);
                    elBeingHandled.classList.add(pane.animationOut);
                }, pane.showFor * 1000);
            });
    });
</script>
</body>
</html>

