<?php

namespace App;

use Illuminate\Support\Facades\DB;

class DatabaseTroubleshooter
{
    public function checkPricesAndFormData(): array
    {
        $issues = [];
        $emptyForm = [];
        $emptylocationPrices = [];
        $emptyPlanPrices = [];
        $services = DB::table('services')->join('service_groups', 'services.service_group_id', '=', 'service_groups.id')->select('services.id', 'services.name', 'services.form', 'services.appointment_price', 'service_groups.name as service_group_name')->get();
        $locations = DB::table('locations')->select('id', 'district', 'state')->get();
        $plans = DB::table('plans')->select('id', 'name')->get();

        foreach ($services as $service) {
            if ($service->form == '[]' || $service->form == null) {
                $emptyForm[] = $service;
            }
            foreach ($locations as $location) {
                $locationPrice = DB::table('prices')->select('default_govt_price', 'default_commission_price', 'default_tax_percentage', 'tatkal_govt_price', 'tatkal_commission_price', 'tatkal_tax_percentage')->where('service_id', $service->id)->where('location_id', $location->id)->where('plan_id', null)->first();
                if (
                    !$locationPrice ||
                    is_null($locationPrice->default_govt_price) ||
                    is_null($locationPrice->default_commission_price) ||
                    is_null($locationPrice->default_tax_percentage) ||
                    is_null($locationPrice->tatkal_govt_price) ||
                    is_null($locationPrice->tatkal_commission_price) ||
                    is_null($locationPrice->tatkal_tax_percentage)
                ) {
                    $data = [];
                    $data['service_name'] = $service->name;
                    $data['service_group_name'] = $service->service_group_name;
                    $data['district'] = $location->district;
                    $data['state'] = $location->state;
                    $emptylocationPrices[] = $data;
                }
                foreach ($plans as $plan) {
                    $planPrice = DB::table('prices')->select('subscribed_default_govt_price', 'subscribed_default_commission_price', 'subscribed_default_tax_percentage', 'subscribed_tatkal_govt_price', 'subscribed_tatkal_commission_price', 'subscribed_tatkal_tax_percentage')->where('service_id', $service->id)->where('location_id', $location->id)->where('plan_id', $plan->id)->first();
                    $planPrice = DB::table('prices')
                        ->select(
                            'subscribed_default_govt_price',
                            'subscribed_default_commission_price',
                            'subscribed_default_tax_percentage',
                            'subscribed_tatkal_govt_price',
                            'subscribed_tatkal_commission_price',
                            'subscribed_tatkal_tax_percentage'
                        )
                        ->where('service_id', $service->id)
                        ->where('location_id', $location->id)
                        ->where('plan_id', $plan->id)
                        ->first();

                    if (
                        empty($planPrice) ||
                        is_null($planPrice->subscribed_default_govt_price) ||
                        is_null($planPrice->subscribed_default_commission_price) ||
                        is_null($planPrice->subscribed_default_tax_percentage) ||
                        is_null($planPrice->subscribed_tatkal_govt_price) ||
                        is_null($planPrice->subscribed_tatkal_commission_price) ||
                        is_null($planPrice->subscribed_tatkal_tax_percentage)
                    ) {
                        $data = [];
                        $data['service_name'] = $service->name;
                        $data['service_group_name'] = $service->service_group_name;
                        $data['district'] = $location->district;
                        $data['state'] = $location->state;
                        $data['plan'] = $plan->name;
                        $emptyPlanPrices[] = $data;
                    }
                }
            }
        }
        $issues['withoutFormData'] = $emptyForm;
        $issues['withoutLocationPrice'] = $emptylocationPrices;
        $issues['withoutPlanPrice'] = $emptyPlanPrices;
        return $issues;
    }
}
