<div>
    <!-- Incluce các modal -->
    @include('thotam-auth::livewire.auth.modal.add_edit_modal')
    @include('thotam-auth::livewire.auth.modal.link_modal')
    @include('thotam-auth::livewire.auth.modal.reset_password_modal')

    <!-- Scripts -->
    @push('livewires')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                window.thotam_livewire = @this;
                Livewire.emit("dynamic_update_method");
            });
        </script>
    @endpush
</div>
