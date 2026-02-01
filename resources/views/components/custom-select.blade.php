@props(['options' => [], 'name' => '', 'id' => null, 'label' => '', 'placeholder' => 'Select an option', 'value' => '', 'required' => false])

@php
    $id = $id ?? $name;
    // Normalized options to handle both [value => label] and [[id => ..., name => ...]] formats
    $normalizedOptions = [];
    foreach ($options as $key => $option) {
        if (is_object($option)) {
            $normalizedOptions[] = ['value' => $option->id, 'label' => $option->name];
        } elseif (is_array($option) && isset($option['id'])) {
            $normalizedOptions[] = ['value' => $option['id'], 'label' => $option['name']];
        } else {
            $normalizedOptions[] = ['value' => $key, 'label' => $option];
        }
    }
@endphp

<div x-data="{
    open: false,
    selected: @js($value),
    options: @js($normalizedOptions),
    label: '',
    init() {
        // Find label for initial value
        const found = this.options.find(o => o.value == this.selected);
        this.label = found ? found.label : '';
    },
    select(option) {
        this.selected = option.value;
        this.label = option.label;
        this.open = false;
        $dispatch('input', this.selected);
        $dispatch('change', this.selected);
    },
    toggle() {
        this.open = !this.open;
    },
    close() {
        this.open = false;
    }
}" class="relative group min-w-[200px]" @click.outside="close()">

    <!-- Hidden Native Input for Form Submission -->
    <input type="hidden" :name="'{{ $name }}'" :value="selected" {{ $required ? 'required' : '' }}>

    <!-- Trigger Button -->
    <button type="button" @click="toggle()"
        class="relative flex items-center justify-between w-full rounded-xl border border-primary/20 bg-background text-text px-4 py-3 transition-all duration-300 hover:border-primary/50 focus:outline-none focus:border-primary focus:ring-0"
        :class="{'border-primary bg-background': open}">

        <span class="block truncate" x-text="label || '{{ $placeholder }}'"
            :class="{'text-text': label, 'text-text-muted': !label}"></span>

        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-primary">
            <svg class="h-5 w-5 transition-transform duration-300" :class="{'rotate-180': open}"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </span>
    </button>

    <!-- Floating Label (Optional) -->
    @if($label)
        <label @click="toggle()"
            class="absolute left-4 px-2 bg-background text-xs font-bold text-primary transition-all cursor-pointer pointer-events-none"
            style="top: -0.75rem; z-index: 10;">
            {{ $label }}
        </label>
    @endif

    <!-- Dropdown Menu -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-2 scale-95"
        class="absolute z-50 mt-2 w-full rounded-xl bg-background/95 backdrop-blur-xl shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none border border-primary/10 max-h-60 overflow-auto py-1 custom-scrollbar">

        <ul class="py-1" role="listbox">
            <template x-for="option in options" :key="option.value">
                <li @click="select(option)"
                    class="cursor-pointer select-none relative py-3 pl-4 pr-4 hover:bg-primary/10 transition-colors group"
                    :class="{'bg-primary/5 text-primary font-semibold': selected == option.value, 'text-text': selected != option.value}"
                    role="option">
                    <div class="flex items-center">
                        <span class="block truncate"
                            :class="{'font-semibold': selected == option.value, 'font-normal': selected != option.value}"
                            x-text="option.label"></span>
                    </div>


                </li>
            </template>
        </ul>
    </div>
</div>