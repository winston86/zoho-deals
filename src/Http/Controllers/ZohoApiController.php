<?php

namespace Winston86\ZohoDeals\Http\Controllers;

use Illuminate\Routing\Controller;
use Winston86\ZohoDeals\Interfaces\ZohoApiInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Winston86\ZohoDeals\View\Components\ZohoDeals as ZohoView;

class ZohoApiController extends Controller
{
    protected $view;

    public function __construct(ZohoView $view)
    {
        $this->view = $view;
    }

    /**
     * Fetch Accounts from Zoho CRM
     */
    public function getAccounts(ZohoApiInterface $zoho)
    {
        // For multiple records, Zoho usually expects the module name 'Accounts'
        $response = $zoho->getRecord('Accounts', '');
        return $this->view->render($tpl = 'zoho-deals::components.zoho-accounts', ['accounts' => $response['data']]);
    }

    public function searchAccounts(Request $request, ZohoApiInterface $zoho)
    {
        $query = $request->query('q');

        $response = $zoho->searchRecords('Accounts', $query);

        return $response['data'] ?? [];
    }

    public function addAccount(Request $request, ZohoApiInterface $zoho)
    {
        $validated = $request->validate([
            'Account_Name' => 'required|string',
            'Website' => [
                    'required',
                    'url',
                    'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'
                ],
            'Phone'     => [
                    'required',
                    'string',
                    'max:50',
                    'regex:/^\+?(\d[\d. -]+)?(\([\d. -]+\))?[\d. -]+\d$/'
                ],
        ]);

        $response = $zoho->createRecord('Accounts', ['data' => [$validated]]);

        $data = $response['data'][0];

        if ($data['status'] == 'success') {
            return response()->json(['status' => 'success', 'data' => $data, 'item' => $zoho->getRecord('Accounts', $data['details']['id'])]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Zoho Error: ' . ($data['message'] ?? 'Unknown Error')
        ], 400);
    }

    public function addDeal(Request $request, ZohoApiInterface $zoho)
    {

        $allowedStages = [
            'Qualification', 'Needs Analysis', 'Value Proposition',
            'Identify Decision Makers', 'Proposal/Price Quote',
            'Negotiation/Review', 'Closed Won', 'Closed Lost',
            'Closed Lost to Competition'
        ];
        $validated = $request->validate([
            'Deal_Name' => 'required|string|max:120',
            'Stage'     => [
                    'required',
                    Rule::in($allowedStages), // Checks if input is in the list
                ],
        ]);

        $response = $zoho->createRecord('Deals', ['data' => [$validated]]);

        $data = $response['data'][0];

        if ($data['status'] == 'success') {
            return response()->json(['status' => 'success', 'data' => $data, 'item' => $zoho->getRecord('Deals', $data['details']['id'])]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Zoho Error: ' . ($data['message'] ?? 'Unknown Error')
        ], 400);
    }

    public function storeDeal(Request $request, ZohoApiInterface $zoho)
    {
        $validated = $request->validate([
            'account_id' => 'required|string',
            'deal_id'    => 'required|string',
        ]);
        $payload = [
        'data' => [
            [
                        'id'           => $validated['deal_id'], // Often required for update calls
                        'Account_Name' => [
                            'id' => $validated['account_id'] // This is the mandatory object structure
                        ]
                    ]
                ]
            ];
        $response = $zoho->storeRecord('Deals', $validated['deal_id'], $payload);

        return $response;

    }

    /**
     * Fetch Deals from Zoho CRM
     */
    public function getDeals(ZohoApiInterface $zoho)
    {
        // Module name is case-sensitive in the Zoho API: 'Deals'
        $response = $zoho->getRecord('Deals', '');
        return $this->view->render($tpl = 'zoho-deals::components.zoho-deals', ['deals' => $response['data']]);
    }
}
