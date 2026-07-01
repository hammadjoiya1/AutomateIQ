<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-display font-bold text-text">Edit Tool</h1>
        <a href="{{ route('admin.tools.index') }}" class="btn btn-secondary px-4">Cancel</a>
    </div>

    <div class="card p-6 border border-border">
        <form action="{{ route('admin.tools.update', $tool) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-text mb-1">Tool Name</label>
                        <input type="text" name="name" value="{{ $tool->name }}" required
                            class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text focus:border-primary focus:ring-1 focus:ring-primary">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-text mb-1">Category</label>
                        <select name="category_id" required
                            class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text focus:border-primary focus:ring-1 focus:ring-primary">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $tool->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-text mb-1">Description</label>
                        <textarea name="description" rows="3"
                            class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text focus:border-primary focus:ring-1 focus:ring-primary">{{ $tool->description }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-text mb-1">Icon Upload</label>
                        @if($tool->icon)
                            <div class="mb-2">
                                <img src="{{ $tool->icon }}" class="w-12 h-12 rounded bg-bg-2 object-cover">
                            </div>
                        @endif
                        <input type="file" name="icon"
                            class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                    </div>
                </div>

                <!-- Settings -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-text mb-1">Output Format</label>
                        <select name="output_format"
                            class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text focus:border-primary focus:ring-1 focus:ring-primary">
                            <option value="text" {{ $tool->output_format == 'text' ? 'selected' : '' }}>Text</option>
                            <option value="markdown" {{ $tool->output_format == 'markdown' ? 'selected' : '' }}>Markdown
                            </option>
                            <option value="json" {{ $tool->output_format == 'json' ? 'selected' : '' }}>JSON</option>
                            <option value="code" {{ $tool->output_format == 'code' ? 'selected' : '' }}>Code</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-text mb-1">Daily Limit (Free Users)</label>
                        <input type="number" name="usage_limit" value="{{ $tool->usage_limit }}"
                            class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text focus:border-primary focus:ring-1 focus:ring-primary">
                        <p class="text-xs text-text-muted mt-1">0 = Unlimited</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-text mb-1">Cost Override (Credits)</label>
                        <input type="number" name="cost_credits" value="{{ $tool->cost_credits }}"
                            class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text focus:border-primary focus:ring-1 focus:ring-primary">
                        <p class="text-xs text-text-muted mt-1">Leave empty to use default cost rules.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-text mb-1">Daily Budget Cap (Credits)</label>
                        <input type="number" name="daily_budget_credits" value="{{ $tool->daily_budget_credits }}"
                            class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text focus:border-primary focus:ring-1 focus:ring-primary">
                        <p class="text-xs text-text-muted mt-1">Optional per-user daily cap for this tool.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-text mb-1">Tags</label>
                        <input type="text" name="tags" value="{{ $tool->tags->pluck('name')->implode(', ') }}"
                            class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text focus:border-primary focus:ring-1 focus:ring-primary">
                        <p class="text-xs text-text-muted mt-1">Comma separated.</p>
                    </div>

                    <div class="flex items-center gap-3 pt-6">
                        <div class="flex items-center gap-2">
                            <input type="hidden" name="status" value="0">
                            <input type="checkbox" name="status" value="1" {{ $tool->status ? 'checked' : '' }}
                                class="rounded border-border bg-bg-2 text-primary">
                            <label class="text-sm font-medium text-text">Active</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" {{ $tool->is_featured ? 'checked' : '' }} class="rounded border-border bg-bg-2 text-yellow-500">
                            <label class="text-sm font-medium text-text">Featured</label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-text mb-1">Prompt Template</label>
                        <textarea name="prompt_template" rows="5"
                            class="w-full font-mono text-sm bg-surface border border-border rounded-lg px-4 py-2 text-text focus:border-primary focus:ring-1 focus:ring-primary">{{ $tool->prompt_template }}</textarea>
                    </div>
                </div>
            </div>

            <!-- JSON Schema Editor -->
            <div x-data="{ 
                fields: {{ json_encode($tool->input_schema ?? []) }},
                addField() { this.fields.push({ name: '', type: 'text', label: '', required: false, placeholder: '', default: '', options: '' }) },
                removeField(index) { this.fields.splice(index, 1) }
            }" class="border-t border-border pt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg text-text">Input Fields Schema</h3>
                    <button type="button" @click="addField()" class="btn btn-sm btn-secondary">+ Add Field</button>
                </div>

                <div class="space-y-3">
                    <template x-for="(field, index) in fields" :key="index">
                        <div class="flex gap-3 items-start p-3 bg-surface rounded-lg border border-border">
                            <input type="text" x-model="field.name" placeholder="Field Name (key)"
                                class="flex-1 bg-bg-2 border border-border rounded px-2 py-1 text-sm text-text">
                            <input type="text" x-model="field.label" placeholder="Label"
                                class="flex-1 bg-bg-2 border border-border rounded px-2 py-1 text-sm text-text">
                            <select x-model="field.type"
                                class="bg-bg-2 border border-border rounded px-2 py-1 text-sm text-text">
                                <option value="text">Text</option>
                                <option value="textarea">Textarea</option>
                                <option value="number">Number</option>
                                <option value="select">Select</option>
                            </select>
                            <input type="text" x-model="field.placeholder" placeholder="Placeholder"
                                class="flex-1 bg-bg-2 border border-border rounded px-2 py-1 text-sm text-text">
                            <input type="text" x-model="field.default" placeholder="Default"
                                class="flex-1 bg-bg-2 border border-border rounded px-2 py-1 text-sm text-text">
                            <input type="text" x-model="field.options" placeholder="Options (comma separated)"
                                class="flex-1 bg-bg-2 border border-border rounded px-2 py-1 text-sm text-text">
                            <label class="flex items-center gap-1 text-xs text-text-muted">
                                <input type="checkbox" x-model="field.required"
                                    class="rounded border-border bg-bg-2 text-primary"> Req?
                            </label>
                            <button type="button" @click="removeField(index)"
                                class="text-danger hover:text-danger">×</button>
                        </div>
                    </template>
                </div>

                <!-- Hidden Input to store JSON -->
                <input type="hidden" name="input_schema" :value="JSON.stringify(fields)">
            </div>

            <div class="flex justify-end pt-6">
                <button type="submit" class="btn btn-primary px-6 py-2">Update Tool</button>
            </div>
        </form>
    </div>
</x-admin-layout>