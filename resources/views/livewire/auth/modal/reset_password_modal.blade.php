<div wire:ignore.self class="modal fade" id="reset_password_modal" tabindex="-1" role="dialog" aria-labelledby="reset_password_modal" aria-hidden="true" data-toggle="modal" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content py-2">
            <div class="modal-header">
                <h4 class="modal-title text-indigo"><span class="fas fa-user mr-3"></span>{{ $modal_title }}</h4>
                <button type="button" wire:click.prevent="cancel()" class="close" thotam-blockui data-dismiss="modal" wire:loading.attr="disabled" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="container-fluid mx-0 px-0">
                    <div class="row">

                        @if ($resetStatus)
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="col-form-label">Họ tên:</label>
                                    <div id="name_div">
                                        <span class="form-control px-2"  style="width: 100%">{{ $name }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="col-form-label">Email:</label>
                                    <div id="name_div">
                                        <span class="form-control px-2"  style="width: 100%">{{ $email }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="col-form-label">Số điện thoại:</label>
                                    <div id="name_div">
                                        <span class="form-control px-2"  style="width: 100%">{{ $phone }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="col-form-label text-indigo" for="password">Mật khẩu mới:</label>
                                    <div id="password_div">
                                        <input type="password" class="form-control px-2" wire:model.lazy="password" id="password" style="width: 100%" placeholder="Mật khẩu mới ..." autocomplete="off">
                                    </div>
                                    @error('password')
                                        <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="col-form-label text-indigo" for="password_confirmation">Xác nhân mật khẩu mới:</label>
                                    <div id="password_confirmation_div">
                                        <input type="password" class="form-control px-2" wire:model.lazy="password_confirmation" id="password_confirmation" style="width: 100%" placeholder="Xác nhân mật khẩu mới ..." autocomplete="off">
                                    </div>
                                    @error('password_confirmation')
                                        <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>

                        @endif

                    </div>
                </div>
            </div>

            <div class="modal-footer mx-auto">
                <button wire:click.prevent="cancel()" class="btn btn-danger" thotam-blockui wire:loading.attr="disabled" data-dismiss="modal">Đóng</button>
                <button wire:click.prevent="reset_password_save()" class="btn btn-success" thotam-blockui wire:loading.attr="disabled">Xác nhận</button>
            </div>

        </div>
    </div>

</div>
