<?php

namespace Modules\Customer\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Customer\Models\Consent;
use Modules\Customer\Models\CustomerConsent;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

class ConsentController extends Controller
{
    /**
     * Display a listing of consents
     */
    public function index()
    {
        $module_action = 'List';
        return view('customer::backend.consents.index', compact('module_action'));
    }

    /**
     * Show the form for creating a new consent
     */
    public function create()
    {
        $module_action = 'Create';
        return view('customer::backend.consents.create', compact('module_action'));
    }

    /**
     * Store a newly created consent
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'is_active' => 'boolean',
            'is_required' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        Consent::create($request->all());

        return redirect()->route('backend.consents.index')
            ->with('flash_success', 'Consent created successfully.');
    }

    /**
     * Display the specified consent
     */
    public function show($id)
    {
        $module_action = 'Show';
        $consent = Consent::findOrFail($id);
        return view('customer::backend.consents.show', compact('module_action', 'consent'));
    }

    /**
     * Show the form for editing the specified consent
     */
    public function edit($id)
    {
        $module_action = 'Edit';
        $consent = Consent::findOrFail($id);
        return view('customer::backend.consents.edit', compact('module_action', 'consent'));
    }

    /**
     * Update the specified consent
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'is_active' => 'boolean',
            'is_required' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $consent = Consent::findOrFail($id);
        $consent->update($request->all());

        return redirect()->route('backend.consents.index')
            ->with('flash_success', 'Consent updated successfully.');
    }

    /**
     * Remove the specified consent
     */
    public function destroy($id)
    {
        $consent = Consent::findOrFail($id);
        $consent->delete();

        return redirect()->route('backend.consents.index')
            ->with('flash_success', 'Consent deleted successfully.');
    }

    /**
     * Get consents for DataTables
     */
    public function index_data(DataTables $datatable)
    {
        $query = Consent::query();

        return $datatable->eloquent($query)
            ->addColumn('action', function ($data) {
                return view('customer::backend.consents.action_column', compact('data'));
            })
            ->addColumn('status', function ($data) {
                $badge = $data->is_active ? 'success' : 'danger';
                $text = $data->is_active ? 'Active' : 'Inactive';
                return '<span class="badge badge-' . $badge . '">' . $text . '</span>';
            })
            ->addColumn('required', function ($data) {
                $badge = $data->is_required ? 'warning' : 'secondary';
                $text = $data->is_required ? 'Required' : 'Optional';
                return '<span class="badge badge-' . $badge . '">' . $text . '</span>';
            })
            ->rawColumns(['action', 'status', 'required'])
            ->toJson();
    }

    /**
     * Get customer consents for a specific customer
     */
    public function getCustomerConsents($customerId)
    {
        $customer = User::findOrFail($customerId);
        $consents = Consent::active()->ordered()->get();
        
        // Get existing customer consents
        $customerConsents = CustomerConsent::where('user_id', $customerId)
            ->with('consent')
            ->get()
            ->keyBy('consent_id');

        return response()->json([
            'success' => true,
            'consents' => $consents,
            'customer_consents' => $customerConsents,
        ]);
    }

    /**
     * Store or update customer consent
     */
    public function storeCustomerConsent(Request $request, $customerId)
    {
        $request->validate([
            'consent_id' => 'required|exists:consents,id',
            'has_consented' => 'required|boolean',
            'notes' => 'nullable|string',
        ]);

        $customer = User::findOrFail($customerId);
        $consent = Consent::findOrFail($request->consent_id);

        $customerConsent = CustomerConsent::updateOrCreate(
            [
                'user_id' => $customerId,
                'consent_id' => $request->consent_id,
            ],
            [
                'has_consented' => $request->has_consented,
                'consented_at' => $request->has_consented ? now() : null,
                'revoked_at' => !$request->has_consented ? now() : null,
                'notes' => $request->notes,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Consent updated successfully.',
            'customer_consent' => $customerConsent,
        ]);
    }

    /**
     * Delete customer consent
     */
    public function deleteCustomerConsent($customerConsentId)
    {
        $customerConsent = CustomerConsent::findOrFail($customerConsentId);
        $customerConsent->delete();

        return response()->json([
            'success' => true,
            'message' => 'Consent record deleted successfully.',
        ]);
    }


    /**
     * Save signature for customer consent
     */
    public function saveSignature(Request $request, $customerId, $consentId)
    {
        $request->validate([
            'signature' => 'required|string', // Base64 signature data
        ]);

        $customer = User::findOrFail($customerId);
        $consent = Consent::findOrFail($consentId);

        // Get or create customer consent
        $customerConsent = CustomerConsent::updateOrCreate(
            [
                'user_id' => $customerId,
                'consent_id' => $consentId,
            ],
            [
                'has_consented' => true,
                'consented_at' => now(),
                'revoked_at' => null,
            ]
        );

        // Save signature
        $signatureData = $request->signature;
        
        // Save signature as file
        $signatureFileName = 'signatures/consent_' . $consentId . '_customer_' . $customerId . '_' . now()->format('Y-m-d_H-i-s') . '.png';
        $signaturePath = 'public/' . $signatureFileName;
        
        // Decode base64 and save file
        $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
        $signatureData = str_replace(' ', '+', $signatureData);
        $signatureImage = base64_decode($signatureData);
        
        Storage::put($signaturePath, $signatureImage);

        // Update customer consent with signature
        $customerConsent->addSignature($request->signature, $signatureFileName);

        return response()->json([
            'success' => true,
            'message' => 'Signature saved successfully.',
            'customer_consent' => $customerConsent,
        ]);
    }
}
