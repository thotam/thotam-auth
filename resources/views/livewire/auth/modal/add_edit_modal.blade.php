<div wire:ignore.self class="modal fade" id="add_edit_modal" tabindex="-1" role="dialog" aria-labelledby="add_edit_modal" aria-hidden="true" data-toggle="modal" data-backdrop="static" data-keyboard="false">

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

                        @if ($editStatus || $addStatus)
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="col-form-label" for="name">Họ tên:</label>
                                    <div id="name_div">
                                        <input type="text" class="form-control px-2" wire:model.lazy="name" id="name" style="width: 100%" placeholder="Họ tên ..." autocomplete="off">
                                    </div>
                                    @error('name')
                                        <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="col-form-label" for="email">Email:</label>
                                    <div id="email_div">
                                        <input type="email" class="form-control px-2" wire:model.lazy="email" id="email" style="width: 100%" placeholder="Email ..." autocomplete="off">
                                    </div>
                                    @error('email')
                                        <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="col-form-label" for="phone">Số điện thoại:</label>
                                    <div id="phone_div">
                                        <input type="text" class="form-control px-2" wire:model.lazy="phone" id="phone" style="width: 100%" placeholder="Số điện thoại ..." autocomplete="off">
                                    </div>
                                    @error('phone')
                                        <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="input-group form-group border-bottom thotam-border py-2">
                                    <div class="input-group-prepend mr-4">
                                        <label class="col-form-label col-6 text-left pt-0 input-group-text border-0" for="active">Kích hoạt tài khoản:</label>
                                    </div>
                                    <label class="switcher switcher-square">
                                        <input type="checkbox" class="switcher-input form-control" wire:model="active" id="active" style="width: 100%">
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes"></span>
                                            <span class="switcher-no"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            <div class="modal-footer mx-auto">
                <button wire:click.prevent="cancel()" class="btn btn-danger" thotam-blockui wire:loading.attr="disabled" data-dismiss="modal">Đóng</button>
                <button wire:click.prevent="edit_user_save()" class="btn btn-success" thotam-blockui wire:loading.attr="disabled">Xác nhận</button>
            </div>

        </div>
    </div>

</div>
