<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Helpers\Constants;

use App\Models\Staff;
use function Laravel\Prompts\select;

class Customer extends Model
{

    public static function get_all_applications_data($customer_id)
    {
        $query = DB::table('applications')
            ->where('applications.customer_id', $customer_id)
            ->join('services', 'applications.service_id', '=', 'services.id')
            ->join('agents', 'applications.agent_id', '=', 'agents.id')
            ->where('applications.is_approved', '=', 1)
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
                'agents.shop_name as shop_name',
                DB::raw('(SELECT GROUP_CONCAT(CONCAT(id, ":", status_name, ":" , color , ":" , ask_reason)) FROM service_statuses WHERE service_statuses.service_id = applications.service_id) as statuses')
            )
            ->orderBy("applications.id", "desc");
        $result = $query->get();
        $structured_data = [];
        foreach ($result as $application) {
            $data = [];
            $data['agentName'] =  $application->agent_name;
            $data['shopName'] =  $application->shop_name;
            $data['service'] =  $application->service_name;
            $data['applyDate'] =  $application->apply_date;
            $data['deliveryDate'] = ($application->delivery_date) ? $application->delivery_date : 'Not yet determined';
            if ($application->status == -1) {
                $data['status']['name'] = 'Rejected';
                $data['status']['color'] = 'red';
                $data['status']['reason'] = $application->reason;
            } elseif ($application->status == 2) {
                $data['status']['name'] = 'Completed';
                $data['status']['color'] = 'green';
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

    public static function update_password(int $customer_id, array $input_data)
    {
        $customer = DB::table('customers')->where('id', $customer_id)->first();
        if ($input_data['current_password'] === $customer->mobile || $input_data['current_password'] === $customer->password) {
            DB::table('customers')->update(['password' => $input_data['new_password']]);
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

    public static function get_all_services_data()
    {
        $services = DB::table("services")
            ->join("service_groups", "services.service_group_id", "=", "service_groups.id")
            ->where('service_groups.id', '<>', Constants::MAHA_ESEVA_KENDRA_SERVICE_GROUP_ID)
            ->select("services.id", "services.name", "service_groups.name as groupName", "service_groups.photo as groupPhoto")
            ->get()
            ->groupBy('groupName');


        // Format the services data for the view
        $service_groups = [];
        foreach ($services as  $grouped_services) {
            $service_groups[] = [
                $grouped_services,
            ];
        }

        return [
            'success' => true,
            "data" => $service_groups
        ];
    }

    public static function get_form_data_by_service_id($service_id, $customer_id)
    {
        $service = DB::table('services')->where('id', '=', $service_id)->first();
        $agents = self::get_agents_by_customer_id($customer_id);
        if ($service) {
            $data = [
                "serviceId" => $service_id,
                "serviceName" => $service->name,
                "documentRequirements" => explode(',', $service->requirements),
                "form" => json_decode($service->form),
                'agent' => $agents,
            ];
            return [
                "success" => true,
                "data" => $data
            ];
        } else {
            return [
                "success" => false,
                "message" => 'Invalid Service Id'
            ];
        }
    }

    private static function get_agents_by_customer_id($customer_id)
    {
        $agents = DB::table('applications')->where('customer_id', '=', $customer_id)->join('agents', 'applications.agent_id', '=', 'agents.id')->select('applications.agent_id as id', 'agents.full_name as name')->distinct()->get();
        return $agents;
    }

    public static function store_application_data($request, $customer_id)
    {
        $service_id = $request->input('service_id');
        $agent_id = $request->input('agent_id');
        $location_id = DB::table('agents')->where('id', $agent_id)->value('location_id');
        $service = DB::table('services')->where('id', $service_id)->select('requirements', 'service_group_id')->first();
        $nextStaffId = Staff::get_staff_id($service->service_group_id, $location_id);
        $requirements = [];
        $requirements_from_service = explode(',', $service->requirements);
        foreach ($requirements_from_service as $doc) {
            array_push($requirements, trim($doc));
        }
        array_push($requirements, '_token');
        array_push($requirements, 'file');
        array_push($requirements, 'agent_id');
        array_push($requirements, 'service_id');
        $form_data = $request->except($requirements);
        $filePaths = [];
        foreach ($request->allFiles() as $fieldName => $files) {
            if (!is_array($files)) {
                $files = [$files];
            }

            foreach ($files as $file) {
                $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/applications'), $fileName);
                $filePaths[$fieldName] = 'uploads/applications/' . $fileName;
            }
        }
        $formDataJson = json_encode([
            'formData' => $form_data,
            'filePaths' => $filePaths
        ]);
        $application = DB::table('applications')->insertGetId([
            'service_id' => $service_id,
            'agent_id' => $agent_id,
            'customer_id' => $customer_id,
            'location_id' => $location_id,
            'service_group_id' => $service->service_group_id,
            'staff_id' => $nextStaffId,
            'is_applicant_customer' => true,
            'is_approved' => false,
            'apply_date' => today(),
            'form_data' => $formDataJson,
        ]);
        return [
            'success' => true,
            'message' => 'Application Successfull'
        ];
    }
}
