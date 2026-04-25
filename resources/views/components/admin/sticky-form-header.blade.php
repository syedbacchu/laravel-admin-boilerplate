<div {{ $attributes }} class="sticky-form-header" style="position: fixed; top: 0; left: 0; right: 0; z-index: 100; background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(5px); border-bottom: 1px solid #e5e7eb; padding: 1rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 max-w-[95%] mx-auto">
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                {{ $title }}
            </h1>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            {{-- Back Button --}}
            @if(isset($backRoute) && $backRoute)
                <a href="{{ route($backRoute) }}" class="btn btn-outline-primary">
                    {{ $backText }}
                </a>
            @endif

            {{-- Submit/Save Button --}}
            @if(isset($submitText) && $submitText)
                <button type="{{ $submitType ?? 'submit' }}"
                        class="{{ $submitClass ?? 'btn btn-primary' }}"
                        @if(isset($formId) && $formId) form="{{ $formId }}" @endif>
                    {{ $submitText }}
                </button>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stickyHeader = document.querySelector('.sticky-form-header');
    if (stickyHeader) {
        let lastScroll = 0;

        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;

            // Show header when scrolling down, hide when scrolling up (optional)
            // Or always show:
            stickyHeader.style.transform = currentScroll > 50 ? 'translateY(0)' : 'translateY(-100%)';

            // Always show instead (uncomment below to use):
            stickyHeader.style.transform = 'translateY(0)';

            lastScroll = currentScroll;
        });

        // Always show from the start
        stickyHeader.style.transform = 'translateY(0)';
    }
});
</script>

<style>
.sticky-form-header {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    z-index: 9999 !important;
    transform: translateY(0);
    transition: transform 0.3s ease;
}
</style>
