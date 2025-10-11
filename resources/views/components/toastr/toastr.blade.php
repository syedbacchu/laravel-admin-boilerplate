<script>
    // success message popup notification
    @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
    @endif

    // info message popup notification
    @if(Session::has('info'))
        toastr.info("{{ Session::get('info') }}");
    @endif

    // warning message popup notification
    @if($errors->any())
            @foreach($errors->getMessages() as $error)
            toastr.warning("{{ $error[0] }}");
                @break
            @endforeach
        @endif
    @if(Session::has('warning'))
        toastr.warning("{{ Session::get('warning') }}");
    @endif

    // error message popup notification
    @if(Session::has('dismiss'))
        toastr.error("{{ Session::get('dismiss') }}");
    @endif
    // error message popup notification
    @if(Session::has('error'))
        toastr.error("{{ Session::get('error') }}");
    @endif
</script>
