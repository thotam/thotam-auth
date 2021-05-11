<div wire:ignore.self class="modal fade" id="link_modal" tabindex="-1" role="dialog" aria-labelledby="link_modal" aria-hidden="true" data-toggle="modal" data-backdrop="static" data-keyboard="false">

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

                        @if ($linkStatus)
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

                            @if (!!Auth::user()->update_hr)
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="col-form-label">Họ tên:</label>
                                        <div id="name_div">
                                            <span class="form-control px-2"  style="width: 100%">{{ Auth::user()->update_hr->hoten }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="col-form-label">Mã nhân sự:</label>
                                        <div id="name_div">
                                            <span class="form-control px-2"  style="width: 100%">{{ Auth::user()->update_hr->hr_key }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="col-form-label">Nhóm:</label>
                                        <div id="name_div">
                                            <span class="form-control px-2"  style="width: 100%">{{ Auth::user()->update_hr->nhom }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="col-form-label">Ngày sinh:</label>
                                        <div id="name_div">
                                            <span class="form-control px-2"  style="width: 100%">{{ Auth::user()->update_hr->ngaysinh->format('d-m-Y') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="col-form-label">Ngày thử việc:</label>
                                        <div id="name_div">
                                            <span class="form-control px-2"  style="width: 100%">{{ Auth::user()->update_hr->hoten->format('d-m-Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="col-form-label text-indigo" for="hr_key">Nhân viên:</label>
                                    <div class="select2-success" id="hr_key_div">
                                        <select class="form-control px-2 thotam-select2" thotam-allow-clear="true" thotam-placeholder="Liên kết với nhân viên ..." thotam-search="10" wire:model="hr_key" id="hr_key" style="width: 100%">
                                            @if (!!count($hr_info_arrays))
                                                <option selected></option>
                                                @foreach ($hr_info_arrays as $hr_info_array)
                                                    <option value="{{ $hr_info_array["key"] }}">[{{ $hr_info_array["key"] }}] {{ $hr_info_array["hoten"] }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @error('hr_key')
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
                <button wire:click.prevent="link_user_save()" class="btn btn-success" thotam-blockui wire:loading.attr="disabled">Xác nhận</button>
            </div>

        </div>
    </div>

</div>
