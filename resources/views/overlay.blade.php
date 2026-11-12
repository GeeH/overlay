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
    @foreach($checklists as $checklist)
        {{-- Laid out at width / scale, then scaled, so it covers exactly `width` pixels on screen. --}}
        {{-- Hiding slides it with `translate`, which combines with the transform instead of replacing it. --}}
        <div id="checklist-{{ $checklist->id }}" class="absolute" data-checklist @if($checklist->auto_hide) data-auto-hide @endif
             style="top: {{ $checklist->top }}px; left: {{ $checklist->left }}px; width: {{ $checklist->width * 100 / $checklist->scale }}px;
                    transform: scale({{ $checklist->scale / 100 }}); transform-origin: top left;
                    transition: translate 900ms cubic-bezier(.65, 0, .35, 1);">
            @include('checklist-themes.' . $checklist->theme, ['checklist' => $checklist])
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

        // Checklists: re-render from the server on every change, then let the theme animate
        // the item that changed through its is-just-{action} and is-removing classes
        // (see resources/views/checklist-themes/default.blade.php).
        const checklistUrl = @js(route('overlay.checklist', ['username' => $user->username, 'checklistId' => '__ID__'], false));
        const animationDone = (node) => new Promise((resolve) => {
            node.addEventListener('animationend', resolve, {once: true});
            window.setTimeout(resolve, 1000);
        });

        // Hiding: a checklist slides off whichever 1920×1080 edge is nearest. With data-auto-hide it
        // does so after 45 seconds without changes; any change, or the dashboard toggle, brings it back.
        const hideAfter = 45000;
        const slideDuration = 900; // matches the wrapper's translate transition
        const hideTimers = new Map();
        const hide = (wrapper) => {
            window.clearTimeout(hideTimers.get(wrapper));
            // offsetLeft and offsetTop ignore the slide and the scale; the rect gives the size as drawn.
            const {width, height} = wrapper.getBoundingClientRect();
            const left = wrapper.offsetLeft, top = wrapper.offsetTop, margin = 60;
            const exits = [
                [left, `${-(left + width + margin)}px 0`],
                [1920 - left - width, `${1920 - left + margin}px 0`],
                [top, `0 ${-(top + height + margin)}px`],
                [1080 - top - height, `0 ${1080 - top + margin}px`],
            ];
            wrapper.style.translate = exits.sort((a, b) => a[0] - b[0])[0][1];
            wrapper.dataset.hidden = '';
        };
        const scheduleHide = (wrapper) => {
            window.clearTimeout(hideTimers.get(wrapper));
            if (wrapper.hasAttribute('data-auto-hide')) {
                hideTimers.set(wrapper, window.setTimeout(() => hide(wrapper), hideAfter));
            }
        };
        // Resolves once the checklist is on screen, so a change made while hidden plays where viewers see it.
        const show = async (wrapper) => {
            scheduleHide(wrapper);
            if (!wrapper.hasAttribute('data-hidden')) return;
            wrapper.style.translate = '';
            delete wrapper.dataset.hidden;
            await new Promise((resolve) => window.setTimeout(resolve, slideDuration));
        };
        document.querySelectorAll('[data-checklist]').forEach(scheduleHide);

        Echo.channel('overlay.{{ $user->id }}')
            .listen('ChecklistUpdatedEvent', async (e) => {
                const wrapper = document.getElementById(`checklist-${e.checklistId}`);
                // A new checklist, or a new position or theme, needs the whole page.
                if (!wrapper || e.action === 'updated') {
                    window.location.reload();
                    return;
                }

                if (e.action === 'toggled') {
                    wrapper.hasAttribute('data-hidden') ? show(wrapper) : hide(wrapper);
                    return;
                }
                await show(wrapper);

                if (e.action === 'removed') {
                    const old = wrapper.querySelector(`[data-item-id="${e.itemId}"]`);
                    if (old) {
                        old.classList.add('is-removing');
                        await animationDone(old);
                    }
                }

                const response = await fetch(checklistUrl.replace('__ID__', e.checklistId), {cache: 'no-store'});
                const html = await response.text();

                // Slide items that changed place (a move, or the gap a removal leaves) from where they were.
                // offsetTop is in the panel's own unscaled pixels, the same units translateY uses inside it.
                const items = () => wrapper.querySelectorAll('[data-item-id]');
                const before = new Map([...items()].map((node) => [node.dataset.itemId, node.offsetTop]));
                wrapper.innerHTML = html;
                items().forEach((node) => {
                    const delta = before.get(node.dataset.itemId) - node.offsetTop;
                    if (delta) {
                        node.animate([{transform: `translateY(${delta}px)`}, {transform: 'none'}],
                            {duration: 600, easing: 'cubic-bezier(.2, .8, .2, 1)'});
                    }
                });

                const target = e.action === 'reset'
                    ? wrapper.querySelector('.checklist')
                    : wrapper.querySelector(`[data-item-id="${e.itemId}"]`);
                target?.classList.add(`is-just-${e.action}`);
            });
    });
</script>
</body>
</html>

