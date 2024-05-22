<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Customer extends Model
{

    public static function get_all_applications_data($customer_id)
    {
        $query = DB::table('applications')
            ->where('applications.customer_id', $customer_id)
            ->join('services', 'applications.service_id', '=', 'services.id')
            ->join('agents', 'applications.agent_id', '=', 'agents.id')
            ->select(
                'applications.apply_date',
                'applications.delivery_date',
                'applications.status',
                'applications.reason',
                'applications.is_doc_approved',
                'applications.delivery as delivery_doc',
                'applications.receipt',
                'services.name as service_name',
                'agents.full_name as agent_name',
                DB::raw('(SELECT GROUP_CONCAT(CONCAT(id, ":", status_name, ":" , color , ":" , ask_reason)) FROM service_statuses WHERE service_statuses.service_id = applications.service_id) as statuses')
            )
            ->orderBy("applications.id", "desc");
        $result = $query->get();
        $structured_data = [];
        foreach ($result as $application) {
            $data = [];
            $data['agentName'] =  $application->agent_name;
            $data['service'] =  $application->service_name;
            $data['applyDate'] =  $application->apply_date;
            $data['deliveryDate'] = ($application->delivery_date) ? $application->delivery_date : 'Not yet determined';
            if ($application->status == -1) {
                $data['status']['name'] = 'Rejected';
                $data['status']['color'] = 'green';
                $data['status']['reason'] = $application->reason;
            } elseif ($application->status == 2) {
                $data['status']['name'] = 'Rejected';
                $data['status']['color'] = 'red';
            } elseif ($application->status == 0) {
                $data['status']['name'] = 'Initiated';
                $data['status']['color'] = 'cyan';
            } elseif ($application->status == 1) {
                $data['status']['name'] = 'In Progress';
                $data['status']['color'] = 'yellow';
            } else {
                $statusesArray = explode(',', $application->statuses);
                foreach ($statusesArray as $status) {
                    [$id, $statusName, $statusColor, $askReason] = explode(':', $status);
                    if ($application->status == $id) {
                        $data['status']['name'] = $statusName;
                        $data['status']['color'] = $statusColor;
                        if ($askReason) {
                            $data['status']['reason'] = $application->reason;
                        }
                        break;
                    }
                }
            }
            $data['reciept'] = $application->receipt ? $application->receipt : "Available Soon";
            if ($application->delivery_doc) {
                $data['deliveryDoc'] = ($application->is_doc_approved) ? $application->delivery_doc : 'Ask Agent to Unlock';
            } else {
                $data['deliveryDoc'] = null;
            }
            $structured_data[] = $data;
        }
        return $structured_data;
    }

    public static function update_password(int $customer_id , array $input_data){
        $customer = DB::table('customers')->where('id',$customer_id)->first();
        if( $input_data['current_password'] === $customer->mobile || $input_data['current_password'] === $customer->password){
            DB::table('customers')->update(['password'=>$input_data['new_password']]);
            return [
                'success' => true,
                'message' => "Password Updated Successfully"
            ];
        }
        return [
            'success' => false,
            'message' => 'Incorrect Password'
        ];

    }
}
