<?php

namespace Thotam\ThotamAuth\Http\Livewire;

use Auth;
use App\Models\User;
use Livewire\Component;
use Thotam\ThotamHr\Models\HR;
use Illuminate\Support\Facades\Hash;
use App\Actions\Fortify\PasswordValidationRules;

class AuthLivewire extends Component
{
    use PasswordValidationRules;

    /**
    * Các biến sử dụng trong Component
    *
    * @var mixed
    */
    public $name, $email, $phone, $active, $password, $password_confirmation, $hr_key;
    public $modal_title, $toastr_message;
    public $hr;
    public $user_id, $user;
    public $hr_info_arrays;

    /**
     * @var bool
     */
    public $addStatus = false;
    public $viewStatus = false;
    public $editStatus = false;
    public $linkStatus = false;
    public $resetStatus = false;

    /**
     * Các biển sự kiện
     *
     * @var array
     */
    protected $listeners = ['dynamic_update_method', 'edit_user', 'link_user', 'reset_password', ];

    /**
     * dynamic_update_method
     *
     * @return void
     */
    public function dynamic_update_method()
    {
        $this->dispatchBrowserEvent('dynamic_update');
    }

    /**
     * On updated action
     *
     * @param  mixed $propertyName
     * @return void
     */
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    /**
     * Validation rules
     *
     * @var array
     */
    protected function rules() {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$this->user_id,
            'phone' => [
                'required',
                'regex:/^(032|033|034|035|036|037|038|039|086|096|097|098|081|082|083|084|085|088|091|094|056|058|092|070|076|077|078|079|089|090|093|099|059|087)+([0-9]{7})$/',
                'numeric',
                'unique:users,phone,'.$this->user_id,
            ],
            'active' => 'nullable|boolean',
            'hr_key' => 'nullable|exists:hrs,key',
            'password' => $this->passwordRules(),
        ];
    }

    /**
     * Custom attributes
     *
     * @var array
     */
    protected $validationAttributes = [
        'name' => 'họ tên',
        'email' => 'email',
        'phone' => 'số điện thoại',
        'hr_key' => 'mã nhân sự',
        'password' => 'mật khẩu',
    ];

    public function updatedName()
    {
        $this->name = mb_convert_case(trim($this->name), MB_CASE_TITLE, "UTF-8");
    }

    public function updatedPasswordConfirmation()
    {
        $this->validate([
            'password' => $this->passwordRules(),
        ]);
    }

    /**
     * cancel
     *
     * @return void
     */
    public function cancel()
    {
        $this->dispatchBrowserEvent('unblockUI');
        $this->dispatchBrowserEvent('hide_modals');
        $this->reset();
        $this->addStatus = false;
        $this->editStatus = false;
        $this->viewStatus = false;
        $this->linkStatus = false;
        $this->resetStatus = false;
        $this->resetValidation();
        $this->mount();
    }

    /**
     * mount data
     *
     * @return void
     */
    public function mount()
    {
        $this->hr = Auth::user()->hr;
    }

    /**
     * render
     *
     * @return void
     */
    public function render()
    {
        return view('thotam-auth::livewire.auth.auth-livewire');
    }

    /**
     * edit_user
     *
     * @param  mixed $user
     * @return void
     */
    public function edit_user(User $user)
    {
        if ($this->hr->cannot("edit-user")) {
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Bạn không có quyền thực hiện hành động này"]);
            $this->cancel();
            return null;
        }

        $this->user = $user;
        $this->user_id = $this->user->id;
        if (!!$this->user->update_hr) {
            $this->name = $this->user->update_hr->hoten;
        } else {
            $this->name = $this->user->name;
        }
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
        $this->active = !!$this->user->active;

        $this->editStatus = true;
        $this->modal_title = "Chỉnh sửa Tài khoản - ID: ".$this->user_id;
        $this->toastr_message = "Chỉnh sửa Tài khoản thành công";

        $this->dispatchBrowserEvent('unblockUI');
        $this->dispatchBrowserEvent('dynamic_update');
        $this->dispatchBrowserEvent('show_modal', "#add_edit_modal");
    }

    /**
     * edit_user
     *
     * @return void
     */
    public function edit_user_save()
    {
        if (!$this->hr->canAny(["add-user", "edit-user"])) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Bạn không có quyền thực hiện hành động này"]);
            return null;
        }

        //Xác thực dữ liệu
        $this->dispatchBrowserEvent('unblockUI');
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$this->user_id,
            'phone' => [
                'required',
                'regex:/^(032|033|034|035|036|037|038|039|086|096|097|098|081|082|083|084|085|088|091|094|056|058|092|070|076|077|078|079|089|090|093|099|059|087)+([0-9]{7})$/',
                'numeric',
                'unique:users,phone,'.$this->user_id,
            ],
            'active' => 'nullable|boolean',
        ]);
        $this->dispatchBrowserEvent('blockUI');

        try {
            User::updateOrCreate([
                "id" => $this->user_id,
            ], [
                'name' => mb_convert_case(trim($this->name), MB_CASE_TITLE, "UTF-8"),
                "email" => $this->email,
                "phone" => $this->phone,
                "active" => !!$this->active
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => implode(" - ", $e->errorInfo)]);
            return null;
        } catch (\Exception $e2) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => $e2->getMessage()]);
            return null;
        }

        //Đẩy thông tin về trình duyệt
        $this->dispatchBrowserEvent('dt_draw');
        $toastr_message = $this->toastr_message;
        $this->cancel();
        $this->dispatchBrowserEvent('toastr', ['type' => 'success', 'title' => "Thành công", 'message' => $toastr_message]);
    }

    /**
     * link_user
     *
     * @param  mixed $user
     * @return void
     */
    public function link_user(User $user)
    {
        if ($this->hr->cannot("link-user")) {
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Bạn không có quyền thực hiện hành động này"]);
            $this->cancel();
            return null;
        }

        $this->user = $user;
        $this->user_id = $this->user->id;
        if (!!$this->user->update_hr) {
            $this->name = $this->user->update_hr->hoten;
        } else {
            $this->name = $this->user->name;
        }
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
        $this->hr_key = $this->user->hr_key;

        $this->hr_info_arrays = HR::select("key", "hoten")->get()->toArray();;

        $this->linkStatus = true;
        $this->modal_title = "Liên kết Tài khoản - ID: ".$this->user_id;
        $this->toastr_message = "Liên kết Tài khoản thành công";

        $this->dispatchBrowserEvent('unblockUI');
        $this->dispatchBrowserEvent('dynamic_update');
        $this->dispatchBrowserEvent('show_modal', "#link_modal");
    }

    /**
     * link_user_save
     *
     * @return void
     */
    public function link_user_save()
    {
        if ($this->hr->cannot("link-user")) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Bạn không có quyền thực hiện hành động này"]);
            return null;
        }

        //Xác thực dữ liệu
        $this->dispatchBrowserEvent('unblockUI');
        $this->validate([
            'hr_key' => 'nullable|exists:hrs,key',
        ]);
        $this->dispatchBrowserEvent('blockUI');

        try {
            if (!!$this->hr_key) {
                $this->user->update([
                    "hr_key" => $this->hr_key
                ]);
            } else {
                $this->user->hr()->dissociate()->save();
            }

        } catch (\Illuminate\Database\QueryException $e) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => implode(" - ", $e->errorInfo)]);
            return null;
        } catch (\Exception $e2) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => $e2->getMessage()]);
            return null;
        }

        //Đẩy thông tin về trình duyệt
        $this->dispatchBrowserEvent('dt_draw');
        $toastr_message = $this->toastr_message;
        $this->cancel();
        $this->dispatchBrowserEvent('toastr', ['type' => 'success', 'title' => "Thành công", 'message' => $toastr_message]);
    }

    /**
     * reset_password
     *
     * @param  mixed $user
     * @return void
     */
    public function reset_password(User $user)
    {
        if ($this->hr->cannot("reset-password-user")) {
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Bạn không có quyền thực hiện hành động này"]);
            $this->cancel();
            return null;
        }

        $this->user = $user;
        $this->user_id = $this->user->id;
        if (!!$this->user->update_hr) {
            $this->name = $this->user->update_hr->hoten;
        } else {
            $this->name = $this->user->name;
        }
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;

        $this->resetStatus = true;
        $this->modal_title = "Reset mật khẩu - ID: ".$this->user_id;
        $this->toastr_message = "Reset mật khẩu thành công";

        $this->dispatchBrowserEvent('unblockUI');
        $this->dispatchBrowserEvent('dynamic_update');
        $this->dispatchBrowserEvent('show_modal', "#reset_password_modal");
    }

    /**
     * reset_password_save
     *
     * @return void
     */
    public function reset_password_save()
    {
        if ($this->hr->cannot("reset-password-user")) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Bạn không có quyền thực hiện hành động này"]);
            return null;
        }

        //Xác thực dữ liệu
        $this->dispatchBrowserEvent('unblockUI');
        $this->validate([
            'password' => $this->passwordRules(),
        ]);
        $this->dispatchBrowserEvent('blockUI');

        try {
            $this->user->forceFill(['password' => Hash::make($this->password),])->save();
        } catch (\Illuminate\Database\QueryException $e) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => implode(" - ", $e->errorInfo)]);
            return null;
        } catch (\Exception $e2) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => $e2->getMessage()]);
            return null;
        }

        //Đẩy thông tin về trình duyệt
        $this->dispatchBrowserEvent('dt_draw');
        $toastr_message = $this->toastr_message;
        $this->cancel();
        $this->dispatchBrowserEvent('toastr', ['type' => 'success', 'title' => "Thành công", 'message' => $toastr_message]);
    }
}
