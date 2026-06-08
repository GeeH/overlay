<x-app-layout>
    <div class="w-3/4 flex flex-col self-center mt-8 bg-gray-50 mx-auto p-8">

        @if(session('status'))
            <div class="mb-4 w-full rounded-md bg-green-50 px-3 py-1.5 text-sm text-green-700">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('update-pane', $pane->id) }}" class="w-full">
            @csrf
            @method('PATCH')

            <div class="space-y-12">

                {{-- Basics --}}
                <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-3">
                    <div>
                        <h2 class="text-base font-semibold leading-7 text-gray-900">Basics</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600">What is this pane, and what will it say?</p>
                    </div>

                    <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 md:col-span-2">
                        <div class="sm:col-span-1">
                            <label class="block text-sm font-medium leading-6 text-gray-900">ID</label>
                            <div class="mt-2">
                                <input type="text" disabled
                                       class="block w-full rounded-md border-0 bg-gray-200 py-1.5 text-gray-500 ring-1 ring-inset ring-gray-200 sm:text-sm sm:leading-6"
                                       value="{{ $pane->id }}">
                            </div>
                        </div>

                        <div class="sm:col-span-5">
                            <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Name</label>
                            <div class="mt-2">
                                <input type="text" name="name" id="name"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                       value="{{ old('name', $pane->name) }}">
                                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="col-span-full">
                            <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                            <div class="mt-2">
                                <textarea id="description" name="description" rows="2"
                                          class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">{{ old('description', $pane->description) }}</textarea>
                            </div>
                            <p class="mt-1 text-sm leading-6 text-gray-500">Private note — not shown on the overlay.</p>
                        </div>

                        <div class="col-span-full">
                            <label for="text" class="block text-sm font-medium leading-6 text-gray-900">Text</label>
                            <div class="mt-2">
                                <input type="text" name="text" id="text"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                       value="{{ old('text', $pane->text) }}">
                                @error('text')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Appearance --}}
                <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-3">
                    <div>
                        <h2 class="text-base font-semibold leading-7 text-gray-900">Appearance</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600">Colours, font, and sizing.</p>
                    </div>

                    <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 md:col-span-2">
                        <div class="sm:col-span-2">
                            <label for="colour" class="block text-sm font-medium leading-6 text-gray-900">Text Colour</label>
                            <div class="mt-2">
                                <input type="text" name="colour" id="colour"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                       value="{{ old('colour', $pane->colour) }}">
                                @error('colour')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="bgColour" class="block text-sm font-medium leading-6 text-gray-900">Background</label>
                            <div class="mt-2">
                                <input type="text" name="bgColour" id="bgColour"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                       value="{{ old('bgColour', $pane->bgColour) }}">
                                @error('bgColour')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="size" class="block text-sm font-medium leading-6 text-gray-900">Font Size</label>
                            <div class="mt-2">
                                <input type="text" name="size" id="size" placeholder="48px"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                       value="{{ old('size', $pane->size) }}">
                                @error('size')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="font" class="block text-sm font-medium leading-6 text-gray-900">Font</label>
                            <div class="mt-2">
                                <input type="text" name="font" id="font" placeholder="Leave blank for default"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                       value="{{ old('font', $pane->font) }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Position & Size --}}
                <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-3">
                    <div>
                        <h2 class="text-base font-semibold leading-7 text-gray-900">Position &amp; Size</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600">Pixel offsets from top-left of the 1920×1080 canvas. Width/height of 0 means auto.</p>
                    </div>

                    <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 md:col-span-2">
                        <div class="sm:col-span-3">
                            <label for="top" class="block text-sm font-medium leading-6 text-gray-900">Top</label>
                            <div class="mt-2">
                                <input type="number" name="top" id="top"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                       value="{{ old('top', $pane->top) }}">
                                @error('top')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="left" class="block text-sm font-medium leading-6 text-gray-900">Left</label>
                            <div class="mt-2">
                                <input type="number" name="left" id="left"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                       value="{{ old('left', $pane->left) }}">
                                @error('left')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="width" class="block text-sm font-medium leading-6 text-gray-900">Width</label>
                            <div class="mt-2">
                                <input type="number" name="width" id="width"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                       value="{{ old('width', $pane->width) }}">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="height" class="block text-sm font-medium leading-6 text-gray-900">Height</label>
                            <div class="mt-2">
                                <input type="number" name="height" id="height"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                       value="{{ old('height', $pane->height) }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Animation & Behaviour --}}
                <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-3">
                    <div>
                        <h2 class="text-base font-semibold leading-7 text-gray-900">Animation &amp; Behaviour</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600">Use <a href="https://animate.style" target="_blank" class="text-indigo-600 underline">animate.css</a> class names, e.g. <code class="text-xs">animate__flipInX</code>.</p>
                    </div>

                    <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 md:col-span-2">
                        <div class="sm:col-span-3">
                            <label for="animationIn" class="block text-sm font-medium leading-6 text-gray-900">Animation In</label>
                            <div class="mt-2">
                                <input type="text" name="animationIn" id="animationIn" placeholder="animate__flipInX"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                       value="{{ old('animationIn', $pane->animationIn) }}">
                                @error('animationIn')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="animationOut" class="block text-sm font-medium leading-6 text-gray-900">Animation Out</label>
                            <div class="mt-2">
                                <input type="text" name="animationOut" id="animationOut" placeholder="animate__flipOutX"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                       value="{{ old('animationOut', $pane->animationOut) }}">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="showFor" class="block text-sm font-medium leading-6 text-gray-900">Show for (seconds)</label>
                            <div class="mt-2">
                                <input type="number" name="showFor" id="showFor" min="1"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                       value="{{ old('showFor', $pane->showFor) }}">
                                @error('showFor')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="sm:col-span-3 flex items-end pb-1">
                            <div class="flex items-center gap-x-3">
                                <input type="hidden" name="alwaysShown" value="0">
                                <input id="alwaysShown" name="alwaysShown" type="checkbox" value="1"
                                       class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                                       {{ old('alwaysShown', $pane->alwaysShown) ? 'checked' : '' }}>
                                <label for="alwaysShown" class="block text-sm font-medium leading-6 text-gray-900">Always shown</label>
                            </div>
                            <p class="ml-4 text-sm text-gray-500">(stays visible; trigger just replays the in-animation)</p>
                        </div>
                    </div>
                </div>

                {{-- Advanced --}}
                <div class="grid grid-cols-1 gap-x-8 gap-y-10 pb-12 md:grid-cols-3">
                    <div>
                        <h2 class="text-base font-semibold leading-7 text-gray-900">Advanced</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600">Extra CSS properties and Tailwind classes appended to the pane element.</p>
                    </div>

                    <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 md:col-span-2">
                        <div class="col-span-full">
                            <label for="extraClasses" class="block text-sm font-medium leading-6 text-gray-900">Extra Classes</label>
                            <div class="mt-2">
                                <input type="text" name="extraClasses" id="extraClasses" placeholder="p-4 rounded"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                       value="{{ old('extraClasses', $pane->extraClasses) }}">
                            </div>
                        </div>

                        <div class="col-span-full">
                            <label for="extraCss" class="block text-sm font-medium leading-6 text-gray-900">Extra CSS</label>
                            <div class="mt-2">
                                <textarea name="extraCss" id="extraCss" rows="3" placeholder="border: 2px solid red;"
                                          class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">{{ old('extraCss', $pane->extraCss) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('dashboard') }}" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                <button type="submit"
                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Save
                </button>
            </div>
        </form>

    </div>
</x-app-layout>
