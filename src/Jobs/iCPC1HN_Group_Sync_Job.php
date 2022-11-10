<?php

namespace Thotam\ThotamAuth\Jobs;

use Exception;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Thotam\ThotamHr\Models\HR;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Thotam\ThotamAuth\Models\iCPC1HN_Group;
use Thotam\ThotamUpharma\Traits\JobTrait;

class iCPC1HN_Group_Sync_Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use JobTrait;

    public $UserName, $Password, $Token, $uPharmaID;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->UserName = config('thotam-upharma.API.User.UserName');
        $this->Password = config('thotam-upharma.API.User.Password');
        $__getAccount = $this->__getAccount();
        $this->Token = $__getAccount['Token'];
        $this->uPharmaID = $__getAccount['UserInfo']['uPharmaID'];

        $__getOrganizationLsts = $this->__getOrganizationLst(null)['OrganizationLst'];

        foreach ($__getOrganizationLsts as $data) {
            if ((bool)collect($data)->get("OrganizationID")) {
                iCPC1HN_Group::updateOrCreate(
                    ['icpc1hn_group_id' => collect($data)->get("ShopCode")],
                    [
                        'active' => true,
                        'deleted_at' => NULL,
                    ]
                );
            }
        }
    }
}
