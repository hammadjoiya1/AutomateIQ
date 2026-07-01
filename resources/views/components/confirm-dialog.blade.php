<div x-data="confirmDialog()" x-on:confirm.window="showDialog($event.detail.message, $event.detail.form)" x-cloak>
    <div x-show="open" class="fixed inset-0 z-[999] flex items-center justify-center">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="cancel()"></div>
        <div
            class="relative w-full max-w-md rounded-2xl glass-panel border border-border p-6 shadow-2xl">
            <div class="text-lg font-semibold text-text">Confirm Action</div>
            <p class="mt-2 text-sm text-text/70" x-text="message"></p>

            <div class="mt-6 flex items-center justify-end gap-3">
                <button type="button" @click="cancel()"
                    class="px-4 py-2 rounded-lg border border-border text-text hover:bg-surface transition-colors">
                    Cancel
                </button>
                <button type="button" @click="confirm()"
                    class="px-4 py-2 rounded-lg bg-danger text-white hover:bg-danger/90 transition-colors">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDialog() {
        return {
            open: false,
            message: '',
            form: null,
            showDialog(message, form) {
                this.message = message || 'Are you sure?';
                this.form = form || null;
                this.open = true;
            },
            confirm() {
                if (this.form) {
                    this.form.submit();
                }
                this.open = false;
                this.form = null;
            },
            cancel() {
                this.open = false;
                this.form = null;
            }
        }
    }
</script>
