<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ConsultationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.consultations.index');
    }

    /**
     * Get consultations data for DataTable
     *
     * @return \Illuminate\Http\Response
     */
    public function index_data(Datatables $datatable, Request $request)
    {
        $query = Consultation::with('patient')->select('consultations.*');

        // Filter by patient if provided
        if ($request->has('patient_id') && $request->patient_id != '') {
            $query->where('patient_id', $request->patient_id);
        }

        $datatable = $datatable->eloquent($query)
            ->addColumn('check', function ($row) {
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-' . $row->id . '"  name="datatable_ids[]" value="' . $row->id . '" onclick="dataTableRowCheck(' . $row->id . ')">';
            })
            ->addColumn('patient_name', function ($data) {
                if ($data->patient) {
                    return $data->patient->first_name . ' ' . $data->patient->last_name;
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('action', function ($data) use ($request) {
                // Use customer action column if patient_id filter is set and it's a customer view
                if ($request->has('patient_id') && $request->patient_id != '' && $request->has('customer_view')) {
                    return view('backend.consultations.customer_action_column', compact('data'));
                }
                return view('backend.consultations.action_column', compact('data'));
            })
            ->editColumn('consultation_date', function ($data) {
                return $data->consultation_date ? $data->consultation_date->format('Y-m-d') : '-';
            })
            ->editColumn('items', function ($data) {
                $items = $data->items ?? [];
                $count = is_array($items) ? count($items) : 0;
                $itemsJson = htmlspecialchars(json_encode($items), ENT_QUOTES, 'UTF-8');
                return '<button type="button" class="btn btn-sm btn-info view-items-btn" data-id="' . $data->id . '" data-items=\'' . $itemsJson . '\' data-bs-toggle="tooltip" title="Voir les éléments">
                    <i class="fas fa-eye"></i> Voir (' . $count . ')
                </button>';
            })
            ->editColumn('updated_at', function ($data) {
                $diff = Carbon::now()->diffInHours($data->updated_at);
                if ($diff < 25) {
                    return $data->updated_at->diffForHumans();
                } else {
                    return $data->updated_at->isoFormat('llll');
                }
            })
            ->rawColumns(['action', 'items', 'check', 'patient_name'])
            ->orderColumns(['id'], '-:column $1');

        return $datatable->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.consultations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'consultation_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*' => 'nullable|string',
        ]);

        $items = array_filter($request->items ?? [], function($item) {
            return !empty(trim($item));
        });

        if (empty($items)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'At least one consultation item is required.',
                    'status' => false
                ], 422);
            }
            return back()->withInput()->withErrors(['items' => 'At least one consultation item is required.']);
        }

        $data = [
            'consultation_date' => $request->consultation_date,
            'patient_id' => $request->patient_id ?? null,
            'items' => array_values($items),
            'created_by' => auth()->id(),
        ];

        Consultation::create($data);

        $message = __('messages.create_form', ['form' => 'Consultation']);

        if ($request->expectsJson()) {
            return response()->json(['message' => $message, 'status' => true], 200);
        }

        return redirect()->route('backend.consultations.index')->with('success', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $consultation = Consultation::findOrFail($id);
        return view('backend.consultations.show', compact('consultation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $consultation = Consultation::findOrFail($id);
        $data = $consultation->toArray();
        // Format date for HTML date input (YYYY-MM-DD)
        if ($consultation->consultation_date) {
            $data['consultation_date'] = $consultation->consultation_date->format('Y-m-d');
        }
        return response()->json(['data' => $data, 'status' => true]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'consultation_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*' => 'nullable|string',
        ]);

        $consultation = Consultation::findOrFail($id);

        $items = array_filter($request->items ?? [], function($item) {
            return !empty(trim($item));
        });

        if (empty($items)) {
            return response()->json([
                'message' => 'At least one consultation item is required.',
                'status' => false
            ], 422);
        }

        $data = [
            'consultation_date' => $request->consultation_date,
            'patient_id' => $request->patient_id ?? null,
            'items' => array_values($items),
            'updated_by' => auth()->id(),
        ];

        $consultation->update($data);

        $message = __('messages.update_form', ['form' => 'Consultation']);

        return response()->json(['message' => $message, 'status' => true], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (env('IS_DEMO')) {
            return response()->json(['message' => __('messages.permission_denied'), 'status' => false], 200);
        }

        $consultation = Consultation::findOrFail($id);
        $consultation->delete();

        $message = __('messages.delete_form', ['form' => 'Consultation']);

        return response()->json(['message' => $message, 'status' => true], 200);
    }
}

