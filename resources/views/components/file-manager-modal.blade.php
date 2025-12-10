<div
    x-data="fileManagerModal()"
    x-show="open"
    x-cloak
    class="fixed inset-0 bg-black/60 flex items-center justify-center z-[9999]"
>
    <div class="bg-white w-11/12 md:w-3/4 h-4/5 rounded shadow relative">

        <!-- Close Button -->
        <button class="absolute top-2 right-2 text-xl" @click="close()">✖</button>

        <!-- Load file manager via iframe or AJAX -->
        <iframe
            src="{{ route('fileManager.all') }}"
            class="w-full h-full border-0"
        ></iframe>
    </div>
</div>

<script>
    function fileManagerModal() {
        return {
            open: false,
            callback: null,

            init() {
                // Listen for open event
                window.addEventListener('open-file-manager', (e) => {
                    this.callback = e.detail.callback;
                    this.open = true;
                });

                // Listen for selected file coming from iframe
                window.addEventListener('file-selected', (e) => {
                    if (this.callback) {
                        window.dispatchEvent(new CustomEvent(this.callback, { detail: e.detail }));
                    }
                    this.close();
                });
            },

            close() {
                this.open = false;
            }
        }
    }
</script>
