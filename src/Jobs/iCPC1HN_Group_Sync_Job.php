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

class iCPC1HN_Group_Sync_Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $admin_key = config('thotam-icpc1hn-api.adminHrKey');
        $token = HR::find($admin_key)->icpc1hn_token;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post(config('thotam-icpc1hn-api.kpi.order.getServiceOffice'), [
            "token" => $token
        ]);

        if ($response->status() == 200) {
            $json_array = $response->json();
            if ($json_array["ResCode"] == 0) {
                if (!!$json_array["Data"]) {
                    foreach ($json_array["Data"] as $data) {
                        if ((bool)collect($data)->get("IDGroup")) {
                            iCPC1HN_Group::updateOrCreate(
                                ['icpc1hn_group_id' => collect($data)->get("IDGroup")],
                                [
                                    'active' => true,
                                    'deleted_at' => NULL,
                                ]
                            );
                        }
                    }
                }
            } else {
                Log::error(get_class($this) . ': ' . $json_array["ResCode"] . " " . $json_array["ResMes"]);
                throw new Exception(get_class($this) . ': ' . $json_array["ResCode"] . " " . $json_array["ResMes"]);
            }
        } else {
            Log::error(get_class($this) . ': Unexpected HTTP status: ' .  ' - ' . $response->status() . ' - ' . $response->getReasonPhrase());
            throw new Exception(get_class($this) . ': Unexpected HTTP status: ' .  ' - ' . $response->status() . ' - ' . $response->getReasonPhrase());
        }
    }
}
