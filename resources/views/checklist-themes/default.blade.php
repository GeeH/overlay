{{--
    Checklist theme. Every view in this folder is a theme, named after its file; copy this one to add a game theme.

    A theme gets $checklist (with ->items) and must render:
      - a root element with the class "checklist"
      - one element per item with data-item-id="{{ $item->id }}"
    and scope its CSS under its own root class, because several themes can share the overlay page.

    The overlay re-renders the theme after every change and then adds one class for a theme to animate:
      - is-just-done, is-just-undone, is-just-added on the item that changed
      - is-just-reset on the root after every item was unticked
      - is-removing on an item about to be deleted (the overlay waits for its animation to end)
--}}
@php($done = $checklist->items->where('done', true)->count())
@php($total = $checklist->items->count())
<style>
    .cl-default {
        --cl-accent: #34d399;
        color: #f8fafc;
        background: rgba(15, 23, 42, 0.78);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 24px;
        padding: 28px 32px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.45);
        backdrop-filter: blur(8px);
        text-align: left;
    }
    .cl-default .cl-header { display: flex; align-items: baseline; justify-content: space-between; gap: 16px; }
    .cl-default .cl-title { font-size: 34px; font-weight: 700; letter-spacing: -0.01em; }
    .cl-default .cl-count { font-size: 22px; font-weight: 600; color: var(--cl-accent); font-variant-numeric: tabular-nums; }
    .cl-default .cl-bar { height: 6px; margin: 14px 0 18px; border-radius: 999px; background: rgba(255, 255, 255, 0.1); overflow: hidden; }
    .cl-default .cl-bar span { display: block; height: 100%; border-radius: inherit; background: var(--cl-accent); transition: width 600ms cubic-bezier(.2, .8, .2, 1); }
    .cl-default ul { list-style: none; margin: 0; padding: 0; display: grid; gap: 10px; }
    .cl-default li { display: flex; align-items: center; gap: 16px; font-size: 26px; line-height: 1.25; padding: 6px 10px; border-radius: 12px; }
    .cl-default .cl-box {
        flex: none; width: 30px; height: 30px; border-radius: 50%;
        border: 3px solid rgba(255, 255, 255, 0.45);
        display: grid; place-items: center;
    }
    .cl-default .cl-box svg { width: 18px; height: 18px; opacity: 0; }
    .cl-default li.is-done .cl-box { background: var(--cl-accent); border-color: var(--cl-accent); }
    .cl-default li.is-done .cl-box svg { opacity: 1; }
    .cl-default li.is-done .cl-text { opacity: 0.5; text-decoration: line-through; text-decoration-thickness: 2px; }

    .cl-default li.is-just-done { animation: cl-default-flash 900ms ease-out; }
    .cl-default li.is-just-done .cl-box { animation: cl-default-pop 500ms cubic-bezier(.2, 1.6, .4, 1); }
    .cl-default li.is-just-undone { animation: cl-default-nudge 400ms ease-in-out; }
    .cl-default li.is-just-added { animation: cl-default-in 500ms cubic-bezier(.2, .8, .2, 1); }
    .cl-default li.is-removing { animation: cl-default-out 400ms ease-in forwards; }
    .cl-default.is-just-reset { animation: cl-default-nudge 500ms ease-in-out; }

    @keyframes cl-default-pop { 0% { transform: scale(0.3); } 60% { transform: scale(1.35); } 100% { transform: scale(1); } }
    @keyframes cl-default-flash { 0% { background: rgba(52, 211, 153, 0.35); } 100% { background: transparent; } }
    @keyframes cl-default-nudge { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-6px); } 75% { transform: translateX(6px); } }
    @keyframes cl-default-in { from { opacity: 0; transform: translateX(-24px); } to { opacity: 1; transform: none; } }
    @keyframes cl-default-out { to { opacity: 0; transform: translateX(24px); } }
</style>
<div class="checklist cl-default">
    <div class="cl-header">
        <div class="cl-title">{{ $checklist->name }}</div>
        <div class="cl-count">{{ $done }} / {{ $total }}</div>
    </div>
    <div class="cl-bar"><span style="width: {{ $total ? round($done / $total * 100) : 0 }}%"></span></div>
    <ul>
        @foreach($checklist->items as $item)
            <li data-item-id="{{ $item->id }}" @class(['is-done' => $item->done])>
                <span class="cl-box">
                    <svg viewBox="0 0 20 20" fill="none" stroke="#0f172a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10.5l4 4 8-9"/></svg>
                </span>
                <span class="cl-text">{{ $item->text }}</span>
            </li>
        @endforeach
    </ul>
</div>
