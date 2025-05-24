<?php

namespace Modules\Deal\Services;

use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use Modules\Task\Entities\TaskModel;

class DealService
{
    public function index()
    {
        $statuses = Status::getStatuses();
        $deals_collection = $this->collectionParser($statuses);
        $deals = [];
        foreach ($deals_collection as $deal) {
            $deals = array_merge($deals, $deal);
        }
        return  json_encode($deals, true);
    }

    private function collectionParser($collection)
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

}