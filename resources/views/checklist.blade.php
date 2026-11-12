<x-app-layout>
    @php($input = 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6')
    @php($button = 'rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500')

    <div class="mx-auto mt-8 grid w-full max-w-7xl grid-cols-1 gap-8 p-4 lg:grid-cols-5">

        @if(session('status'))
            <div class="lg:col-span-5 rounded-md bg-green-50 px-3 py-1.5 text-sm text-green-700">{{ session('status') }}</div>
        @endif

        @if($checklist->exists)
            {{-- Ticking, the part used live on stream --}}
            <section class="lg:col-span-3">
                <div class="mb-3 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">{{ $checklist->name }}</h2>
                    <div class="flex items-center gap-4">
                        @if($checklist->items->contains('done', true))
                            <form method="POST" action="{{ route('checklists.reset', $checklist->id) }}"
                                  onsubmit="return confirm('Untick every item?')">
                                @csrf
                                <button type="submit" class="text-sm font-semibold text-gray-500 hover:text-gray-900">Untick all</button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('checklists.visibility', $checklist->id) }}">
                            @csrf
                            <button type="submit" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                Show / hide on overlay
                            </button>
                        </form>
                    </div>
                </div>

                <ul class="divide-y divide-gray-200 overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-gray-200">
                    @forelse($checklist->items as $item)
                        <li class="flex items-center">
                            <form method="POST" action="{{ route('checklists.items.toggle', [$checklist->id, $item->id]) }}" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="flex w-full items-center gap-4 px-4 py-4 text-left text-lg hover:bg-gray-50">
                                    <span @class([
                                        'flex h-8 w-8 flex-none items-center justify-center rounded-md border-2',
                                        'border-emerald-500 bg-emerald-500 text-white' => $item->done,
                                        'border-gray-300 text-transparent' => ! $item->done,
                                    ])>
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10.5l4 4 8-9"/></svg>
                                    </span>
                                    <span @class(['text-gray-400 line-through' => $item->done, 'text-gray-900' => ! $item->done])>{{ $item->text }}</span>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('checklists.items.move', [$checklist->id, $item->id]) }}" class="flex">
                                @csrf
                                @method('PATCH')
                                @php($moves = ['up' => ['M5 12.5l5-5 5 5', $loop->first], 'down' => ['M5 7.5l5 5 5-5', $loop->last]])
                                @foreach($moves as $direction => [$path, $disabled])
                                    <button type="submit" name="direction" value="{{ $direction }}" @disabled($disabled)
                                            class="rounded p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-900 disabled:invisible" title="Move {{ $direction }}">
                                        <span class="sr-only">Move {{ $item->text }} {{ $direction }}</span>
                                        <svg class="h-6 w-6" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $path }}"/></svg>
                                    </button>
                                @endforeach
                            </form>
                            <form method="POST" action="{{ route('checklists.items.destroy', [$checklist->id, $item->id]) }}" class="pr-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded p-2 text-gray-300 hover:bg-red-50 hover:text-red-600" title="Delete item">
                                    <span class="sr-only">Delete {{ $item->text }}</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
                                </button>
                            </form>
                        </li>
                    @empty
                        <li class="px-4 py-6 text-center text-sm text-gray-500">No items yet. Add the first one below.</li>
                    @endforelse
                </ul>

                <form method="POST" action="{{ route('checklists.items.store', $checklist->id) }}" class="mt-4 flex gap-3">
                    @csrf
                    <input type="text" name="text" placeholder="Add an item…" required autofocus class="{{ $input }} flex-1">
                    <button type="submit" class="{{ $button }}">Add</button>
                </form>
                @error('text')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </section>
        @endif

        <section @class(['lg:col-span-2' => $checklist->exists, 'lg:col-span-5 mx-auto w-full max-w-xl' => ! $checklist->exists])>
            @if($checklist->exists)
                <h2 class="mb-3 text-xl font-semibold text-gray-900">On the overlay</h2>
                <div class="mb-8 overflow-hidden rounded-lg bg-gray-800 p-6">
                    {{-- Overlay pixels at the checklist's size, shrunk to fit the column --}}
                    <div style="width: {{ $checklist->width * 100 / $checklist->scale }}px; zoom: {{ min(1, 440 / $checklist->width) * $checklist->scale / 100 }};">
                        @include('checklist-themes.' . $checklist->theme, ['checklist' => $checklist])
                    </div>
                </div>
            @else
                <h2 class="mb-3 text-xl font-semibold text-gray-900">New checklist</h2>
            @endif

            <form method="POST"
                  action="{{ $checklist->exists ? route('checklists.update', $checklist->id) : route('checklists.store') }}"
                  class="flex flex-col gap-5 rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200">
                @csrf
                @if($checklist->exists) @method('PATCH') @endif

                <div>
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Title</label>
                    <input type="text" name="name" id="name" required class="{{ $input }} mt-2" value="{{ old('name', $checklist->name) }}">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="theme" class="block text-sm font-medium leading-6 text-gray-900">Theme</label>
                    <select name="theme" id="theme" class="{{ $input }} mt-2">
                        @foreach($themes as $theme)
                            <option value="{{ $theme }}" @selected(old('theme', $checklist->theme) === $theme)>{{ ucfirst($theme) }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Add a theme by adding a view to <code>resources/views/checklist-themes</code>.</p>
                    @error('theme')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    @foreach(['top' => 'Top (px)', 'left' => 'Left (px)', 'width' => 'Width (px)', 'scale' => 'Size (%)'] as $field => $label)
                        <div>
                            <label for="{{ $field }}" class="block text-sm font-medium leading-6 text-gray-900">{{ $label }}</label>
                            <input type="number" name="{{ $field }}" id="{{ $field }}" required class="{{ $input }} mt-2" value="{{ old($field, $checklist->$field) }}">
                            @error($field)<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    @endforeach
                </div>

                <div class="flex gap-3">
                    <input type="checkbox" name="auto_hide" id="auto_hide" value="1" @checked(old('auto_hide', $checklist->auto_hide))
                           class="mt-1 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                    <label for="auto_hide" class="text-sm leading-6">
                        <span class="font-medium text-gray-900">Auto-hide</span>
                        <span class="block text-gray-500">Slide off the nearest screen edge after 45 seconds without changes. Any change brings it back.</span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-x-6">
                    <a href="{{ route('dashboard') }}" class="text-sm font-semibold leading-6 text-gray-900">Back</a>
                    <button type="submit" class="{{ $button }}">{{ $checklist->exists ? 'Save' : 'Create' }}</button>
                </div>
            </form>
        </section>
    </div>
</x-app-layout>
