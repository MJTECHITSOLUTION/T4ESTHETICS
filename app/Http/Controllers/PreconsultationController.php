<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PreconsultationController extends Controller
{
    public function publicForm(Request $request, int $customerId)
    {
        $user = User::find($customerId);
        $info = UserInfo::where('customer_id', $customerId)->first();

        if (!$user) {
            abort(404, 'User not found');
        }

        return view('preconsultation-public', [
            'user' => $user,
            'info' => $info,
        ]);
    }

    public function show(Request $request, int $customerId)
    {
        $user = User::find($customerId);
        $info = UserInfo::where('customer_id', $customerId)->first();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found'], 404);
        }

        $data = [
            // Identity from users table
            'identite_nom' => $user->last_name,
            'identite_prenom' => $user->first_name,
            'identite_date_naissance' => $user->date_of_birth,
            'identite_email' => $user->email,
            'identite_telephone' => $user->mobile,
            'identite_adresse' => $user->adresse,
            'identite_profession' => $user->profession,
            'identite_newsletter' => (bool) $user->is_subscribe,
            // Prefer saved user_infos for extended fields
            'antecedents' => $info?->antecedents ?? [],
            'autoimmunes' => $info?->autoimmunes ?? [],
            'autoimmunes_autre' => $info?->autoimmunes_autre,
            'allergies' => $info?->allergies ?? [],
            'allergies_autre' => $info?->allergies_autre,
            'traitements' => $info?->traitements,
            'esthetiques' => $info?->esthetiques ?? [],
            'esthetiques_autre' => $info?->esthetiques_autre,
            // Map motif_consultation JSON from users to motifs if user_infos not set
            'motifs' => $info?->motifs ?? (is_array($user->motif_consultation) ? $user->motif_consultation : []),
            'motifs_autre' => $info?->motifs_autre,
            'parrainage' => (bool) ($info?->parrainage ?? false),
            'declaration_exactitude' => (bool) ($info?->declaration_exactitude ?? false),
        ];

        return response()->json(['status' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        $rules = [
            'customer_id' => 'required|integer',
            'identite_email' => 'nullable|email',
            'identite_date_naissance' => 'nullable|date',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $payload = $request->only([
            'customer_id',
            'identite_nom',
            'identite_prenom',
            'identite_date_naissance',
            'identite_email',
            'identite_telephone',
            'identite_adresse',
            'identite_profession',
            'identite_newsletter',
            'antecedents',
            'autoimmunes',
            'autoimmunes_autre',
            'allergies',
            'allergies_autre',
            'traitements',
            'esthetiques',
            'esthetiques_autre',
            'motifs',
            'motifs_autre',
            'parrainage',
            'declaration_exactitude',
        ]);

        // Defaults to allow clearing values on update
        $arrayKeys = ['antecedents','autoimmunes','allergies','esthetiques','motifs'];
        foreach ($arrayKeys as $k) {
            if (!array_key_exists($k, $payload)) {
                $payload[$k] = [];
            }
        }

        foreach (['identite_newsletter','parrainage','declaration_exactitude'] as $boolKey) {
            if (array_key_exists($boolKey, $payload)) {
                $payload[$boolKey] = (bool) ($payload[$boolKey] === true || $payload[$boolKey] === '1' || $payload[$boolKey] === 1 || $payload[$boolKey] === 'on');
            } else {
                $payload[$boolKey] = false;
            }
        }

        // Normalize empty strings to null for scalar fields
        $nullableKeys = [
            'identite_nom','identite_prenom','identite_date_naissance','identite_email','identite_telephone','identite_adresse','identite_profession',
            'autoimmunes_autre','allergies_autre','traitements','esthetiques_autre','motifs_autre',
        ];
        foreach ($nullableKeys as $k) {
            if (array_key_exists($k, $payload) && ($payload[$k] === '' || $payload[$k] === null)) {
                $payload[$k] = null;
            }
        }

        $info = DB::transaction(function () use ($payload) {
            // Update main user table fields
            $user = User::find($payload['customer_id']);
            if ($user) {
                $user->last_name = $payload['identite_nom'] ?? $user->last_name;
                $user->first_name = $payload['identite_prenom'] ?? $user->first_name;
                $user->date_of_birth = $payload['identite_date_naissance'] ?? $user->date_of_birth;
                $user->email = $payload['identite_email'] ?? $user->email;
                $user->mobile = $payload['identite_telephone'] ?? $user->mobile;
                $user->adresse = $payload['identite_adresse'] ?? $user->adresse;
                $user->profession = $payload['identite_profession'] ?? $user->profession;
                $user->is_subscribe = $payload['identite_newsletter'];
                // Optionally map motifs back to users.motif_consultation
                if (array_key_exists('motifs', $payload)) {
                    $user->motif_consultation = $payload['motifs'];
                }
                $user->save();
            }

            // Persist extended fields in user_infos
            return UserInfo::updateOrCreate(
                ['customer_id' => $payload['customer_id']],
                $payload
            );
        });

        return response()->json([
            'status' => true,
            'message' => 'Pré-consultation enregistrée avec succès',
            'data' => $info,
        ]);
    }

    public function pdf(Request $request, int $customerId)
    {
        $user = User::find($customerId);
        $info = UserInfo::where('customer_id', $customerId)->first();

        if (!$user) {
            abort(404, 'User not found');
        }

        $pdf = Pdf::loadView('preconsultation-pdf', [
            'user' => $user,
            'info' => $info,
        ])->setPaper('a4');

        $filename = 'preconsultation-' . $user->id . '.pdf';
        return $pdf->stream($filename);
    }
}


