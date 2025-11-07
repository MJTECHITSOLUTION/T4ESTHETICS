@php($signedPost = URL::signedRoute('preconsultation.public.store'))
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pré-consultation - {{ $user->first_name }} {{ $user->last_name }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { background: #f8f9fa; }
        .card { max-width: 900px; margin: 24px auto; }
        .step-indicator { display: flex; gap: 8px; }
        .step-indicator .step { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: #e9ecef; }
        .step-indicator .step.active { background: #0d6efd; color: #fff; }
    </style>
    <script>
        window.__PUBLIC_CUSTOMER_ID__ = {{ (int) $user->id }};
        window.__SIGNED_POST__ = @json($signedPost);
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Formulaire de Pré-consultation</h5>
    </div>
    <div class="card-body">
        <p class="text-muted">Merci de remplir ce formulaire avant votre consultation.</p>

        @php(
            $pref = [
                'antecedents' => (array) ($info->antecedents ?? []),
                'autoimmunes' => (array) ($info->autoimmunes ?? []),
                'allergies' => (array) ($info->allergies ?? []),
                'esthetiques' => (array) ($info->esthetiques ?? []),
                'motifs' => (array) ($info->motifs ?? (is_array($user->motif_consultation ?? null) ? $user->motif_consultation : [])),
                'autoimmunes_autre' => $info->autoimmunes_autre ?? '',
                'allergies_autre' => $info->allergies_autre ?? '',
                'esthetiques_autre' => $info->esthetiques_autre ?? '',
                'motifs_autre' => $info->motifs_autre ?? '',
                'traitements' => $info->traitements ?? '',
                'newsletter' => (bool) ($user->is_subscribe ?? false),
                'parrainage' => (bool) ($info->parrainage ?? false),
                'declaration' => (bool) ($info->declaration_exactitude ?? false),
            ]
        )

        <form id="publicPreForm" class="mt-3">
            <input type="hidden" name="customer_id" value="{{ $user->id }}">

            <div class="step-indicator mb-3">
                <div class="step active" data-step="1">1</div>
                <div class="step" data-step="2">2</div>
                <div class="step" data-step="3">3</div>
                <div class="step" data-step="4">4</div>
                <div class="step" data-step="5">5</div>
                <div class="step" data-step="6">6</div>
                <div class="step" data-step="7">7</div>
                <div class="step" data-step="8">8</div>
            </div>

            <div class="devis-step" data-step-panel="1">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nom</label>
                        <input type="text" name="identite_nom" class="form-control" value="{{ old('identite_nom', $user->last_name) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Prénom</label>
                        <input type="text" name="identite_prenom" class="form-control" value="{{ old('identite_prenom', $user->first_name) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date de Naissance</label>
                        <input type="date" name="identite_date_naissance" class="form-control" value="{{ old('identite_date_naissance', $user->date_of_birth) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="identite_email" class="form-control" value="{{ old('identite_email', $user->email) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="identite_telephone" class="form-control" value="{{ old('identite_telephone', $user->mobile) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Adresse</label>
                        <input type="text" name="identite_adresse" class="form-control" value="{{ old('identite_adresse', $user->adresse) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Profession</label>
                        <input type="text" name="identite_profession" class="form-control" value="{{ old('identite_profession', $user->profession) }}">
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="identite_newsletter" name="identite_newsletter" value="1" {{ $pref['newsletter'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="identite_newsletter">Souhaitez-vous recevoir nos nouveautés par email ?</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="devis-step" data-step-panel="2" style="display:none;">
                <label class="form-label">Antécédents Médicaux et Chirurgicaux</label>
                <div class="row">
                    <div class="col-12">
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[]" value="aucuns" id="a1" {{ in_array('aucuns', $pref['antecedents']) ? 'checked' : '' }}><label class="form-check-label" for="a1">Aucuns</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[]" value="herpes" id="a2" {{ in_array('herpes', $pref['antecedents']) ? 'checked' : '' }}><label class="form-check-label" for="a2">Herpès</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[]" value="diabete" id="a3" {{ in_array('diabete', $pref['antecedents']) ? 'checked' : '' }}><label class="form-check-label" for="a3">Diabète</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[]" value="epilepsie" id="a4" {{ in_array('epilepsie', $pref['antecedents']) ? 'checked' : '' }}><label class="form-check-label" for="a4">Épilepsie</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[]" value="cancer" id="a5" {{ in_array('cancer', $pref['antecedents']) ? 'checked' : '' }}><label class="form-check-label" for="a5">Cancer</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[]" value="maladie_virale" id="a6" {{ in_array('maladie_virale', $pref['antecedents']) ? 'checked' : '' }}><label class="form-check-label" for="a6">Maladie virale (Hépatite, HIV, Sida)</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[]" value="maladie_bacterienne" id="a7" {{ in_array('maladie_bacterienne', $pref['antecedents']) ? 'checked' : '' }}><label class="form-check-label" for="a7">Maladie bactérienne</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[]" value="glaucome" id="a8" {{ in_array('glaucome', $pref['antecedents']) ? 'checked' : '' }}><label class="form-check-label" for="a8">Glaucome</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[]" value="depression" id="a9" {{ in_array('depression', $pref['antecedents']) ? 'checked' : '' }}><label class="form-check-label" for="a9">Dépression / Troubles psychiatriques</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[]" value="troubles_alimentaires" id="a10" {{ in_array('troubles_alimentaires', $pref['antecedents']) ? 'checked' : '' }}><label class="form-check-label" for="a10">Anorexie / Boulimie</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[]" value="addictions" id="a11" {{ in_array('addictions', $pref['antecedents']) ? 'checked' : '' }}><label class="form-check-label" for="a11">Addictions (Toxico, Alcool)</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[]" value="hta" id="a12" {{ in_array('hta', $pref['antecedents']) ? 'checked' : '' }}><label class="form-check-label" for="a12">Hypertension artérielle (HTA)</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[]" value="trouble_respiratoire" id="a13" {{ in_array('trouble_respiratoire', $pref['antecedents']) ? 'checked' : '' }}><label class="form-check-label" for="a13">Trouble respiratoire</label></div>
                    </div>
                </div>
            </div>

            <div class="devis-step" data-step-panel="3" style="display:none;">
                <label class="form-label">Avez-vous des maladies auto-immunes suivantes ?</label>
                <div class="row">
                    <div class="col-12">
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="autoimmunes[]" value="aucune" id="m1" {{ in_array('aucune', $pref['autoimmunes']) ? 'checked' : '' }}><label class="form-check-label" for="m1">Aucune</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="autoimmunes[]" value="crohn" id="m2" {{ in_array('crohn', $pref['autoimmunes']) ? 'checked' : '' }}><label class="form-check-label" for="m2">Maladie de Crohn</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="autoimmunes[]" value="lupus" id="m3" {{ in_array('lupus', $pref['autoimmunes']) ? 'checked' : '' }}><label class="form-check-label" for="m3">Lupus</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="autoimmunes[]" value="thyroidite" id="m4" {{ in_array('thyroidite', $pref['autoimmunes']) ? 'checked' : '' }}><label class="form-check-label" for="m4">Thyroïdite</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="autoimmunes[]" value="polyarthrite_rhumatoide" id="m5" {{ in_array('polyarthrite_rhumatoide', $pref['autoimmunes']) ? 'checked' : '' }}><label class="form-check-label" for="m5">Polyarthrite rhumatoïde</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="autoimmunes[]" value="sclerodermie" id="m6" {{ in_array('sclerodermie', $pref['autoimmunes']) ? 'checked' : '' }}><label class="form-check-label" for="m6">Sclérodermie</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="autoimmunes[]" value="sep" id="m7" {{ in_array('sep', $pref['autoimmunes']) ? 'checked' : '' }}><label class="form-check-label" for="m7">SEP (Sclérose en plaques)</label></div>
                        <div class="mt-2"><label class="form-label">Autre</label><input type="text" class="form-control" name="autoimmunes_autre" value="{{ $pref['autoimmunes_autre'] }}"></div>
                    </div>
                </div>
            </div>

            <div class="devis-step" data-step-panel="4" style="display:none;">
                <label class="form-label">Avez-vous eu des réactions allergiques suite à un acte esthétique ?</label>
                <div class="row">
                    <div class="col-12">
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="allergies[]" value="aucune" id="r1" {{ in_array('aucune', $pref['allergies']) ? 'checked' : '' }}><label class="form-check-label" for="r1">Aucune</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="allergies[]" value="urticaire" id="r2" {{ in_array('urticaire', $pref['allergies']) ? 'checked' : '' }}><label class="form-check-label" for="r2">Urticaire</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="allergies[]" value="reaction_nodulaire" id="r3" {{ in_array('reaction_nodulaire', $pref['allergies']) ? 'checked' : '' }}><label class="form-check-label" for="r3">Réaction nodulaire</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="allergies[]" value="taches" id="r4" {{ in_array('taches', $pref['allergies']) ? 'checked' : '' }}><label class="form-check-label" for="r4">Taches</label></div>
                        <div class="mt-2"><label class="form-label">Autre</label><input type="text" class="form-control" name="allergies_autre" value="{{ $pref['allergies_autre'] }}"></div>
                    </div>
                </div>
            </div>

            <div class="devis-step" data-step-panel="5" style="display:none;">
                <label class="form-label">Vos traitements médicamenteux</label>
                <textarea name="traitements" class="form-control" rows="4" placeholder="Listez vos traitements et compléments actuels">{{ old('traitements', $pref['traitements']) }}</textarea>
            </div>

            <div class="devis-step" data-step-panel="6" style="display:none;">
                <label class="form-label">Antécédents Esthétiques</label>
                <div class="row">
                    <div class="col-12">
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="esthetiques[]" value="comblement" id="e1" {{ in_array('comblement', $pref['esthetiques']) ? 'checked' : '' }}><label class="form-check-label" for="e1">Injection de comblement</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="esthetiques[]" value="toxine_botulique" id="e2" {{ in_array('toxine_botulique', $pref['esthetiques']) ? 'checked' : '' }}><label class="form-check-label" for="e2">Toxine botulique</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="esthetiques[]" value="peeling" id="e3" {{ in_array('peeling', $pref['esthetiques']) ? 'checked' : '' }}><label class="form-check-label" for="e3">Peeling</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="esthetiques[]" value="laser_visage" id="e4" {{ in_array('laser_visage', $pref['esthetiques']) ? 'checked' : '' }}><label class="form-check-label" for="e4">Laser visage</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="esthetiques[]" value="chirurgie_visage" id="e5" {{ in_array('chirurgie_visage', $pref['esthetiques']) ? 'checked' : '' }}><label class="form-check-label" for="e5">Chirurgie esthétique du visage</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="esthetiques[]" value="chirurgie_silhouette" id="e6" {{ in_array('chirurgie_silhouette', $pref['esthetiques']) ? 'checked' : '' }}><label class="form-check-label" for="e6">Chirurgie esthétique de la silhouette</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="esthetiques[]" value="laser_epilation" id="e7" {{ in_array('laser_epilation', $pref['esthetiques']) ? 'checked' : '' }}><label class="form-check-label" for="e7">Laser épilation</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="esthetiques[]" value="cryolipolyse" id="e8" {{ in_array('cryolipolyse', $pref['esthetiques']) ? 'checked' : '' }}><label class="form-check-label" for="e8">Cryolipolyse</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="esthetiques[]" value="emsculpt" id="e9" {{ in_array('emsculpt', $pref['esthetiques']) ? 'checked' : '' }}><label class="form-check-label" for="e9">EMsculpt</label></div>
                        <div class="mt-2"><label class="form-label">Autre</label><input type="text" class="form-control" name="esthetiques_autre" value="{{ $pref['esthetiques_autre'] }}"></div>
                    </div>
                </div>
            </div>

            <div class="devis-step" data-step-panel="7" style="display:none;">
                <label class="form-label">Votre motif principal de consultation ce jour</label>
                <div class="row">
                    <div class="col-12">
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="troubles_rides" id="mcf1" {{ in_array('troubles_rides', $pref['motifs']) ? 'checked' : '' }}><label class="form-check-label" for="mcf1">Troubles des rides</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="volumetrie_visage" id="mcf2" {{ in_array('volumetrie_visage', $pref['motifs']) ? 'checked' : '' }}><label class="form-check-label" for="mcf2">Volumétrie du visage</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="fils_tenseurs" id="mcf3" {{ in_array('fils_tenseurs', $pref['motifs']) ? 'checked' : '' }}><label class="form-check-label" for="mcf3">Fils tenseurs</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="rhinoplastie_medicale" id="mcf4" {{ in_array('rhinoplastie_medicale', $pref['motifs']) ? 'checked' : '' }}><label class="form-check-label" for="mcf4">Rhinoplastie médicale</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="peeling" id="mcf5" {{ in_array('peeling', $pref['motifs']) ? 'checked' : '' }}><label class="form-check-label" for="mcf5">Peeling</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="hydrafacial" id="mcf6" {{ in_array('hydrafacial', $pref['motifs']) ? 'checked' : '' }}><label class="form-check-label" for="mcf6">Hydrafacial</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="nettoyage_peau" id="mcf7" {{ in_array('nettoyage_peau', $pref['motifs']) ? 'checked' : '' }}><label class="form-check-label" for="mcf7">Nettoyage de peau dermatologique</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="laser_peau" id="mcf8" {{ in_array('laser_peau', $pref['motifs']) ? 'checked' : '' }}><label class="form-check-label" for="mcf8">Laser de peau (HIFU, Frax, Plexr)</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="coup_eclat" id="mcf9" {{ in_array('coup_eclat', $pref['motifs']) ? 'checked' : '' }}><label class="form-check-label" for="mcf9">Coup d'éclat / Réhydratation</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="cosmetologie" id="mcf10" {{ in_array('cosmetologie', $pref['motifs']) ? 'checked' : '' }}><label class="form-check-label" for="mcf10">Cosmétologie sur mesure</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="cheveu" id="mcf11" {{ in_array('cheveu', $pref['motifs']) ? 'checked' : '' }}><label class="form-check-label" for="mcf11">Traitement du cheveu</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="mains" id="mcf12" {{ in_array('mains', $pref['motifs']) ? 'checked' : '' }}><label class="form-check-label" for="mcf12">Traitement des mains</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="silhouette_emsculpt" id="mcf13" {{ in_array('silhouette_emsculpt', $pref['motifs']) ? 'checked' : '' }}><label class="form-check-label" for="mcf13">Traitement de la silhouette (EMsculpt)</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="led" id="mcf14" {{ in_array('led', $pref['motifs']) ? 'checked' : '' }}><label class="form-check-label" for="mcf14">LED (visage, cheveux, intime)</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="epilation" id="mcf15" {{ in_array('epilation', $pref['motifs']) ? 'checked' : '' }}><label class="form-check-label" for="mcf15">Épilation (laser, électrique)</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="micronutrition" id="mcf16" {{ in_array('micronutrition', $pref['motifs']) ? 'checked' : '' }}><label class="form-check-label" for="mcf16">Micronutrition</label></div>
                        <div class="mt-2"><label class="form-label">Autre</label><input type="text" class="form-control" name="motifs_autre" value="{{ $pref['motifs_autre'] }}"></div>
                    </div>
                </div>
            </div>

            <div class="devis-step" data-step-panel="8" style="display:none;">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="parrainage" name="parrainage" value="1" {{ $pref['parrainage'] ? 'checked' : '' }}>
                    <label class="form-check-label" for="parrainage">Souhaitez-vous participer à un programme de parrainage ?</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="declaration_exactitude" name="declaration_exactitude" value="1" {{ $pref['declaration'] ? 'checked' : '' }}>
                    <label class="form-check-label" for="declaration_exactitude">À ma connaissance, j'atteste l'exactitude de ces informations...</label>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="button" id="publicPrev" class="btn btn-outline-primary" style="display:none;">Précédent</button>
                <button type="button" id="publicNext" class="btn btn-primary">Suivant</button>
                <button type="button" id="submitBtn" class="btn btn-success" style="display:none;">Envoyer</button>
            </div>
        </form>
    </div>
</div>

<script>
let step = 1;
const total = 8;

function updateSteps(){
    $('.devis-step').hide();
    $(`[data-step-panel="${step}"]`).show();
    $('#publicPrev').toggle(step > 1);
    $('#publicNext').toggle(step < total);
    $('#submitBtn').toggle(step === total);
    $('.step-indicator .step').removeClass('active');
    $(`.step-indicator .step[data-step="${step}"]`).addClass('active');
}

$(function(){
    updateSteps();
    $('#publicPrev').on('click', function(){ if(step>1){ step--; updateSteps(); }});
    $('#publicNext').on('click', function(){ if(step<total){ step++; updateSteps(); }});
});

$('#submitBtn').on('click', function(){
    const $btn = $(this);
    const original = $btn.html();
    $btn.prop('disabled', true).html('Envoi...');

    const fd = new FormData(document.getElementById('publicPreForm'));
    const payload = {};
    fd.forEach((v,k)=>{ if (payload[k] !== undefined) { if(!Array.isArray(payload[k])) payload[k] = [payload[k]]; payload[k].push(v);} else { payload[k]=v; } });

    $.ajax({
        url: window.__SIGNED_POST__,
        method: 'POST',
        data: payload,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(resp){
            alert(resp.message || 'Merci, vos informations ont été enregistrées.');
            document.getElementById('publicPreForm').reset();
        },
        error: function(xhr){
            let msg = 'Une erreur est survenue.';
            if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
            alert(msg);
        },
        complete: function(){
            $btn.prop('disabled', false).html(original);
        }
    });
});
</script>
</body>
</html>


