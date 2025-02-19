<?php

namespace Modules\Leads\Services;

use App\Http\Requests\LeadCreateRequest;
use App\Http\Requests\LeadUpdateRequest;
use App\Models\Company;
use App\Models\Status;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\Leads\Entities\Comodity;
use Modules\Leads\Entities\Lead;
use Modules\Leads\Entities\LeadAdditionalContacts;
use Modules\Leads\Entities\LeadAddress;
use Modules\Leads\Entities\LeadComodity;
use Modules\Leads\Entities\LeadCompany;
use Modules\Leads\Entities\LeadStatuses;
use Modules\Task\Entities\TaskModel;

class LeadService
{
    public function index(Request $request, int $table_rows): JsonResponse|View
    {
        $data['comodities'] = response()->json(Comodity::all())->content();
        $leads = $this->collectionParser(Lead::getLeads($table_rows));
        $data['leads'] = response()->json($leads)->content();

        if ($request->get('page')) return response()->json($leads);

        $statuses = Status::getLeadsStatuses();
        $deals_collection = $this->dealsCollectionParser($statuses);
        $data['deals'] = [];
        foreach ($deals_collection as $deal) {
            $data['deals'] = array_merge($data['deals'], $deal);
        }
        $data['deals'] = json_encode($data['deals']);

        return view('leads::index', compact('data'));
    }

    private function dealsCollectionParser($collection)
    {

        $collection->transform(function ($status) {
            $merged_data = array();

            if (!is_null($status->deals_data)) {
                $status->deals_data = $this->parseDeals($status->deals_data);
                $status->deals_details_data = $this->parseDealsDetails($status->deals_details_data);
                $status->locations = $this->parseDealLocations($status->locations);
                $status->leads_data = $this->parseDealLeads($status->leads_data);
                $deal_counter = 0;

                $merged_data = array_map(function ($item)
                use (&$deal_counter, $status) {
                    $result = array_merge(
                        $item, $status->deals_details_data[$deal_counter] ?? [],
                        $status->locations[$deal_counter],
                        $status->leads_data[$deal_counter],
                    ); $deal_counter++;
                    return $result;
                }, $status->deals_data);
            }

            $parsed_merged_data = [];
            foreach ($merged_data as $key => $item) {
                $from = reset($item['from']);
                $to = reset($item['to']);
                $title = sprintf(
                    '%s %s - %s %s',
                    $from['city'], $from['state'],
                    $to['city'], $to['state'],
                );

                $parsed_merged_data[$status['status_name']][$key] = [
                    'id' => $item['id'],
                    'title' => $title,
                    'income' => $item['income'],
                    'created_at' => $item['created_at'],
                    'updated_at' => $item['updated_at'],
                    'tasks' => TaskModel::where('deal_id', $item['id'])
                        ->where('user_id', Auth::id())->count(),
                    'pick_up_date' => $item['pick_up_date'] ?? '',
                    'lead_name' => $item['lead_name'],
                    'lead_phone' => $item['lead_phone'],
                    'equipment' =>  isset($item['equipment_type']) ? strtoupper($item['equipment_type']) : '',
                    'shipment' => isset($item['shipment_type']) ? strtoupper($item['shipment_type']) : '',
                ];
            }


            if (empty($merged_data)) {
                $parsed_merged_data[$status['status_name']] = [];
                return $parsed_merged_data;
            }

            /*TODO: Refactor to ORDER by POSITION*/

            $array_to_reorder = $parsed_merged_data[$status['status_name']];
            usort($array_to_reorder, function ($a, $b) {
                return strtotime($b['created_at']) < strtotime($a['created_at']);
            });
            $parsed_merged_data[$status['status_name']] = $array_to_reorder;
            return $parsed_merged_data;
        });

        return $collection;
    }


    private function parseDealsDetails(string $deals_details): array
    {
        $columns_keys = ['equipment_type', 'shipment_type', 'pick_up_date'];
        return $this->dealQueryParser($deals_details, $columns_keys);
    }

    private function parseDeals(string $deals): array
    {
        $columns_keys = ['id', 'income', 'created_at', 'updated_at'];
        return $this->dealQueryParser($deals, $columns_keys);
    }

    private function parseTasks(string $deal_tasks): array
    {
        return $this->dealQueryParser($deal_tasks, ['tasks']);
    }
    private function parseDealLeads(string $deal_leads): array
    {
        $columns_keys = ['name', 'phone'];
        $response =  $this->dealQueryParser($deal_leads, $columns_keys);

        return array_map(function ($item) {
            return [
                'lead_name' => $item['name'],
                'lead_phone' => $item['phone']
            ];
        }, $response);
    }

    private function parseDealLocations(string $deal_locations): array
    {
        $columns_keys = ['city', 'state', 'type', 'deal_id'];
        $parsed_response = $this->dealQueryParser($deal_locations, $columns_keys);

        $deal_to_from = [];
        foreach ($parsed_response as $location) {
            $dealId = $location['deal_id'];
            if (!isset($deal_to_from[$dealId])) {
                $deal_to_from[$dealId] = [
                    'id' => $dealId,
                    'to' => [],
                    'from' => []
                ];
            }
            $deal_to_from[$dealId][$location['type']][] = [
                'city' => $location['city'],
                'state' => $location['state']
            ];
        }
        return array_values($deal_to_from);
    }

    private function dealQueryParser(string $deals, array $columns_keys): array
    {
        $deals_array = explode("||", $deals);
        foreach ($deals_array as $key => $deal) {
            $deal_clean_string = ltrim($deal, ',');
            $deal_components_array = explode('&&', $deal_clean_string);

            foreach ($deal_components_array as $columnd_id => $column_value) {
                if (count($deal_components_array) === count($columns_keys))
                    $deals_parsed[$key][$columns_keys[$columnd_id]] = trim($column_value);
            }
        }
        return $deals_parsed;
    }


    public function getLeads(array $leads_ids)
    {
        $leads = Lead::getLeadsByID($leads_ids);
        return $this->collectionParser($leads);
    }

    public function store(LeadCreateRequest $request): JsonResponse
    {
        $data = $request->only('name', 'company', 'email', 'phone', 'notes',
                                     'web_page', 'company_volume');

        $data['user_id'] = Auth::id();

        $created_lead = Lead::create($data);
        $comodities = $request->get('comodities_list');
        if (!empty($comodities)) {
            foreach ($comodities as $comodity) {
                LeadComodity::create([
                    'lead_id' => $created_lead->id,
                    'comodity_id' => $comodity['id'],
                ]);
            }
        }

        $parsed_location = $this->locationParser($request->get('full_location'));
        $parsed_location['lead_id'] = $created_lead->id;

        LeadAddress::create($parsed_location);

        $check_company = Company::where('name', $request->get('company'))->first();

        if (!is_null($check_company)) {
            LeadCompany::create(['lead_id' => $created_lead->id, 'company_id' => $check_company->id]);
        } else {
            $created_company = Company::create([
                'name' => $request->get('company'),
                'phone' => $request->get('phone'),
                'email' => $request->get('email'),
                'address' => $request->get('full_location')['formatted_address'],
            ]);
            LeadCompany::create(['lead_id' => $created_lead->id, 'company_id' => $created_company->id]);
        }

        $lead = Lead::getLeads(1, $created_lead->id);

        $additional_contacts = $request->only('additional_location', 'additional_email', 'additional_phone');
        $this->saveAdditionalContacts($created_lead->id, $additional_contacts);

        return response()->json($this->collectionParser($lead)->first());
    }

    private function saveAdditionalContacts(int $lead_id, array $contacts): void
    {
        $contacts_for_saving = array();
        if (!empty($contacts)) {
            foreach ($contacts as $key => $additional_contact) {
                if (!is_null($additional_contact) && !empty($additional_contact)) {
                    $db_column = str_replace('additional_', '', $key);
                    $contacts_for_saving[$db_column] = $additional_contact;
                }
            }
            LeadAdditionalContacts::where('lead_id', $lead_id)->delete();
            if (!empty($contacts_for_saving)) {
                $contacts_for_saving['lead_id'] = $lead_id;
                LeadAdditionalContacts::create($contacts_for_saving);
            }
        }
    }

    public function update(LeadUpdateRequest $request, $id): JsonResponse
    {
        $lead = $request->only('name', 'email', 'phone', 'notes', 'web_page', 'company_volume');

        Lead::where('id', $id)->update($lead);
        LeadComodity::where('lead_id', $id)->delete();

        LeadCompany::where('lead_id', $id)->update([
            'name' => $request->get('company')
        ]);

        $comodities = $request->get('comodities_list');
        foreach ($comodities as $comodity) {
            LeadComodity::create(['lead_id' => $id, 'comodity_id' => $comodity['id']]);
        }

        if (!is_null($request->get('full_location'))) {
            $parsed_location = $this->locationParser($request->get('full_location'));
            $parsed_location['lead_id'] = $id;
            LeadAddress::where('lead_id', $id)->delete();
            LeadAddress::create($parsed_location);
        }

        $additional_contacts = $request->only('additional_location', 'additional_email', 'additional_phone');
        $this->saveAdditionalContacts($id, $additional_contacts);

        return response()->json(['success' => true, 'message' => 'Lead updated successfully.']);
    }

    private function collectionParser(LengthAwarePaginator $collection): LengthAwarePaginator
    {
        $collection->getCollection()->transform(function ($lead) {
            $lead->comodities_list = $this->parseToArray($lead->comodities_list);
            $lead->locations_list = $this->parseToArray($lead->locations_list);
            $lead->notes_full = $lead->notes;
            $lead->notes = substr($lead->notes, 0, 50) . '...';
            $lead->created_at_formated = $lead->created_at->format('F d, H:i');
            $lead->status_id =
                LeadStatuses::where('id', $lead->status_id)->first()->name ??
                LeadStatuses::first()->name;
            return $lead;
        });

        return $collection;
    }

    private function locationParser(array $location): array
    {
        $address_components = $location['address_components'];
        $address_allowed_parts = ['street_number', 'postal_code', 'natural_feature',
            'locality', 'country', 'administrative_area_level_1', 'route'];
        $parsed_address = [];

        foreach ($address_components as $address_component) {
            $address_type = reset($address_component['types']);
            if (in_array($address_type, $address_allowed_parts)) {
                $parsed_address[$address_type] = $address_component['long_name'];
            }
        }

        $city = '';
        if (isset($parsed_address['locality'])) {
            $city = $parsed_address['locality'];
        } elseif (isset($parsed_address['natural_feature'])) {
            $city = $parsed_address['natural_feature'];
        }

        return [
            'location_name' => $location['formatted_address'],
            'country' => $parsed_address['country'] ?? '',
            'city' => $city,
            'state' => $parsed_address['administrative_area_level_1'],
            'zip' => $parsed_address['postal_code'] ?? '',
        ];
    }

    private function parseToArray(string|null $string): array
    {
        if (is_null($string)) return array();

        return array_filter(explode('||', $string));
    }
}
