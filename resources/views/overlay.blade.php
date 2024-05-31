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
</head>
<body>

<div id="chrome" style="width: 1920px; height: 1080px;" class="bg-gray-100 absolute">
    <div id="heading"
         style="top: 10px;"
         class="w-1/4 p-4 absolute text-white text-center bg-red-500 mt-8 animate__animated hidden">
        <h1 class="">An animated element</h1>
    </div>
</div>
<script>
    window.addEventListener('DOMContentLoaded', function () {
        const elBeingHandled = document.getElementById('heading');
        let shown = false;
        elBeingHandled.addEventListener('animationend', () => {
            if (!shown) {
                elBeingHandled.classList.add('hidden');
                shown = false;
            }
        });
        Echo.channel('overlay.1')
            .listen('OverlayTriggerEvent', (e) => {
                elBeingHandled.removeEventListener('animationend', () => {
                    if (!shown) {
                        elBeingHandled.classList.add('hidden');
                        shown = false;
                    }
                });
                elBeingHandled.classList.remove('animate__flipOutX');
                elBeingHandled.classList.add('animate__flipInX');
                elBeingHandled.classList.remove('hidden');
                shown = true;
                window.setInterval(() => {
                    elBeingHandled.classList.remove('animate__flipInX');
                    elBeingHandled.classList.add('animate__flipOutX');
                }, 5000);
            });
    });
</script>
</body>
</html>

