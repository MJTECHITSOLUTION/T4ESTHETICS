<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pré-consultation</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 18px; margin: 0 0 10px; }
        h2 { font-size: 14px; margin: 16px 0 6px; border-bottom: 1px solid #ddd; padding-bottom: 4px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px 16px; }
        .row { margin-bottom: 6px; }
        .label { color: #555; }
        .value { font-weight: bold; }
        .muted { color: #666; }
        .section { margin-bottom: 14px; }
        .small { font-size: 11px; }
        .checks { display: grid; grid-template-columns: 1fr 1fr; gap: 4px 16px; }
        .check { display: inline-block; }
        .box { display:inline-block; width: 11px; height: 11px; border: 1px solid #111; margin-right: 6px; position: relative; top: 1px; }
        .box.checked { background: #111; }
        .other { margin-top: 6px; }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 6px 8px; vertical-align: top; }
        .info-table .label { color: #555; display: inline-block; min-width: 110px; }
        .checks-table { width: 100%; border-collapse: collapse; }
        .checks-table td { padding: 4px 8px; vertical-align: top; }
    </style>
    @php
        $info = $info ?? null;
        $bool = fn($v) => $v ? 'Oui' : 'Non';
        $isChecked = function($arr, $key) { return is_array($arr) && in_array($key, $arr ?? [], true); };
        $fmt = function($v) { return $v !== null && $v !== '' ? $v : '-'; };
    @endphp
</head>
<body>
    <table>
        <tr>
            <td>
                <img src="{{ $user->profile_image }}" alt="{{ $user->last_name . ' ' . $user->first_name }}" style="width: 100px; height: 100px;">
            </td>
            <td>
                <h1>Pré-consultation — {{ $user->last_name . ' ' . $user->first_name }}</h1>
            </td>
        </tr>
    </table>


    <div class="section">
        <h2>Identité</h2>
        <table class="info-table">
            <tr>
                <td><span class="label">Nom:</span> <span class="value">{{ $user->last_name }}</span></td>
                <td><span class="label">Prénom:</span> <span class="value">{{ $user->first_name }}</span></td>
                <td><span class="label">Date de naissance:</span> <span class="value">{{ optional($user->date_of_birth)->format('d/m/Y') ?? '-' }}</span></td>
            </tr>
            <tr>
                <td><span class="label">Email:</span> <span class="value">{{ $user->email ?? '-' }}</span></td>
                <td><span class="label">Téléphone:</span> <span class="value">{{ $user->mobile ?? '-' }}</span></td>
                <td><span class="label">Adresse:</span> <span class="value">{{ $user->adresse ?? '-' }}</span></td>
            </tr>
            <tr>
                <td><span class="label">Profession:</span> <span class="value">{{ $user->profession ?? '-' }}</span></td>
                <td><span class="label">Newsletter:</span> <span class="value">{{ $user->is_subscribe ? 'Oui' : 'Non' }}</span></td>
                <td><span class="label">ID Patient:</span> <span class="value">{{ $user->id }}</span></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Antécédents médicaux</h2>
        @php $vals = $info->antecedents ?? []; @endphp
        @php $opts = [
            'aucuns' => 'Aucuns',
            'herpes' => 'Herpès',
            'diabete' => 'Diabète',
            'epilepsie' => 'Épilepsie',
            'cancer' => 'Cancer',
            'maladie_virale' => 'Maladie virale (Hépatite, HIV, Sida)',
            'maladie_bacterienne' => 'Maladie bactérienne',
            'glaucome' => 'Glaucome',
            'depression' => 'Dépression / Troubles psychiatriques',
            'troubles_alimentaires' => 'Anorexie / Boulimie',
            'addictions' => 'Addictions (Toxico, Alcool)',
            'hta' => 'Hypertension artérielle (HTA)',
            'trouble_respiratoire' => 'Trouble respiratoire'
        ]; @endphp
        @php $rows = 5; $cols = 3; $optKeys = array_keys($opts); @endphp
        <table class="checks-table">
            @for($r=0; $r<$rows; $r++)
                <tr>
                    @for($c=0; $c<$cols; $c++)
                        @php $i = $r + ($c * $rows); @endphp
                        <td>
                            @if(isset($optKeys[$i]))
                                @php $key = $optKeys[$i]; $label = $opts[$key]; @endphp
                                <span class="box {{ $isChecked($vals, $key) ? 'checked' : '' }}"></span>{{ $label }}
                            @endif
                        </td>
                    @endfor
                </tr>
            @endfor
        </table>
    </div>

    <div class="section">
        <h2>Maladies auto-immunes</h2>
        @php $vals = $info->autoimmunes ?? []; @endphp
        @php $opts = [
            'aucune' => 'Aucune',
            'crohn' => 'Maladie de Crohn',
            'lupus' => 'Lupus',
            'thyroidite' => 'Thyroïdite',
            'polyarthrite_rhumatoide' => 'Polyarthrite rhumatoïde',
            'sclerodermie' => 'Sclérodermie',
            'sep' => 'SEP (Sclérose en plaques)'
        ]; @endphp
        @php $rows = 3; $cols = 3; $optKeys = array_keys($opts); @endphp
        <table class="checks-table">
            @for($r=0; $r<$rows; $r++)
                <tr>
                    @for($c=0; $c<$cols; $c++)
                        @php $i = $r + ($c * $rows); @endphp
                        <td>
                            @if(isset($optKeys[$i]))
                                @php $key = $optKeys[$i]; $label = $opts[$key]; @endphp
                                <span class="box {{ $isChecked($vals, $key) ? 'checked' : '' }}"></span>{{ $label }}
                            @endif
                        </td>
                    @endfor
                </tr>
            @endfor
        </table>
        <div class="other"><span class="label">Autre:</span> {{ $fmt($info->autoimmunes_autre ?? null) }}</div>
    </div>

    <div class="section">
        <h2>Allergies</h2>
        @php $vals = $info->allergies ?? []; @endphp
        @php $opts = [
            'aucune' => 'Aucune',
            'urticaire' => 'Urticaire',
            'reaction_nodulaire' => 'Réaction nodulaire',
            'taches' => 'Taches'
        ]; @endphp
        @php $rows = 2; $cols = 3; $optKeys = array_keys($opts); @endphp
        <table class="checks-table">
            @for($r=0; $r<$rows; $r++)
                <tr>
                    @for($c=0; $c<$cols; $c++)
                        @php $i = $r + ($c * $rows); @endphp
                        <td>
                            @if(isset($optKeys[$i]))
                                @php $key = $optKeys[$i]; $label = $opts[$key]; @endphp
                                <span class="box {{ $isChecked($vals, $key) ? 'checked' : '' }}"></span>{{ $label }}
                            @endif
                        </td>
                    @endfor
                </tr>
            @endfor
        </table>
        <div class="other"><span class="label">Autre:</span> {{ $fmt($info->allergies_autre ?? null) }}</div>
    </div>

    <div class="section">
        <h2>Traitements en cours</h2>
        <div class="row">{{ $fmt($info->traitements ?? null) }}</div>
    </div>

    <div class="section">
        <h2>Actes esthétiques antérieurs</h2>
        @php $vals = $info->esthetiques ?? []; @endphp
        @php $opts = [
            'comblement' => 'Injection de comblement',
            'toxine_botulique' => 'Toxine botulique',
            'peeling' => 'Peeling',
            'laser_visage' => 'Laser visage',
            'chirurgie_visage' => 'Chirurgie esthétique du visage',
            'chirurgie_silhouette' => 'Chirurgie esthétique de la silhouette',
            'laser_epilation' => 'Laser épilation',
            'cryolipolyse' => 'Cryolipolyse',
            'emsculpt' => 'EMsculpt'
        ]; @endphp
        @php $rows = 4; $cols = 3; $optKeys = array_keys($opts); @endphp
        <table class="checks-table">
            @for($r=0; $r<$rows; $r++)
                <tr>
                    @for($c=0; $c<$cols; $c++)
                        @php $i = $r + ($c * $rows); @endphp
                        <td>
                            @if(isset($optKeys[$i]))
                                @php $key = $optKeys[$i]; $label = $opts[$key]; @endphp
                                <span class="box {{ $isChecked($vals, $key) ? 'checked' : '' }}"></span>{{ $label }}    
                            @endif
                        </td>
                    @endfor
                </tr>
            @endfor
        </table>
        <div class="other"><span class="label">Autre:</span> {{ $fmt($info->esthetiques_autre ?? null) }}</div>
    </div>
    <div style="margin-top: 50px;"></div>
    <div class="section">
        <h2>Motifs de consultation</h2>
        @php $vals = $info->motifs ?? []; @endphp
        @php $opts = [
            'visage_botox_hyaluronique' => 'Visage (Botox / Acide Hyaluronique)',
            'visage_fils_tenseur' => 'Visage (Fils tenseurs)',
            'visage_peeling' => 'Visage (Peeling)',
            'visage_skinbooster_mesotherapie' => 'Visage (Skinbooster / Mésothérapie)',
            'visage_microneedling' => 'Visage (Microneedling)',
            'visage_skinPen' => 'Visage (SkinPen)',
            'visage_tache' => 'Visage (Tâches)',
            'visage_cicatrice' => 'Visage (Cicatrices)',
            'visage_cernes' => 'Visage (Cernes)',
            'yeux_blépharoplastie_medicale' => 'Yeux (Blépharoplastie médicale)',
            'cheveux_soin' => 'Cheveux (Soin)',
            'cheveux_mesotherapie' => 'Cheveux (Mésothérapie)',
            'mains' => 'Traitement des mains',
            'silhouette_emsculpt' => 'Silhouette (EMsculpt)',
            'led' => 'LED (visage, cheveux, intime)',
            'epilation' => 'Épilation (laser, électrique)',
            'micronutrition' => 'Micronutrition'
        ]; @endphp
        @php $rows = 6; $cols = 3; $optKeys = array_keys($opts); @endphp
        <table class="checks-table">
            @for($r=0; $r<$rows; $r++)
                <tr>
                    @for($c=0; $c<$cols; $c++)
                        @php $i = $r + ($c * $rows); @endphp
                        <td>
                            @if(isset($optKeys[$i]))
                                @php $key = $optKeys[$i]; $label = $opts[$key]; @endphp
                                <span class="box {{ $isChecked($vals, $key) ? 'checked' : '' }}"></span>{{ $label }}
                            @endif
                        </td>
                    @endfor
                </tr>
            @endfor
        </table>
        <div class="other"><span class="label">Autre:</span> {{ $fmt($info->motifs_autre ?? null) }}</div>
    </div>

    <div class="section">
        <h2>Déclarations</h2>
        <div class="row"><span class="label">Parrainage:</span> {{ $info ? $bool($info->parrainage) : 'Non' }}</div>
        <div class="row"><span class="label">Exactitude des informations:</span> {{ $info ? $bool($info->declaration_exactitude) : 'Non' }}</div>
    </div>

</body>
</html>

