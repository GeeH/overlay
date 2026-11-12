{{-- Enshrouded: dark ornate panel, gold trim, Shroud mist, and items that ignite like the Flame. See default.blade.php for the theme contract. --}}
@php($done = $checklist->items->where('done', true)->count())
@php($total = $checklist->items->count())
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;800&family=Marcellus&display=swap">
<style>
    .cl-enshrouded {
        --gold: #e2c27d;
        --gold-dim: #8c6d3a;
        --ember: #ff8a3d;
        --shroud: 128, 36, 92;
        position: relative;
        padding: 34px 40px 32px;
        color: #ebe2cc;
        font-family: 'Marcellus', serif;
        text-align: left;
        background:
            radial-gradient(120% 140% at 0% 0%, rgba(var(--shroud), 0.38), transparent 55%),
            linear-gradient(180deg, rgba(16, 19, 21, 0.93), rgba(8, 10, 11, 0.95));
        border: 1px solid var(--gold-dim);
        box-shadow:
            inset 0 0 0 5px rgba(8, 10, 11, 0.9),
            inset 0 0 0 6px rgba(226, 194, 125, 0.35),
            0 0 50px rgba(0, 0, 0, 0.65);
        overflow: hidden;
    }
    /* Slow drifting Shroud mist behind the content. */
    .cl-enshrouded::before {
        content: '';
        position: absolute;
        inset: -40%;
        background:
            radial-gradient(35% 30% at 30% 70%, rgba(var(--shroud), 0.28), transparent 70%),
            radial-gradient(30% 25% at 75% 30%, rgba(var(--shroud), 0.2), transparent 70%);
        animation: cl-ens-mist 18s ease-in-out infinite alternate;
        pointer-events: none;
    }
    .cl-enshrouded > * { position: relative; }
    .cl-enshrouded .cl-corner {
        position: absolute; width: 12px; height: 12px;
        background: var(--gold); transform: rotate(45deg);
        box-shadow: 0 0 10px rgba(226, 194, 125, 0.6);
    }
    .cl-enshrouded .cl-corner:nth-of-type(1) { top: 0; left: 0; }
    .cl-enshrouded .cl-corner:nth-of-type(2) { top: 0; right: 0; }
    .cl-enshrouded .cl-corner:nth-of-type(3) { bottom: 0; left: 0; }
    .cl-enshrouded .cl-corner:nth-of-type(4) { bottom: 0; right: 0; }

    .cl-enshrouded .cl-title {
        font-family: 'Cinzel', serif; font-weight: 800; font-size: 32px;
        letter-spacing: 0.14em; text-transform: uppercase; text-align: center;
        background: linear-gradient(180deg, #fff2cf 0%, var(--gold) 55%, #9a7440 100%);
        -webkit-background-clip: text; background-clip: text; color: transparent;
        filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.8));
    }
    .cl-enshrouded.is-complete .cl-title { animation: cl-ens-glow 2.4s ease-in-out infinite; }
    .cl-enshrouded .cl-divider { display: flex; align-items: center; gap: 12px; margin: 12px 0 6px; color: var(--gold); font-size: 12px; }
    .cl-enshrouded .cl-divider::before,
    .cl-enshrouded .cl-divider::after { content: ''; flex: 1; height: 1.5px; background: linear-gradient(90deg, transparent, var(--gold-dim), var(--gold)); }
    .cl-enshrouded .cl-divider::after { transform: scaleX(-1); }
    .cl-enshrouded .cl-progress {
        text-align: center; font-family: 'Cinzel', serif; font-weight: 600; font-size: 17px;
        letter-spacing: 0.2em; text-transform: uppercase; color: var(--gold-dim); margin-bottom: 10px;
    }
    .cl-enshrouded .cl-progress b { color: var(--gold); font-weight: 600; }
    .cl-enshrouded .cl-bar { height: 4px; margin: 0 0 22px; background: rgba(226, 194, 125, 0.12); }
    .cl-enshrouded .cl-bar span {
        display: block; height: 100%;
        background: linear-gradient(90deg, #b3471b, var(--ember), #ffd27a);
        box-shadow: 0 0 12px rgba(255, 138, 61, 0.8);
        transition: width 800ms cubic-bezier(.2, .8, .2, 1);
    }

    .cl-enshrouded ul { list-style: none; margin: 0; padding: 0; display: grid; gap: 12px; }
    .cl-enshrouded li { position: relative; display: flex; align-items: center; gap: 20px; font-size: 25px; line-height: 1.3; padding: 4px 8px; }
    .cl-enshrouded .cl-mark {
        flex: none; width: 20px; height: 20px; margin: 0 4px; transform: rotate(45deg);
        border: 2px solid var(--gold-dim); background: rgba(0, 0, 0, 0.4);
    }
    .cl-enshrouded li.is-done .cl-mark {
        border-color: #ffd27a;
        background: radial-gradient(circle at 50% 50%, #fff3c4 0%, var(--ember) 45%, #a8360f 100%);
        box-shadow: 0 0 10px rgba(255, 138, 61, 0.9), 0 0 24px rgba(255, 138, 61, 0.45);
    }
    .cl-enshrouded li.is-done .cl-text { color: #8f8672; text-decoration: line-through; text-decoration-color: rgba(226, 194, 125, 0.7); text-decoration-thickness: 2px; }

    .cl-enshrouded li.is-just-done::before {
        content: ''; position: absolute; inset: 0; pointer-events: none;
        background: linear-gradient(90deg, transparent, rgba(255, 138, 61, 0.35), transparent);
        animation: cl-ens-sweep 900ms ease-out forwards;
    }
    .cl-enshrouded li.is-just-done .cl-mark { animation: cl-ens-ignite 900ms cubic-bezier(.2, 1.4, .4, 1); }
    .cl-enshrouded li.is-just-undone .cl-mark { animation: cl-ens-snuff 600ms ease-out; }
    .cl-enshrouded li.is-just-added { animation: cl-ens-emerge 900ms ease-out; }
    .cl-enshrouded li.is-removing { animation: cl-ens-dissolve 700ms ease-in forwards; }
    .cl-enshrouded.is-just-reset { animation: cl-ens-shroud 1200ms ease-out; }

    @keyframes cl-ens-mist { from { transform: translate(-6%, 4%); } to { transform: translate(6%, -4%); } }
    @keyframes cl-ens-ignite {
        0% { transform: rotate(45deg) scale(0.4); box-shadow: 0 0 0 rgba(255, 138, 61, 0); }
        45% { transform: rotate(225deg) scale(1.6); box-shadow: 0 0 30px 10px rgba(255, 170, 80, 0.9); }
        100% { transform: rotate(405deg) scale(1); }
    }
    @keyframes cl-ens-snuff { 0% { box-shadow: 0 0 20px rgba(var(--shroud), 0.9); } 100% { box-shadow: none; } }
    @keyframes cl-ens-sweep { from { transform: translateX(-100%); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
    @keyframes cl-ens-emerge { from { opacity: 0; filter: blur(10px); transform: translateY(8px); } to { opacity: 1; filter: none; transform: none; } }
    @keyframes cl-ens-dissolve { to { opacity: 0; filter: blur(10px); transform: translateX(30px); color: rgb(var(--shroud)); } }
    @keyframes cl-ens-shroud { 0% { box-shadow: inset 0 0 120px rgba(var(--shroud), 0.9), 0 0 50px rgba(0, 0, 0, 0.65); } 100% { box-shadow: inset 0 0 0 5px rgba(8, 10, 11, 0.9), inset 0 0 0 6px rgba(226, 194, 125, 0.35), 0 0 50px rgba(0, 0, 0, 0.65); } }
    @keyframes cl-ens-glow { 0%, 100% { filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.8)); } 50% { filter: drop-shadow(0 0 14px rgba(255, 190, 100, 0.9)); } }
</style>
<div @class(['checklist', 'cl-enshrouded', 'is-complete' => $total > 0 && $done === $total])>
    <span class="cl-corner"></span><span class="cl-corner"></span><span class="cl-corner"></span><span class="cl-corner"></span>
    <div class="cl-title">{{ $checklist->name }}</div>
    <div class="cl-divider">&#9670;</div>
    <div class="cl-progress"><b>{{ $done }}</b> of {{ $total }} complete</div>
    <div class="cl-bar"><span style="width: {{ $total ? round($done / $total * 100) : 0 }}%"></span></div>
    <ul>
        @foreach($checklist->items as $item)
            <li data-item-id="{{ $item->id }}" @class(['is-done' => $item->done])>
                <span class="cl-mark"></span>
                <span class="cl-text">{{ $item->text }}</span>
            </li>
        @endforeach
    </ul>
</div>
