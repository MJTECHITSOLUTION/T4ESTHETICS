<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Customer\Models\Consent;

class ConsentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $consents = [
            [
                'name' => 'Consentement de traitement des données personnelles',
                'description' => 'Autorisation pour le traitement des données personnelles conformément au RGPD',
                'content' => 'En cochant cette case, j\'autorise le centre esthétique à traiter mes données personnelles dans le cadre de la gestion de mon dossier client et des soins esthétiques. Ces données seront utilisées uniquement à des fins de soins et de communication relative à mes rendez-vous.',
                'is_active' => true,
                'is_required' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Consentement pour les soins esthétiques',
                'description' => 'Autorisation pour la réalisation des soins esthétiques',
                'content' => 'Je consens à recevoir les soins esthétiques proposés par le centre. J\'ai été informé(e) des risques potentiels et des contre-indications. Je m\'engage à signaler tout changement de santé ou de médication.',
                'is_active' => true,
                'is_required' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Consentement pour la prise de photos',
                'description' => 'Autorisation pour la prise de photos avant/après traitement',
                'content' => 'J\'autorise le centre esthétique à prendre des photos de mon visage/corps avant et après les traitements à des fins de suivi médical et d\'évaluation des résultats. Ces photos seront conservées de manière sécurisée et ne seront pas utilisées à des fins commerciales sans mon accord explicite.',
                'is_active' => true,
                'is_required' => false,
                'sort_order' => 3,
            ],
            [
                'name' => 'Consentement pour les communications marketing',
                'description' => 'Autorisation pour recevoir des offres et promotions',
                'content' => 'J\'accepte de recevoir par email, SMS ou téléphone des informations sur les offres, promotions et nouveaux services du centre esthétique. Je peux me désabonner à tout moment.',
                'is_active' => true,
                'is_required' => false,
                'sort_order' => 4,
            ],
            [
                'name' => 'Consentement pour le partage de données avec des partenaires',
                'description' => 'Autorisation pour le partage de données avec des partenaires de confiance',
                'content' => 'J\'autorise le centre esthétique à partager mes données de contact avec des partenaires de confiance (laboratoires, fournisseurs de produits) uniquement dans le cadre de mes soins et pour améliorer la qualité des services.',
                'is_active' => true,
                'is_required' => false,
                'sort_order' => 5,
            ],
        ];

        foreach ($consents as $consent) {
            Consent::create($consent);
        }
    }
}
