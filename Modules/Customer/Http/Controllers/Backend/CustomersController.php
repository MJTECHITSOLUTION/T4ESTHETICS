<?php

namespace Modules\Customer\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Booking\Models\Booking;
use Modules\Customer\Http\Requests\CustomerRequest;
use Modules\CustomField\Models\CustomField;
use Modules\CustomField\Models\CustomFieldGroup;
use Modules\Customer\Models\DevisUser;
use Modules\Customer\Models\DevisDetail;
use Modules\Customer\Models\DevisFacture;
use Modules\Package\Models\Package;
use Modules\Tax\Models\Tax;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Modules\Customer\Models\MedicalRecord;
use Modules\Customer\Models\MedicalHistory;
use Modules\Customer\Models\MedicalHistoryType;
use Illuminate\Support\Facades\Storage;
use Modules\Customer\Models\CustomerAct;
use Modules\Customer\Models\CustomerActGallery;
use Illuminate\Support\Facades\Log;
use Modules\Service\Models\Service as ServiceModel;
use App\Models\Branch;
use Modules\Service\Models\ServiceEmployee;
use Modules\Service\Models\ServiceBranches;

class CustomersController extends Controller
{
    // use Authorizable;
    protected string $exportClass = '\App\Exports\CustomerExport';
    protected string $module_title;
    protected string $module_name;
    protected string $module_path;

    public function __construct()
    {
        // Page Title
        $this->module_title = 'customer.title';

        // module name
        $this->module_name = 'customers';

        // directory path of the module
        $this->module_path = 'customer::backend';

        view()->share([
            'module_title' => $this->module_title,
            'module_icon' => 'fa-regular fa-sun',
            'module_name' => $this->module_name,
            'module_path' => $this->module_path,
        ]);
        $this->middleware(['permission:view_customer'])->only('index', 'show');
        $this->middleware(['permission:edit_customer'])->only('edit', 'update');
        $this->middleware(['permission:add_customer'])->only('store');
        $this->middleware(['permission:delete_customer'])->only('destroy');
    }

    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);

        $actionType = $request->action_type;

        $message = __('messages.bulk_update');

        // dd($actionType, $ids, $request->status);
        switch ($actionType) {
            case 'change-status':
                $customer = User::whereIn('id', $ids)->update(['status' => $request->status]);
                $message = __('messages.bulk_customer_update');
                break;

            case 'delete':
                if (env('IS_DEMO')) {
                    return response()->json(['message' => __('messages.permission_denied'), 'status' => false], 200);
                }
                User::whereIn('id', $ids)->delete();
                $message = __('messages.bulk_customer_delete');
                break;

            default:
                return response()->json(['status' => false, 'message' => __('branch.invalid_action')]);
                break;
        }

        return response()->json(['status' => true, 'message' => __('messages.bulk_update')]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $module_action = 'List';
        $columns = CustomFieldGroup::columnJsonValues(new User());
        $customefield = CustomField::exportCustomFields(new User());

        $export_import = true;
        $export_columns = [
            [
                'value' => 'first_name',
                'text' => 'First Name',
            ],
            [
                'value' => 'last_name',
                'text' => 'Last Name',
            ],
            [
                'value' => 'email',
                'text' => 'E-mail',
            ],
            [
                'value' => 'varification_status',
                'text' => 'Verification Status',
            ],
            [
                'value' => 'is_banned',
                'text' => 'Banned',
            ],
            [
                'value' => 'status',
                'text' => 'Status',
            ],
        ];
        $export_url = route('backend.customers.export');

        return view('customer::backend.customers.index', compact('module_action', 'columns', 'customefield', 'export_import', 'export_columns', 'export_url'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $module_action = 'Show';
        $data = User::findOrFail($id);

        // Load user's bookings with related items for invoice tab
        $bookings = Booking::with([
            'services',
            'products',
            'bookingPackages',
            'payment',
        ])->where('user_id', (int) $id)
            ->orderBy('created_at', 'desc')
            ->get();

        if (! is_null($data)) {
            $custom_field_data = $data->withCustomFields();
            $data['custom_field_data'] = collect($custom_field_data->custom_fields_data)
                ->filter(function ($value) {
                    return $value !== null;
                })
                ->toArray();
        }

        $data['profile_image'] = $data->profile_image;

        // Load factures converted from devis for this customer
        $devisFactures = DevisFacture::with(['devis'])
            ->whereHas('devis', function($q) use ($id) { $q->where('customer_id', (int) $id); })
            ->orderByDesc('created_at')
            ->get();

        return view('customer::backend.customers.show', compact('module_action', 'data', 'bookings', 'devisFactures'));
    }

    public function update_status(Request $request, User $id)
    {
        $id->update(['status' => $request->status]);

        return response()->json(['status' => true, 'message' => __('branch.status_update')]);
    }

    public function index_data(Datatables $datatable, Request $request)
    {
        $module_name = $this->module_name;
        $query = User::role('user');

        $filter = $request->filter;

        if (isset($filter)) {
            if (isset($filter['column_status'])) {
                $query->where('status', $filter['column_status']);
            }
        }

        $datatable = $datatable->eloquent($query)
            ->addColumn('check', function ($row) {
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$row->id.'"  name="datatable_ids[]" value="'.$row->id.'" onclick="dataTableRowCheck('.$row->id.')">';
            })
            ->addColumn('action', function ($data) use ($module_name) {
                return view('customer::backend.customers.action_column', compact('module_name', 'data'));
            })

            // ->editColumn('image', function ($data) {
            //     return "<img src='".$data->profile_image."'class='avatar avatar-50 rounded-pill'>";
            // })

            ->addColumn('user_id', function ($data) {
                $Profile_image = optional($data)->profile_image ?? default_user_avatar();
                $name =optional($data)->full_name ?? default_user_name() ;
                $email =  optional($data)->email ?? '--' ;
                return view('booking::backend.bookings.datatable.user_id', compact('Profile_image','name','email'));
                // return view('employee::backend.employees.employee_id', compact('data'));
            })
            ->orderColumn('user_id', function ($query, $order) {
                $query->orderBy('users.first_name', $order) // Ordering by first name
                      ->orderBy('users.last_name', $order); // Optional: also order by last name
            }, 1)
            ->filterColumn('user_id', function ($query, $keyword) {
                if (!empty($keyword)) {
                    // Assuming 'users' table has first_name and last_name
                    $query->where(function ($query) use ($keyword) {
                        $query->where('first_name', 'like', '%' . $keyword . '%')
                              ->orWhere('last_name', 'like', '%' . $keyword . '%') // Filtering by last name
                              ->orWhere('email', 'like', '%' . $keyword . '%');
                    });
                }
            })
            
            
            // ->editColumn('user_id', function ($data) {
            //     return  $data->first_name . ' ' . $data->last_name;
            // })

            ->editColumn('email_verified_at', function ($data) {
                $checked = '';
                if ($data->email_verified_at) {
                    return '<span class="badge bg-soft-success"><i class="fa-solid fa-envelope" style="margin-right: 2px"></i> '.__('customer.msg_verified').'</span>';
                }

                return '<button  type="button" data-url="'.route('backend.customers.verify-customer', $data->id).'" data-token="'.csrf_token().'" class="button-status-change btn btn-text-danger btn-sm  bg-soft-danger"  id="datatable-row-'.$data->id.'"  name="is_verify" value="'.$data->id.'" '.$checked.'>Verify</button>';
            })

            ->editColumn('is_banned', function ($data) {
                $checked = '';
                if ($data->is_banned) {
                    $checked = 'checked="checked"';
                }

                return '
                    <div class="form-check form-switch ">
                        <input type="checkbox" data-url="'.route('backend.customers.block-customer', $data->id).'" data-token="'.csrf_token().'" class="switch-status-change form-check-input"  id="datatable-row-'.$data->id.'"  name="is_banned" value="'.$data->id.'" '.$checked.'>
                    </div>
                 ';
            })

            ->editColumn('status', function ($row) {
                $checked = '';
                if ($row->status) {
                    $checked = 'checked="checked"';
                }

                return '
                    <div class="form-check form-switch ">
                        <input type="checkbox" data-url="'.route('backend.customers.update_status', $row->id).'" data-token="'.csrf_token().'" class="switch-status-change form-check-input"  id="datatable-row-'.$row->id.'"  name="status" value="'.$row->id.'" '.$checked.'>
                    </div>
                ';
            })
            ->editColumn('updated_at', function ($data) {
                $module_name = $this->module_name;

                $diff = Carbon::now()->diffInHours($data->updated_at);

                if ($diff < 25) {
                    return $data->updated_at->diffForHumans();
                } else {
                    return $data->updated_at->isoFormat('llll');
                }
            })
            
            // ->filterColumn('user_id', function ($query, $keyword) {
            //     if (!empty($keyword)) {   
            //             $query->whereRaw('CONCAT(first_name, " ", last_name) LIKE ?', ['%' . $keyword . '%']);
            //     }
            // })

            ->orderColumns(['id'], '-:column $1');

        // Custom Fields For export
        $customFieldColumns = CustomField::customFieldData($datatable, User::CUSTOM_FIELD_MODEL, null);

        return $datatable->rawColumns(array_merge(['user_id','action', 'status', 'is_banned', 'email_verified_at', 'check', 'image'], $customFieldColumns))
            ->toJson();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CustomerRequest $request)
    {
        $data = $request->all();

        $data = User::create($data);

        $data->syncRoles(['user']);

        Artisan::call('cache:clear');

        if ($request->custom_fields_data) {
            $data->updateCustomFieldData(json_decode($request->custom_fields_data));
        }

        // Handle profile image: store under public/profile_images/{userId}/ and persist path in users.profile_image
        if ($request->hasFile('profile_image')) {
            try {
                $file = $request->file('profile_image');
                $dir = 'profile_images/' . (int) $data->id;
                $fileName = 'profile_' . time() . '_' . uniqid('', true) . '.' . $file->getClientOriginalExtension();
                $storedPath = $file->storeAs($dir, $fileName, 'public');
                $data->update(['profile_image' => $storedPath]);
            } catch (\Throwable $e) {
                Log::warning('Failed to store profile image: ' . $e->getMessage());
            }
        }

        $message = __('messages.create_form', ['form' => __('customer.singular_title')]);

        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function edit($id)
    {
        $data = User::findOrFail($id);

        if (! is_null($data)) {
            $custom_field_data = $data->withCustomFields();
            $data['custom_field_data'] = collect($custom_field_data->custom_fields_data)
                ->filter(function ($value) {
                    return $value !== null;
                })
                ->toArray();
        }

        $data['profile_image'] = $data->profile_image;

        return response()->json(['data' => $data, 'status' => true]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CustomerRequest $request, $id)
    {
        $data = User::findOrFail($id);

        $request_data = $request->except('profile_image');

        $data->update($request_data);
        if ($request->custom_fields_data) {
            $data->updateCustomFieldData(json_decode($request->custom_fields_data));
        }

        // Handle profile image updates
        if ($request->hasFile('profile_image')) {
            try {
                // delete old file if present
                if (!empty($data->profile_image)) {
                    try {
                        Storage::disk('public')->delete($data->profile_image);
            } catch (\Throwable $e) {
                Log::warning('Failed to delete old profile image: ' . $e->getMessage());
                    }
                }
                $file = $request->file('profile_image');
                $dir = 'profile_images/' . (int) $data->id;
                $fileName = 'profile_' . time() . '_' . uniqid('', true) . '.' . $file->getClientOriginalExtension();
                $storedPath = $file->storeAs($dir, $fileName, 'public');
                $data->update(['profile_image' => $storedPath]);
            } catch (\Throwable $e) {
                Log::warning('Failed to store profile image: ' . $e->getMessage());
            }
        }
        if ($request->profile_image === null) {
            // clear current image if explicitly set to null
            if (!empty($data->profile_image)) {
                try {
                    Storage::disk('public')->delete($data->profile_image);
                } catch (\Throwable $e) {
                    Log::warning('Failed to delete profile image: ' . $e->getMessage());
                }
                $data->update(['profile_image' => null]);
            }
        }
        $message = __('messages.update_form', ['form' => __('customer.singular_title')]);

        return response()->json(['message' => $message, 'status' => true], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (env('IS_DEMO')) {
            return response()->json(['message' => __('messages.permission_denied'), 'status' => false], 200);
        }
        $data = User::findOrFail($id);
        
        $booking = Booking::where('user_id', $id)->where('status', '!=', 'completed')->update(['status' => 'cancelled']);

        $data->tokens()->delete();

        $data->forceDelete();

        $message = __('messages.delete_form', ['form' => __('customer.singular_title')]);

        return response()->json(['message' => $message, 'status' => true], 200);
    }

    /**
     * List customer acts
     */
    public function getActs($customerId)
    {
        $acts = CustomerAct::with(['service', 'employee', 'branch'])
            ->where('user_id', $customerId)
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['data' => $acts, 'status' => true]);
    }

    /**
     * Store a new act for a customer
     */
    public function storeAct(Request $request, $customerId)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'branch_id' => 'nullable|exists:branches,id',
            'employee_id' => 'nullable|exists:users,id',
            'act_date' => 'nullable|date',
            'status' => 'nullable|string',
            'note' => 'nullable|string',
            'service_price' => 'nullable|numeric',
            'duration_min' => 'nullable|integer',
        ]);

        // Pre-fill price/duration if available from service-branch mapping or service
        if (! isset($validated['service_price']) || ! isset($validated['duration_min'])) {
            $service = ServiceModel::find($validated['service_id']);
            if ($service) {
                $validated['service_price'] = $validated['service_price'] ?? $service->default_price;
                $validated['duration_min'] = $validated['duration_min'] ?? $service->duration_min;
            }
        }

        $validated['user_id'] = (int) $customerId;
        $act = CustomerAct::create($validated);

        return response()->json(['message' => __('messages.create_form', ['form' => 'Act']), 'data' => $act->load(['service','employee','branch']), 'status' => true]);
    }

    /**
     * Update an existing act
     */
    public function updateAct(Request $request, $actId)
    {
        $act = CustomerAct::findOrFail($actId);

        $validated = $request->validate([
            'service_id' => 'sometimes|exists:services,id',
            'branch_id' => 'nullable|exists:branches,id',
            'employee_id' => 'nullable|exists:users,id',
            'act_date' => 'nullable|date',
            'status' => 'nullable|string',
            'note' => 'nullable|string',
            'service_price' => 'nullable|numeric',
            'duration_min' => 'nullable|integer',
        ]);

        $act->update($validated);

        return response()->json(['message' => __('messages.update_form', ['form' => 'Act']), 'data' => $act->load(['service','employee','branch']), 'status' => true]);
    }

    /**
     * Delete an act
     */
    public function deleteAct($actId)
    {
        $act = CustomerAct::findOrFail($actId);
        $act->delete();
        return response()->json(['message' => __('messages.delete_form', ['form' => 'Act']), 'status' => true]);
    }

    /**
     * Option lists for Actes modal
     */
    public function actOptions()
    {
        $services = ServiceModel::select('id', 'name', 'default_price', 'duration_min')
            ->active()
            ->orderBy('name')
            ->get();

        $branches = Branch::select('id', 'name')->orderBy('name')->get();

        $employees = User::select('id', 'first_name', 'last_name')
            ->where('status', 1)
            ->orderBy('first_name')
            ->get()
            ->map(function ($u) {
                $u->full_name = trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? '')) ?: ('#' . $u->id);
                return $u;
            });

        return response()->json([
            'services' => $services,
            'branches' => $branches,
            'employees' => $employees,
            'status_list' => [
                [ 'value' => 'planned', 'label' => 'Planned' ],
                [ 'value' => 'done', 'label' => 'Done' ],
                [ 'value' => 'cancelled', 'label' => 'Cancelled' ],
            ],
            'status' => true,
        ]);
    }

    /**
     * Service specific employees and branches (with branch-specific price and duration)
     */
    public function actServiceMeta($serviceId)
    {
        $service = ServiceModel::findOrFail($serviceId);

        $serviceEmployees = ServiceEmployee::with('employee')
            ->where('service_id', $serviceId)
            ->get()
            ->map(function ($se) {
                return [
                    'id' => $se->employee?->id,
                    'name' => trim(($se->employee?->first_name ?? '') . ' ' . ($se->employee?->last_name ?? '')) ?: ('#' . ($se->employee?->id ?? '')),
                ];
            })
            ->filter(fn ($e) => ! empty($e['id']))
            ->values();

        $serviceBranches = ServiceBranches::with('branch')
            ->where('service_id', $serviceId)
            ->get()
            ->map(function ($sb) {
                return [
                    'id' => $sb->branch?->id,
                    'name' => $sb->branch?->name,
                    'service_price' => $sb->service_price,
                    'duration_min' => $sb->duration_min,
                ];
            })
            ->filter(fn ($b) => ! empty($b['id']))
            ->values();

        return response()->json([
            'employees' => $serviceEmployees,
            'branches' => $serviceBranches,
            'service' => [
                'default_price' => $service->default_price,
                'duration_min' => $service->duration_min,
            ],
            'status' => true,
        ]);
    }

    /**
     * Act Gallery: list sessions for an act
     */
    public function getActGalleries($actId)
    {
        try {
            $act = CustomerAct::findOrFail((int) $actId);

            $sessions = CustomerActGallery::where('customer_act_id', $act->id)
                ->orderBy('session_date', 'asc')
                ->get()
                ->map(function ($g) {
                    return [
                        'id' => $g->id,
                        'phase' => $g->phase,
                        'session_date' => $g->session_date,
                        'note' => $g->note,
                        'images' => $g->getMedia('images')->map(function ($m) {
                            return [
                                'id' => $m->id,
                                'file_name' => $m->file_name,
                                'size' => $m->size,
                                'mime_type' => $m->mime_type,
                                'url' => $m->getFullUrl(),
                            ];
                        }),
                    ];
                });

            return response()->json(['status' => true, 'data' => $sessions]);
        } catch (\Throwable $e) {
            Log::error('Act galleries load error: '.$e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to load sessions'], 500);
        }
    }

    /**
     * Act Gallery: create a session (before or after) and upload images
     */
    public function storeActGallery(Request $request, $actId)
    {
        $act = CustomerAct::findOrFail((int) $actId);

        $validated = $request->validate([
            'phase' => 'required|in:before,after',
            'session_date' => 'nullable|date',
            'note' => 'nullable|string',
            'images' => 'required|array|min:1',
            'images.*' => 'file|mimetypes:image/jpeg,image/png,image/webp|max:10240',
        ]);

        $gallery = CustomerActGallery::create([
            'customer_act_id' => $act->id,
            'phase' => $validated['phase'],
            'session_date' => $validated['session_date'] ?? null,
            'note' => $validated['note'] ?? null,
        ]);

        foreach ($request->file('images', []) as $file) {
            // Use original client filename to avoid BaseModel hashed name override mismatch
            $gallery->addMedia($file)->usingFileName($file->getClientOriginalName())->toMediaCollection('images');
        }

        return response()->json([
            'status' => true,
            'message' => 'Gallery session created',
            'data' => [
                'id' => $gallery->id,
            ],
        ]);
    }

    /**
     * Act Gallery: append images to existing session
     */
    public function addActGalleryImages(Request $request, $galleryId)
    {
        $gallery = CustomerActGallery::findOrFail((int) $galleryId);

        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'file|mimetypes:image/jpeg,image/png,image/webp|max:10240',
        ]);

        foreach ($request->file('images', []) as $file) {
            $gallery->addMedia($file)->usingFileName($file->getClientOriginalName())->toMediaCollection('images');
        }

        return response()->json(['status' => true, 'message' => 'Images added']);
    }

    /**
     * Act Gallery: update meta (date, note, phase)
     */
    public function updateActGallery(Request $request, $galleryId)
    {
        $gallery = CustomerActGallery::findOrFail((int) $galleryId);

        $validated = $request->validate([
            'phase' => 'sometimes|in:before,after',
            'session_date' => 'nullable|date',
            'note' => 'nullable|string',
        ]);

        $gallery->update($validated);
        return response()->json(['status' => true, 'message' => 'Gallery session updated']);
    }

    /**
     * Act Gallery: delete image from a session
     */
    public function deleteActGalleryImage($galleryId, $mediaId)
    {
        $gallery = CustomerActGallery::findOrFail((int) $galleryId);
        $media = $gallery->getMedia('images')->firstWhere('id', (int) $mediaId);
        if (! $media) {
            return response()->json(['status' => false, 'message' => 'Image not found'], 404);
        }
        $media->delete();
        return response()->json(['status' => true, 'message' => 'Image deleted']);
    }

    /**
     * Act Gallery: delete a whole session
     */
    public function deleteActGallery($galleryId)
    {
        $gallery = CustomerActGallery::findOrFail((int) $galleryId);
        $gallery->clearMediaCollection('images');
        $gallery->delete();
        return response()->json(['status' => true, 'message' => 'Gallery session deleted']);
    }

    /**
     * List of trashed ertries
     * works if the softdelete is enabled.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function trashed()
    {
        $module_name = $this->module_name;

        $module_name_singular = Str::singular($module_name);

        $module_action = 'Trash List';

        $data = User::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate();

        return view('customer::backend.customers.trash', compact('data', 'module_name_singular', 'module_action'));
    }

    /**
     * Restore a soft deleted entry.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function restore($id)
    {
        $module_action = 'Restore';

        $data = User::withTrashed()->find($id);
        $data->restore();

        return redirect('app/customers');
    }

    public function change_password(Request $request)
    {
        $data = $request->all();

        $user_id = $data['user_id'];

        $data = User::findOrFail($user_id);

        $request_data = $request->only('password');
        $request_data['password'] = Hash::make($request_data['password']);

        $data->update($request_data);

        $message = __('messages.password_update');

        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function block_customer(Request $request, User $id)
    {
        $id->update(['is_banned' => $request->status]);

        if ($request->status == 1) {
            $message = __('messages.google_blocked');
        } else {
            $message = __('messages.google_unblocked');
        }

        return response()->json(['status' => true, 'message' => $message]);
    }

    public function verify_customer(Request $request, $id)
    {
        $data = User::findOrFail($id);

        $current_time = Carbon::now();

        $data->update(['email_verified_at' => $current_time]);

        return response()->json(['status' => true, 'message' => __('messages.customer_verify')]);
    }

    public function uniqueEmail(Request $request)
    {
        $email = $request->input('email');
        $userId = $request->input('user_id');

        $isUnique = User::where('email', $email)
                        ->where(function ($query) use ($userId) {
                            if ($userId) {
                                $query->where('id', '!=', $userId);
                            }
                        })
                        ->doesntExist();

        return response()->json(['isUnique' => $isUnique]);
    }

    /**
     * Get packages for devis creation
     */
    public function getPackages()
    {
        try {
            $packages = Package::with(['branch'])
                ->where('status', 1)
                ->select('id', 'name', 'package_price', 'description', 'end_date', 'branch_id')
                ->get()
                ->map(function ($package) {
                    return [
                        'id' => $package->id,
                        'name' => $package->name,
                        'package_price' => $package->package_price,
                        'description' => $package->description,
                        'end_date' => $package->end_date,
                        'branch_name' => $package->branch ? $package->branch->name : 'N/A'
                    ];
                });

            return response()->json([
                'status' => true,
                'data' => $packages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error loading packages: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get package details with services
     */
    public function getPackageDetails(Request $request)
    {
        try {
            $packageId = $request->input('package_id');
            
            if (!$packageId) {
                return response()->json([
                    'status' => false,
                    'message' => 'Package ID is required'
                ], 400);
            }
            
            // Get package basic info
            $package = Package::with(['branch'])
                ->where('id', $packageId)
                ->where('status', 1)
                ->first();

            if (!$package) {
                return response()->json([
                    'status' => false,
                    'message' => 'Package not found'
                ], 404);
            }

            // Get package services separately to avoid relationship issues
            $packageServices = DB::table('package_services')
                ->where('package_id', $packageId)
                ->get();

            $packageData = [
                'id' => $package->id,
                'name' => $package->name,
                'package_price' => $package->package_price,
                'description' => $package->description,
                'end_date' => $package->end_date,
                'branch_name' => $package->branch ? $package->branch->name : 'N/A',
                'services' => $packageServices->map(function ($packageService) {
                    return [
                        'id' => $packageService->id,
                        'service_id' => $packageService->service_id,
                        'service_name' => $packageService->service_name,
                        'service_price' => $packageService->service_price,
                        'quantity' => $packageService->qty,
                        'discount_price' => $packageService->discounted_price,
                        'duration_min' => 0 // We'll set this to 0 for now to avoid additional queries
                    ];
                })
            ];

            return response()->json([
                'status' => true,
                'data' => [$packageData]
            ]);
        } catch (\Exception $e) {
            Log::error('Package Details Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Error loading package details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get taxes for devis creation
     */
    public function getTaxes()
    {
        try {
            $taxes = Tax::where('status', 1)
                ->select('id', 'title', 'type', 'value')
                ->get();

            return response()->json([
                'status' => true,
                'data' => $taxes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error loading taxes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save devis
     */
    public function saveDevis(Request $request)
    {
        try {
            $request->validate([
                'customer_id' => 'required|exists:users,id',
                'package_id' => 'nullable|exists:packages,id',
                'services' => 'required|array|min:1',
                'services.*.service_name' => 'required|string|max:255',
                'services.*.quantity' => 'required|integer|min:1',
                'services.*.price' => 'required|numeric|min:0',
                'services.*.discount' => 'nullable|numeric|min:0',
                'services.*.number_of_lot' => 'nullable|string|max:255',
                'tax_rate' => 'nullable|numeric|min:0|max:100',
                'remarks' => 'nullable|string',
                'received_at' => 'nullable|date',
                'accepted_at' => 'nullable|date',
                'signature_image' => 'nullable|string'
            ]);

            // Calculate totals
            $subtotal = 0;
            $totalTaxAmount = 0;
            
            foreach ($request->services as $service) {
                $serviceSubtotal = $service['quantity'] * ($service['price'] - ($service['discount'] ?? 0));
                $serviceTvaRate = $service['tva_rate'] ?? 0;
                $serviceTaxAmount = ($serviceSubtotal * $serviceTvaRate) / 100;
                
                $subtotal += $serviceSubtotal;
                $totalTaxAmount += $serviceTaxAmount;
            }
            
            $totalAmount = $subtotal + $totalTaxAmount;

            // Optionally handle signature image (base64 data URL)
            $signaturePath = null;
            if ($request->filled('signature_image') && is_string($request->signature_image)) {
                try {
                    $dataUrl = $request->signature_image;
                    if (strpos($dataUrl, 'data:image') === 0) {
                        $matches = [];
                        if (preg_match('/^data:image\/(png|jpeg|jpg);base64,(.*)$/', $dataUrl, $matches)) {
                            $ext = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
                            $base64 = $matches[2];
                            $base64 = str_replace(' ', '+', $base64);
                            $binary = base64_decode($base64);
                            if ($binary !== false) {
                                $dir = 'signatures';
                                $fileName = $dir . '/devis_' . time() . '_' . uniqid('', true) . '.' . $ext;
                                Storage::disk('public')->put($fileName, $binary);
                                $signaturePath = $fileName;
                            }
                        }
                    }
                } catch (\Throwable $th) {
                    Log::warning('Failed to store signature image: ' . $th->getMessage());
                }
            }
            
            // Create devis user record
            $devisUser = DevisUser::create([
                'customer_id' => $request->customer_id,
                'package_id' => $request->package_id,
                'devis_number' => DevisUser::generateDevisNumber(),
                'subtotal' => $subtotal,
                'tax_rate' => 0, // We're using individual TVA rates now
                'tax_amount' => $totalTaxAmount,
                'total_amount' => $totalAmount,
                'remarks' => $request->remarks,
                'received_at' => $request->received_at,
                'accepted_at' => $request->accepted_at,
                'signature_path' => $signaturePath,
                'status' => 'draft',
                'valid_until' => now()->addDays(30), // 30 days validity
                'created_by' => auth()->id(),
                'updated_by' => auth()->id()
            ]);

            // Create devis details
            foreach ($request->services as $service) {
                $serviceSubtotal = $service['quantity'] * ($service['price'] - ($service['discount'] ?? 0));
                $serviceTvaRate = $service['tva_rate'] ?? 0;
                $serviceTaxAmount = ($serviceSubtotal * $serviceTvaRate) / 100;
                $serviceTotal = $serviceSubtotal + $serviceTaxAmount;

                DevisDetail::create([
                    'devis_user_id' => $devisUser->id,
                    'service_name' => $service['service_name'],
                    'service_id' => $service['service_id'] ?? null,
                    'quantity' => $service['quantity'],
                    'price' => $service['price'],
                    'discount' => $service['discount'] ?? 0,
                    'subtotal' => $serviceSubtotal,
                    'tax_amount' => $serviceTaxAmount,
                    'total' => $serviceTotal,
                    'remarks' => $service['remarks'] ?? null,
                    'number_of_lot' => $service['number_of_lot'] ?? null,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id()
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Devis saved successfully',
                'data' => [
                    'devis_id' => $devisUser->id,
                    'devis_number' => $devisUser->devis_number,
                    'total_amount' => $devisUser->total_amount,
                    'signature_image_url' => ($devisUser->signature_path ?? null) ? asset('storage/'.$devisUser->signature_path) : null
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error saving devis: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get customer devis list
     */
    public function getCustomerDevis($customerId)
    {
        try {
            Log::info('Loading devis for customer ID: ' . $customerId);
            
            // Get devis with relationships
            $devisList = DevisUser::with(['devisDetails', 'package'])
                ->where('customer_id', $customerId)
                ->orderBy('created_at', 'desc')
                ->get();
                

            Log::info('Found ' . $devisList->count() . ' devis for customer');
            // Transform the data for frontend
            $transformedData = $devisList->map(function ($devis) {
                return [
                    'id' => $devis->id,
                    'devis_number' => $devis->devis_number,
                    'package' => $devis->package ? [
                        'id' => $devis->package->id,
                        'name' => $devis->package->name
                    ] : null,
                    'subtotal' => $devis->subtotal,
                    'tax_amount' => $devis->tax_amount,
                    'total_amount' => $devis->total_amount,
                    'status' => $devis->status,
                    'created_at' => $devis->created_at,
                    'valid_until' => $devis->valid_until,
                    'remarks' => $devis->remarks,
                    'signature_image_url' => (isset($devis->signature_path) && $devis->signature_path) ? asset('storage/'.$devis->signature_path) : null,
                    'devis_details' => $devis->devisDetails->map(function ($detail) {
                        return [
                            'id' => $detail->id,
                            'service_name' => $detail->service_name,
                            'quantity' => $detail->quantity,
                            'price' => $detail->price,
                            'discount' => $detail->discount,
                            'subtotal' => $detail->subtotal,
                            'tax_amount' => $detail->tax_amount,
                            'total' => $detail->total,
                            'remarks' => $detail->remarks
                        ];
                    })
                ];
            });

            return response()->json([
                'status' => true,
                'data' => $transformedData
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading devis for customer ' . $customerId . ': ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'status' => false,
                'message' => 'Error loading devis: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update devis status
     */
    public function updateDevisStatus(Request $request, $devisId)
    {
        try {
            $request->validate([
                'status' => 'required|in:draft,sent,accepted,rejected,expired'
            ]);

            $devis = DevisUser::findOrFail($devisId);
            $devis->update(['status' => $request->status]);

            return response()->json([
                'status' => true,
                'message' => 'Devis status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating devis status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Convert a devis to a facture if not already exists
     */
    public function convertDevisToFacture(Request $request, $devisId)
    {
        try {
            $devis = DevisUser::with('facture')->findOrFail((int) $devisId);

            if ($devis->facture) {
                // If already converted, respond appropriately depending on request type
                if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Facture already exists',
                        'data' => $devis->facture,
                    ]);
                }
                return redirect()->back()->with('success', __('messages.update_form', ['form' => 'Facture']));
            }

            // Generate facture number: FAC + YYYYMM + 4-digit seq
            $prefix = 'FAC';
            $year = date('Y');
            $month = date('m');
            $last = DevisFacture::where('facture_number', 'like', $prefix.$year.$month.'%')
                ->orderBy('facture_number', 'desc')
                ->first();
            if ($last) {
                $seq = (int) substr($last->facture_number, -4) + 1;
            } else {
                $seq = 1;
            }
            $factureNumber = $prefix.$year.$month.str_pad($seq, 4, '0', STR_PAD_LEFT);

            $facture = DevisFacture::create([
                'devis_user_id' => $devis->id,
                'facture_number' => $factureNumber,
                'subtotal' => $devis->subtotal,
                'tax_amount' => $devis->tax_amount,
                'total_amount' => $devis->total_amount,
                'status' => 'unpaid',
                'issued_at' => now(),
            ]);

            // Optionally mark devis as accepted/sent
            if ($devis->status !== 'accepted') {
                $devis->update(['status' => 'accepted']);
            }

            if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Devis converted to facture',
                    'data' => $facture,
                ]);
            }

            return redirect()->back()->with('success', 'Devis converted to facture');
        } catch (\Throwable $e) {
            Log::error('Convert devis error: '.$e->getMessage());
            if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to convert devis',
                ], 500);
            }
            return redirect()->back()->with('error', 'Failed to convert devis');
        }
    }

    /**
     * Test devis functionality
     */
    public function testDevis($customerId)
    {
        try {
            Log::info('Test devis for customer ID: ' . $customerId);
            
            // Test if DevisUser model exists
            $modelExists = class_exists(DevisUser::class);
            
            // Test if table exists
            $tableExists = false;
            $devisCount = 0;
            $error = null;
            
            try {
                $devisCount = DevisUser::where('customer_id', $customerId)->count();
                $tableExists = true;
            } catch (\Exception $e) {
                $error = $e->getMessage();
                Log::error('Table query error: ' . $e->getMessage());
            }
            
            return response()->json([
                'status' => true,
                'message' => 'Test completed',
                'customer_id' => $customerId,
                'devis_count' => $devisCount,
                'model_exists' => $modelExists,
                'table_exists' => $tableExists,
                'error' => $error
            ]);
        } catch (\Exception $e) {
            Log::error('Test devis error: ' . $e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => 'Test failed: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete devis
     */
    public function deleteDevis($devisId)
    {
        try {
            Log::info('Deleting devis ID: ' . $devisId);
            
            // Find the devis
            $devis = DevisUser::find($devisId);
            
            if (!$devis) {
                return response()->json([
                    'status' => false,
                    'message' => 'Devis not found'
                ], 404);
            }
            
            // Delete devis details first (due to foreign key constraint)
            $devis->devisDetails()->delete();
            
            // Delete the main devis record using direct database deletion
            // to avoid BaseModel's deleted_by column requirement
            DB::table('devis_user')->where('id', $devisId)->delete();
            
            Log::info('Devis deleted successfully: ' . $devisId);
            
            return response()->json([
                'status' => true,
                'message' => 'Devis deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error deleting devis ' . $devisId . ': ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'status' => false,
                'message' => 'Error deleting devis: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Stream latest devis PDF for a customer
     */
    public function printDevis($customerId)
    {
        try {
            $customer = User::findOrFail((int) $customerId);

            $devis = DevisUser::with('devisDetails')
                ->where('customer_id', $customerId)
                ->latest('created_at')
                ->first();

            if (!$devis) {
                return abort(404, 'No devis found for this customer');
            }

            $html = View::make('customer::backend.customers.devis-pdf', [
                'customer' => $customer,
                'devis' => $devis,
            ])->render();

            $pdf = Pdf::loadHTML($html);
            return $pdf->stream('devis_'.$devis->devis_number.'.pdf');
        } catch (\Exception $e) {
            Log::error('Print devis error: '.$e->getMessage());
            return abort(500, 'Failed to generate PDF');
        }
    }

    /**
     * Print specific devis PDF by devis ID
     */
    public function printDevisPdf($devisId)
    {
        try {
            $devis = DevisUser::with(['devisDetails', 'customer'])
                ->findOrFail($devisId);

            $html = View::make('customer::backend.customers.devis-pdf', [
                'customer' => $devis->customer,
                'devis' => $devis,
            ])->render();

            $pdf = Pdf::loadHTML($html);
            return $pdf->stream('devis_'.$devis->devis_number.'.pdf');
        } catch (\Exception $e) {
            Log::error('Print devis PDF error: '.$e->getMessage());
            return abort(500, 'Failed to generate PDF');
        }
    }

    /**
     * Print specific facture PDF by facture ID
     */
    public function printFacturePdf($factureId)
    {
        try {
            $facture = DevisFacture::with(['devis.customer', 'devis.devisDetails'])
                ->findOrFail($factureId);

            $html = View::make('customer::backend.customers.facture-pdf', [
                'facture' => $facture,
                'customer' => $facture->devis?->customer,
                'devis' => $facture->devis,
            ])->render();

            $pdf = Pdf::loadHTML($html);
            return $pdf->stream('facture_' . $facture->facture_number . '.pdf');
        } catch (\Exception $e) {
            Log::error('Print facture PDF error: ' . $e->getMessage());
            return abort(500, 'Failed to generate invoice PDF');
        }
    }

    /**
     * Print latest facture for a customer
     */
    public function printLatestFacture($customerId)
    {
        try {
            $facture = DevisFacture::with(['devis.customer', 'devis.devisDetails'])
                ->whereHas('devis', function ($q) use ($customerId) {
                    $q->where('customer_id', (int) $customerId);
                })
                ->latest('issued_at')
                ->latest('created_at')
                ->first();

            if (! $facture) {
                return abort(404, 'No facture found for this customer');
            }

            $html = View::make('customer::backend.customers.facture-pdf', [
                'facture' => $facture,
                'customer' => $facture->devis?->customer,
                'devis' => $facture->devis,
            ])->render();

            $pdf = Pdf::loadHTML($html);
            return $pdf->stream('facture_' . $facture->facture_number . '.pdf');
        } catch (\Exception $e) {
            Log::error('Print latest facture error: ' . $e->getMessage());
            return abort(500, 'Failed to generate invoice PDF');
        }
    }

    /**
     * Print a booking-based invoice PDF (from bookings/services/products/packages/payment)
     */
    public function printBookingInvoice($bookingId)
    {
        try {
            $booking = Booking::with(['services', 'products', 'bookingPackages', 'payment', 'user'])
                ->findOrFail((int) $bookingId);

            $html = View::make('customer::backend.customers.booking-invoice-pdf', [
                'booking' => $booking,
            ])->render();

            $pdf = Pdf::loadHTML($html);
            $number = optional($booking->created_at)?->format('Ymd') . '-' . (int)$booking->id;
            return $pdf->stream('invoice_booking_' . $number . '.pdf');
        } catch (\Throwable $e) {
            Log::error('Print booking invoice error: '.$e->getMessage());
            return abort(500, 'Failed to generate booking invoice PDF');
        }
    }

    /**
     * Add payment to a booking
     */
    public function addPayment(Request $request, $bookingId)
    {
        try {
            $booking = Booking::findOrFail((int) $bookingId);
            
            // Validate the request
            $request->validate([
                'payment_method' => 'required|string|in:cash,card,cheque,transfer',
                'tip_amount' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string|max:1000',
                'external_transaction_id' => 'nullable|string|max:255',
                // Cheque fields
                'cheque_number' => 'nullable|string|max:50',
                'cheque_date' => 'nullable|date',
                'bank_name' => 'nullable|string|max:100',
                // Transfer fields
                'transfer_reference' => 'nullable|string|max:50',
                'transfer_date' => 'nullable|date',
                // Card fields
                'card_last_four' => 'nullable|string|max:4',
                'card_type' => 'nullable|string|in:visa,mastercard,amex,other',
                'transaction_reference' => 'nullable|string|max:50',
            ]);

            // Check if payment already exists
            $existingPayment = \Modules\Booking\Models\BookingTransaction::where('booking_id', $bookingId)->first();
            
            if ($existingPayment && $existingPayment->payment_status) {
                return redirect()->back()->with('error', 'Ce booking a déjà été payé.');
            }

            // Calculate totals
            $serviceSubtotal = ($booking->services ?? collect())->sum('service_price');
            $productSubtotal = ($booking->products ?? collect())->sum(function($p){
                $unit = ($p->discounted_price && $p->discounted_price > 0) ? $p->discounted_price : $p->product_price;
                return $unit * (int) $p->product_qty;
            });
            $packageSubtotal = ($booking->bookingPackages ?? collect())->sum('package_price');
            $subtotal = ($serviceSubtotal + $productSubtotal + $packageSubtotal);
            
            // Calculate tax (use existing tax data if available)
            $taxAmount = 0;
            $taxPercentage = null;
            
            if ($existingPayment && $existingPayment->tax_percentage && is_array($existingPayment->tax_percentage)) {
                // Use existing tax data
                $taxPercentage = $existingPayment->tax_percentage;
                foreach ($existingPayment->tax_percentage as $tax) {
                    if (($tax['type'] ?? '') === 'percent') {
                        $taxAmount += ($subtotal * (float) ($tax['percent'] ?? 0)) / 100;
                    } elseif (($tax['type'] ?? '') === 'fixed') {
                        $taxAmount += (float) ($tax['tax_amount'] ?? 0);
                    }
                }
            }
            
            $tipAmount = (float) ($request->tip_amount ?? 0);
            $total = $subtotal + $taxAmount + $tipAmount;

            // Prepare payment data
            $paymentData = [
                'booking_id' => $bookingId,
                'transaction_type' => $request->payment_method,
                'tip_amount' => $tipAmount,
                'tax_percentage' => $taxPercentage,
                'payment_status' => 1, // Always full payment
                'external_transaction_id' => $request->external_transaction_id,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ];

            // Add payment method specific data
            $paymentMethodData = [];
            switch ($request->payment_method) {
                case 'cheque':
                    $paymentMethodData = [
                        'cheque_number' => $request->cheque_number,
                        'cheque_date' => $request->cheque_date,
                        'bank_name' => $request->bank_name,
                    ];
                    break;
                case 'transfer':
                    $paymentMethodData = [
                        'transfer_reference' => $request->transfer_reference,
                        'transfer_date' => $request->transfer_date,
                    ];
                    break;
                case 'card':
                    $paymentMethodData = [
                        'card_last_four' => $request->card_last_four,
                        'card_type' => $request->card_type,
                        'transaction_reference' => $request->transaction_reference,
                    ];
                    break;
            }

            // Add notes and payment method data to payment data
            $paymentData['notes'] = $request->notes;
            $paymentData = array_merge($paymentData, $paymentMethodData);

            // Create or update payment
            if ($existingPayment) {
                $existingPayment->update($paymentData);
                $payment = $existingPayment;
            } else {
                $payment = \Modules\Booking\Models\BookingTransaction::create($paymentData);
            }

            // Update booking status to completed
            $booking->update(['status' => 'completed']);

            return redirect()->back()->with('success', 'Paiement enregistré avec succès pour la facture #' . $bookingId);

        } catch (\Exception $e) {
            Log::error('Add payment error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement du paiement: ' . $e->getMessage());
        }
    }

    /**
     * Add payment to a devis facture
     */
    public function addDevisFacturePayment(Request $request, $factureId)
    {
        try {
            $facture = \Modules\Customer\Models\DevisFacture::findOrFail((int) $factureId);
            
            // Validate the request
            $request->validate([
                'payment_method' => 'required|string|in:cash,card,cheque,transfer',
                'notes' => 'nullable|string|max:1000',
                'external_transaction_id' => 'nullable|string|max:255',
                // Cheque fields
                'cheque_number' => 'nullable|string|max:50',
                'cheque_date' => 'nullable|date',
                'bank_name' => 'nullable|string|max:100',
                // Transfer fields
                'transfer_reference' => 'nullable|string|max:50',
                'transfer_date' => 'nullable|date',
                // Card fields
                'card_last_four' => 'nullable|string|max:4',
                'card_type' => 'nullable|string|in:visa,mastercard,amex,other',
                'transaction_reference' => 'nullable|string|max:50',
            ]);

            // Check if payment already exists
            $existingPayment = \Modules\Booking\Models\BookingTransaction::where('booking_id', $factureId)->first();
            
            if ($existingPayment && $existingPayment->payment_status) {
                return redirect()->back()->with('error', 'Cette facture a déjà été payée.');
            }

            // Prepare payment data
            $paymentData = [
                'booking_id' => $factureId,
                'transaction_type' => $request->payment_method,
                'tip_amount' => 0, // No tip for devis factures
                'tax_percentage' => null, // Use facture's existing tax data
                'payment_status' => 1, // Always full payment
                'external_transaction_id' => $request->external_transaction_id,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ];

            // Add payment method specific data
            $paymentMethodData = [];
            switch ($request->payment_method) {
                case 'cheque':
                    $paymentMethodData = [
                        'cheque_number' => $request->cheque_number,
                        'cheque_date' => $request->cheque_date,
                        'bank_name' => $request->bank_name,
                    ];
                    break;
                case 'transfer':
                    $paymentMethodData = [
                        'transfer_reference' => $request->transfer_reference,
                        'transfer_date' => $request->transfer_date,
                    ];
                    break;
                case 'card':
                    $paymentMethodData = [
                        'card_last_four' => $request->card_last_four,
                        'card_type' => $request->card_type,
                        'transaction_reference' => $request->transaction_reference,
                    ];
                    break;
            }

            // Add notes and payment method data to payment data
            $paymentData['notes'] = $request->notes;
            $paymentData = array_merge($paymentData, $paymentMethodData);

            // Create or update payment
            if ($existingPayment) {
                $existingPayment->update($paymentData);
                $payment = $existingPayment;
            } else {
                $payment = \Modules\Booking\Models\BookingTransaction::create($paymentData);
            }

            // Update facture status to paid
            $facture->update(['status' => 'paid']);

            return redirect()->back()->with('success', 'Paiement enregistré avec succès pour la facture devis #' . $facture->facture_number);

        } catch (\Exception $e) {
            Log::error('Add devis facture payment error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement du paiement: ' . $e->getMessage());
        }
    }

    /**
     * Remove payment from a booking
     */
    public function removePayment($bookingId)
    {
        try {
            $booking = Booking::findOrFail((int) $bookingId);
            
            // Find the payment
            $payment = \Modules\Booking\Models\BookingTransaction::where('booking_id', $bookingId)->first();
            
            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun paiement trouvé pour ce booking.'
                ], 404);
            }

            // Delete the payment
            $payment->delete();

            // Update booking status back to pending
            $booking->update(['status' => 'pending']);

            return response()->json([
                'success' => true,
                'message' => 'Paiement supprimé avec succès pour la facture #' . $bookingId
            ]);

        } catch (\Exception $e) {
            Log::error('Remove payment error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du paiement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove payment from a devis facture
     */
    public function removeDevisFacturePayment($factureId)
    {
        try {
            $facture = \Modules\Customer\Models\DevisFacture::findOrFail((int) $factureId);
            
            // Find the payment
            $payment = \Modules\Booking\Models\BookingTransaction::where('booking_id', $factureId)->first();
            
            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun paiement trouvé pour cette facture.'
                ], 404);
            }

            // Delete the payment
            $payment->delete();

            // Update facture status back to pending
            $facture->update(['status' => 'pending']);

            return response()->json([
                'success' => true,
                'message' => 'Paiement supprimé avec succès pour la facture devis #' . $facture->facture_number
            ]);

        } catch (\Exception $e) {
            Log::error('Remove devis facture payment error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du paiement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Medical Records: list for a customer
     */
    public function getMedicalRecords($customerId)
    {
        try {
            $records = MedicalRecord::where('customer_id', (int) $customerId)
                ->latest('created_at')
                ->get()
                ->map(function ($rec) {
                    return [
                        'id' => $rec->id,
                        'title' => $rec->title,
                        'note' => $rec->note,
                        'file_name' => $rec->file_name,
                        'file_url' => $rec->file_url,
                        'mime_type' => $rec->mime_type,
                        'file_size' => $rec->file_size,
                        'created_at' => $rec->created_at,
                        'updated_at' => $rec->updated_at,
                    ];
                });

            return response()->json([
                'status' => true,
                'data' => $records,
            ]);
        } catch (\Throwable $e) {
            Log::error('Medical records load error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Error loading medical records: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Medical Records: store a document for a customer
     */
    public function storeMedicalRecord(Request $request, $customerId)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'note' => 'nullable|string',
                'document' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx',
            ]);

            $resolvedCustomerId = (int) ($request->input('customer_id') ?: $customerId);
            if ($resolvedCustomerId <= 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid customer id',
                ], 422);
            }
            $user = User::findOrFail($resolvedCustomerId);

            $path = null;
            $fileName = null;
            $mime = null;
            $size = null;

            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $mime = $file->getMimeType();
                $size = $file->getSize();
                $fileName = $file->getClientOriginalName();
                $path = $file->store('medical_records/'.(int)$user->id, 'public');
            }

            $rec = MedicalRecord::create([
                'customer_id' => $user->id,
                'title' => $request->input('title'),
                'note' => $request->input('note'),
                'file_name' => $fileName,
                'file_path' => $path,
                'mime_type' => $mime,
                'file_size' => $size,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Document uploaded successfully',
                'data' => [
                    'id' => $rec->id,
                    'file_url' => $rec->file_url,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Medical record store error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Error saving document: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Medical Records: delete a document
     */
    public function deleteMedicalRecord($recordId)
    {
        try {
            $rec = MedicalRecord::find($recordId);
            if (!$rec) {
                return response()->json([
                    'status' => false,
                    'message' => 'Document not found',
                ], 404);
            }

            // delete file if present
            if ($rec->file_path) {
                try {
                    Storage::disk('public')->delete($rec->file_path);
                } catch (\Throwable $e) {
                    Log::warning('Failed to delete medical file: ' . $e->getMessage());
                }
            }

            $rec->delete();

            return response()->json([
                'status' => true,
                'message' => 'Document deleted',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error deleting document: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Medical History: list items for a customer
     */
    public function getMedicalHistory($customerId)
    {
        try {
            $items = MedicalHistory::with('type')
                ->where('customer_id', (int) $customerId)
                ->latest('created_at')
                ->get()
                ->map(function ($h) {
                    return [
                        'id' => $h->id,
                        'title' => $h->title,
                        'type' => $h->type ? ['id' => $h->type->id, 'name' => $h->type->name] : null,
                        'detail' => $h->detail,
                        'medication' => $h->medication,
                        'start_date' => $h->start_date,
                        'end_date' => $h->end_date,
                        'created_at' => $h->created_at,
                    ];
                });

            return response()->json(['status' => true, 'data' => $items]);
        } catch (\Throwable $e) {
            Log::error('Medical history load error: '.$e->getMessage());
            return response()->json(['status' => false, 'message' => 'Error loading medical history'], 500);
        }
    }

    /**
     * Medical History: create item
     */
    public function storeMedicalHistory(Request $request, $customerId)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'type_id' => 'required|exists:medical_history_types,id',
                'detail' => 'nullable|string',
                'medication' => 'nullable|string',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);

            $resolvedCustomerId = (int) ($request->input('customer_id') ?: $customerId);
            if ($resolvedCustomerId <= 0) {
                return response()->json(['status' => false, 'message' => 'Invalid customer id'], 422);
            }
            $user = User::findOrFail($resolvedCustomerId);

            $item = MedicalHistory::create([
                'customer_id' => $user->id,
                'title' => $request->input('title'),
                'type_id' => $request->input('type_id'),
                'detail' => $request->input('detail'),
                'medication' => $request->input('medication'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            return response()->json(['status' => true, 'message' => 'History added', 'data' => $item->load('type')]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Throwable $e) {
            Log::error('Medical history store error: '.$e->getMessage());
            return response()->json(['status' => false, 'message' => 'Error saving history'], 500);
        }
    }

    /**
     * Medical History: delete item
     */
    public function deleteMedicalHistory($historyId)
    {
        try {
            $item = MedicalHistory::find($historyId);
            if (!$item) {
                return response()->json(['status' => false, 'message' => 'Item not found'], 404);
            }
            $item->delete();
            return response()->json(['status' => true, 'message' => 'History deleted']);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'message' => 'Error deleting history'], 500);
        }
    }

    /**
     * Medical History Types
     */
    public function getMedicalHistoryTypes()
    {
        $types = MedicalHistoryType::orderBy('name')->get(['id','name']);
        return response()->json(['status' => true, 'data' => $types]);
    }

    public function storeMedicalHistoryType(Request $request)
    {
        try {
            $request->validate(['name' => 'required|string|max:191|unique:medical_history_types,name']);
            $type = MedicalHistoryType::create([
                'name' => $request->input('name'),
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);
            return response()->json(['status' => true, 'message' => 'Type created', 'data' => $type]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $e->errors()], 422);
        }
    }
}
