<div>

    <!-- Filters -->
    <div class="px-4 pt-0 mb-0">
        <div class="form-row justify-content-between">

            <div class="col-md-auto mb-2">
                <label class="form-label"></label>
                <div class="col px-0 mb-1 text-md-left text-center">
                </div>
            </div>

            <div class="col-md-auto mb-2">
                <div class="form-row justify-content-between">

                    <div class="col-12 col-md-auto px-0 text-md-right text-center" wire:ignore>
                        <label class="form-label"></label>
                        <div class="d-none" id="datatable-buttons">
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

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

    <!-- Styles -->
    @push('styles')
        @include('thotam-auth::livewire.auth.sub.style')
    @endpush
</div>
