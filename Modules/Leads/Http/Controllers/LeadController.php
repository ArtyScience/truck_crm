<?php

namespace Modules\Leads\Http\Controllers;

use App\Http\Requests\LeadCreateRequest;
use App\Http\Requests\LeadUpdateRequest;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\CoreController;
use Modules\Leads\Entities\Comodity;
use Modules\Leads\Entities\Lead;
use Modules\Leads\Entities\LeadAdditionalContacts;
use Modules\Leads\Entities\LeadAddress;
use Modules\Leads\Entities\LeadComodity;
use Modules\Leads\Entities\LeadCompany;
use Modules\Leads\Entities\LeadStatuses;
use Modules\Leads\Services\LeadService;

class LeadController extends CoreController
{
    protected LeadService $lead_service;

    public function __construct(LeadService $lead_service)
    {
        parent::__construct();
        $this->lead_service = $lead_service;
    }

    public function index(Request $request): JsonResponse|View
    {
        $table_rows = $this->getTableRowsNumber($request);
        return $this->lead_service->index($request, $table_rows);
    }

    public function getLeads(): JsonResponse
    {
        $leads = Lead::select('leads.id as value', 'leads.name as label')
            ->where('leads.user_id', Auth::id())
            ->get();

        return response()->json($leads);
    }

    public function getComodities(): JsonResponse
    {
        $comodities = Comodity::select('id', 'name')->get();
        return response()->json($comodities);
    }

    public function getFullAddress(int $id): JsonResponse
    {
        $address = LeadAddress::select('location_name', 'zip as postal_code')
                                ->where('lead_id', $id)->first();
        return response()->json($address);
    }

    public function getLeadComodities(int|string $lead_id): JsonResponse
    {
        $comodities = Comodity::getLeadComodities($lead_id);
        return response()->json($comodities);
    }

    public function createComodity(Request $request): JsonResponse
    {
        $comodity = Comodity::create($request->all());
        return response()->json(['id' => $comodity->id, 'name' => $comodity->name]);
    }

    public function getAdditionalContacts(int $id): JsonResponse
    {
        $query_fields = [ 'phone as additional_phone', 'email as additional_email',
                           'location as additional_location'];
        $contacts = LeadAdditionalContacts::select($query_fields)->where('lead_id', $id)->first();

        if (is_null($contacts)) {
            return response()->json(['message' => 'Additional contacts are empty'], 204);
        }

        return response()->json($contacts);
    }

    public function store(LeadCreateRequest $request): JsonResponse
    {
        return $this->lead_service->store($request);
    }

    public function update(LeadUpdateRequest $request, $id): JsonResponse
    {
        return $this->lead_service->update($request, $id);
    }

    public function destroy($id)
    {
        Lead::destroy($id);
    }

    public function importLeads(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Validate the file
            $request->validate([
                'file' => 'required|mimes:csv,txt|max:2048', // Ensure it's a CSV file
            ]);

            try {
                // Store the file temporarily
                $path = $file->store('temp');

                // Read and parse the CSV file
                $filePath = storage_path("app/{$path}");
                $data = [];

                if (($handle = fopen($filePath, 'r')) !== false) {
                    $header = fgetcsv($handle); // Read the first row as header

                    while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                        $data[] = array_combine($header, $row); // Combine header with row data
                    }

                    fclose($handle);
                }

                $lead_ids = array();
                $required_file_columns = [
                    "company", "name", "email", "phone", "notes", "location", "industry",
                    "web_page", "company_volume", "status", "status_id", "user_id"];

                foreach ($data as $one_lead) {
                    $parsed_lead = array();
                    $one_lead['status_id'] = LeadStatuses::first()->id;
                    $one_lead = array_merge($one_lead, ['user_id' => Auth::id()]);
                    foreach ($one_lead as $key => $value) {
                        $changed_key = strtolower(str_replace(['-', ' '], '_', $key));
                        $parsed_lead = array_merge($parsed_lead, [$changed_key => $value]);

                        if (!in_array($changed_key, $required_file_columns)) {
                            $error_message = 'Please fix your file columns, the column '
                                . $changed_key . ' is not allowed.';

                            return response()->json(['error' => $error_message], 422);
                        }
                    }


                    $location_array = explode('/', $parsed_lead['location']);
                    $parsed_lead['city'] = $location_array[0] ?? '';
                    $parsed_lead['state'] = $location_array[1] ?? '';

                    $response = Http::withHeaders([
                        'User-Agent' => 'TransimexCRM/1.0 (artyweb.cris@gmail.com)' // Replace with your app details
                    ])->get('https://nominatim.openstreetmap.org/search', [
                        'city' => $parsed_lead['city'],
                        'state' => $parsed_lead['state'],
                        'format' => 'json'
                    ]);

                    $responseData = json_decode($response->body(), true);

                    if (!empty($responseData)) {
                        $displayName = $responseData[0]['display_name']; // Get first result
                        $parts = explode(", ", $displayName); // Split by commas

                        $city = $parts[0] ?? 'City not found';  // First part
                        $state = $parts[count($parts) - 2] ?? 'State not found'; // Second last part
                        $country = end($parts) ?? 'Country not found'; // Last part

                        $parsed_lead['city'] = $city;
                        $parsed_lead['state'] = $state;
                        $parsed_lead['country'] = $country;
                    } else {
                        echo "No results found.";
                    }

                    $lead = Lead::create([
                        'user_id' => Auth::id(),
                        'name' => $parsed_lead['name'],
                        'email' => $parsed_lead['email'],
                        'phone' => $parsed_lead['phone'],
                        'notes' => $parsed_lead['notes'],
                        'web_page' => $parsed_lead['web_page'],
                        'company_volume' => $parsed_lead['company_volume'],
                        'status_id' => LeadStatuses::first()->id]);

                    $lead_ids[] = $lead->id;

                    LeadAddress::create([
                        'lead_id' => $lead->id,
                        'location_name' => $parsed_lead['city'] . ', ' . $parsed_lead['state'],
                        'country' => $parsed_lead['country'],
                        'city' => $parsed_lead['city'],
                        'state' => $parsed_lead['state'],
                        'zip' => '0000',
                    ]);

                    $industry = Comodity::where('name', $parsed_lead['industry'])->first();
                    if (is_null($industry)) {
                        $industry = Comodity::create([
                            'name' => $parsed_lead['industry'],
                        ]);
                    }
                    LeadComodity::create([
                        'lead_id' => $lead->id,
                        'comodity_id' => $industry->id,
                    ]);
                    $company = Company::where('name', $parsed_lead['company'])->first();
                    if (is_null($company)) {
                        $company = Company::create([
                            'name' => $parsed_lead['company'],
                        ]);
                    }
                    LeadCompany::create([
                        'lead_id' => $lead->id,
                        'company_id' => $company->id,
                    ]);
                }
            } catch (Exception $e) {
                throw $e;
            }

            $leads = $this->lead_service->getLeads($lead_ids);
            return response()->json(['message' => 'File uploaded and processed', 'leads' => $leads]);
        }
    }
}


/**
 *
 * $lead = Lead::create($parsed_leads);
 * //todo: fix location $parsed_leads['location']
 * LeadAddress::create([
 * 'lead_id' => $lead->id,
 * 'location_name' => $parsed_leads['location'],
 * 'country' => 'USA',
 * 'city' => '',
 * 'state' => '',
 * 'zip' => '0000',
 * ]);
 *
 * //create company
 * if (!Company::where('name',
 * $parsed_leads['company'])->exists()) {
 * $company = Company::create([
 * 'name' => $parsed_leads['company'],
 * 'phone' => $parsed_leads['phone'],
 * 'email' => $parsed_leads['email'],
 * 'address' => $parsed_leads['location'],
 * ]);
 * } else {
 * //get company id
 * $company = Company::where('name',
 * $parsed_leads['company'])->first();
 * }
 *
 * //                DB::table('lead_companies')->insert([
 * //                    'lead_id' => $lead->id,
 * //                    'name' => $lead->id,
 * //                ]);
 */
