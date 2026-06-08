<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Stream Overlay</title>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    @php
        $fontsToLoad = collect($panes->pluck('font'))->push($user->global_font)->filter()->unique()->values();
    @endphp
    @if($fontsToLoad->isNotEmpty())
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?{{ $fontsToLoad->map(fn($f) => 'family=' . urlencode($f))->implode('&') }}&display=swap"/>
    @endif
    <style>
        @if($user->global_font)
        body { font-family: '{{ $user->global_font }}', sans-serif; }
        @endif
        @foreach($panes as $pane)
            #{{ str_replace(' ', '-', $pane->name) }} {
                top: {{ $pane->top }}px;
                left: {{$pane->left}}px;
                @if($pane->width !== 0) width: {{ $pane->width .'px;'}} @endif;
                @if($pane->height !== 0) height: {{ $pane->height .'px;'}} @endif;
                background-color: {{ $pane->bgColour }};
                color: {{ $pane->colour }};
                font-size: {{ $pane->size }};
                @if($pane->font !== '') font-family: '{{ $pane->font }}', sans-serif; @endif
                {{ $pane->extraCss }}
            }
        @endforeach
    </style>
</head>
<body>

<div id="chrome" style="width: 1920px; height: 1080px;" class="absolute">
    <div class="text-3xl p-16 animate__animated animate__bounceOut w-full text-center animate__delay-2s">
        Loaded {{ $user->username }}</div>
    @foreach($panes as $pane)
        <div id="{{ str_replace(' ', '-', $pane->name) }}"
             class="absolute text-center animate__animated {{ $pane->extraClasses }}
         @if(!$debug && !$pane->alwaysShown) hidden @endif
         ">
            {{ $pane->text }}
        </div>
    @endforeach
</div>
<script>
    const animateCSS = (element, animation) =>
        new Promise((resolve, reject) => {
            const node = document.getElementById(element);
            node.classList.add('animate__animated', animation);

            // When the animation ends, we clean the classes and resolve the Promise
            function handleAnimationEnd(event) {
                event.stopPropagation();
                node.classList.remove('animate__animated', animation);
                resolve('Animation ended');
            }

            node.addEventListener('animationend', handleAnimationEnd, {once: true});
        });

    window.addEventListener('DOMContentLoaded', function () {

        Echo.channel('overlay.1')
            .listen('OverlayTriggerEvent', (e) => {
                const pane = e.data;

                if (pane.alwaysShown) {
                    console.log('always shown', pane);
                    animateCSS(pane.elementId, pane.animationIn);
                    return;
                }

                const element = document.getElementById(pane.elementId);
                console.log('in and out', pane);
                element.classList.remove('hidden');
                animateCSS(pane.elementId, pane.animationIn);
                window.setTimeout(() => {
                    console.log('timeout running', pane);
                    animateCSS(pane.elementId, pane.animationOut).then(() => {
                        element.classList.add('hidden');
                    });
                }, 5000);

            });
    });
</script>
</body>
</html>

