<x-app-layout>
    <div class="w-full p-4">

        @if(session('status'))
            <div class="mb-4 rounded-md bg-green-50 px-3 py-1.5 text-sm text-green-700">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('settings.update') }}" class="mb-6 flex items-end gap-4">
            @csrf
            @method('PATCH')
            <div>
                <label for="global_font" class="block text-sm font-medium text-gray-700">Global Font</label>
                <p class="text-xs text-gray-500 mb-1">Enter a <a href="https://fonts.google.com" target="_blank" class="underline">Google Fonts</a> family name, e.g. <code>Roboto</code> or <code>Pacifico</code></p>
                <input type="text" name="global_font" id="global_font" placeholder="e.g. Roboto"
                       class="rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 w-64"
                       value="{{ old('global_font', $user->global_font) }}">
            </div>
            <button type="submit"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                Save
            </button>
        </form>

        <div class="mb-4 flex justify-end">
            <a href="{{ route('create-pane') }}"
               class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                + New Overlay
            </a>
        </div>

        <ul role="list" class="w-full mt-3 grid grid-cols-1 gap-5 sm:grid-cols-2 sm:gap-6 lg:grid-cols-4">
            @foreach($panes as $pane)
                <li class="col-span-1 flex rounded-md shadow-sm">
                    <div
                        class="flex flex-1 items-center justify-between truncate rounded-r-md border-b border-r border-t border-gray-200 bg-white">
                        <div class="flex-1 truncate px-4 py-2 text-sm">
                            <a href="#" class="font-medium text-gray-900 hover:text-gray-600">{{ $pane->name }} ({{ $pane->triggered }})</a>
                            <p class="text-gray-500">{{ $pane->description }}</p>
                        </div>
                        <div class="flex-shrink-0 pr-2">
                            <a href="{{ route('add-edit-pane', ['paneId' => $pane->id]) }}" type="button"
                               class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-transparent bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <span class="sr-only">Open options</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path
                                        d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </li>

            @endforeach
        </ul>

    </div>
</x-app-layout>
