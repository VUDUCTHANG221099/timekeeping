<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

<script type="text/javascript" src="{{ asset("$base_url/listbox/js/jquery.transfer.js?v=0.0.6") }}"></script>
{{-- @if (!Request::routeIs('project.get.detail')) 
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
@endif --}}

<script src="{{ asset("$base_url/website/js/adminlte.min.js") }}"></script>
{{-- @if (Request::routeIs('project.get.all'))
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
@endif --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>
<script src="{{ asset("$base_url/js/flatpickr.js") }}"></script>

<script src={{ asset("$base_url/js/main.js") }}></script>
@stack('scripts')
{{-- <script>
    $(document).ready(function() {
        setTimeout(() => {
            $('.danger_hide').hide();
        }, 3000)
    });
</script> --}}

