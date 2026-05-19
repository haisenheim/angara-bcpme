<?php

namespace Database\Seeders;

use App\Models\Instruction\SmeNote;
use Illuminate\Database\Seeder;

/**
 * Référentiel notation PME / avis SME (table sme_notes).
 * Données alignées sur angara_demo_db.sql — requis pour la grille d’instruction.
 */
class SmeNotesSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'note' => 1,
                'name' => 'SME1',
                'mention' => 'Excellent',
                'description' => 'Situation économique et financière et visibilité excellentes :  forte position sur ses marchés, faible endettement par rapport aux fonds propres et au cash flow, peu sensible aux aléas conjoncturels (grande stabilité de la situation financière et des résultats à travers les cycles de son industrie et de l\'économie en général). Sa taille et sa situation lui donnent un accès très facile aux marchés financiers. La capacité du débiteur à exécuter ses engagements financiers est TRES FORTE',
            ],
            [
                'note' => 2,
                'name' => 'SME2',
                'mention' => 'Très Bon',
                'description' => 'La situation financière et le niveau de cash flow actuels sont très bons, avec un historique montrant une bonne stabilité, mais avec une légère sensibilité aux aléas conjoncturels. Endettement modéré par rapport aux fonds propres et au cash flow. Solide position sur ses marchés. Très bonne visibilité à court et moyen terme. Accès facile aux marchés financiers. Sa capacité à exécuter ses engagements financiers est FORTE.',
            ],
            [
                'note' => 3,
                'name' => 'SME3',
                'mention' => 'Bon',
                'description' => 'Situation financière et cash flow convenables mais montrant quelque volatilité, et qui pourraient être affaiblis par une conjoncture (cycle) ou des évènements défavorables dont l\'occurrence est possible. Endettement par rapport aux fonds propres et au cash flow dans la moyenne. Bonne visibilité à court et moyen terme. Possibilité d\'accéder à de nouveaux financements dans une conjoncture normale. Cette note s\'applique aussi aux PME dont la situation financière est solide mais qui sont exclues des catégories précédentes en raison de leur taille.',
            ],
            [
                'note' => 4,
                'name' => 'SME4',
                'mention' => 'Assez Bon',
                'description' => 'Situation financière et cash flow moyens, avec une plus grande volatilité de la situation financière et du cash flow. Il existe quelques facteurs de risque qui pourraient affaiblir la capacité du débiteur à exécuter ses engagements financiers. Cependant, visibilité satisfaisante à court et moyen terme. Accès restreint aux marchés financiers et plus coûteux, mais possibilité de développement des engagements avec ses banques.',
            ],
            [
                'note' => 5,
                'name' => 'SME5',
                'mention' => 'Moyen',
                'description' => 'Cash flow suffisant pour le service de la dette. Les incertitudes sur les fondamentaux du débiteur et son exposition à divers risques (sectoriels, financiers, économiques) peuvent affecter à terme sa capacité à s\'acquitter de ses obligations. Visibilité satisfaisante à court terme mais moins claire à moyen terme. Un accroissement des engagements auprès de ses banques reste encore envisageable. La qualité du management est un élément important de la décision. Accès aux marchés financiers limité, restreint et plus coûteux.',
            ],
            [
                'note' => 6,
                'name' => 'SME6',
                'mention' => 'Acceptable',
                'description' => 'Les risques sectoriels, financiers et économiques sont importants mais devraient être compensés par les fondamentaux du débiteur. Possibilité limitée de trouver des financements en dehors de ses banques. La qualité du management est un élément important de la décision. Les opérations doivent être structurées (garanties, covenants) pour limiter le risque et la rémunération doit être accrue pour tenir compte de la prime de risque plus élevée.',
            ],
            [
                'note' => 7,
                'name' => 'SME7',
                'mention' => 'Potentiellement vulnérable',
                'description' => 'Endettement important pour le secteur. Les risques sectoriels, financiers et économiques sont importants et insuffisamment compensés par les fondamentaux du débiteur qui ne présentent pas une qualité, une stabilité et une visibilité suffisantes. Ce niveau nécessite un suivi attentif. La qualité du management est un élément primordial de la décision. Ce niveau nécessite une grande exigence dans la structuration des opérations et une rémunération significative.',
            ],
            [
                'note' => 8,
                'name' => 'SME7-',
                'mention' => 'Vulnérable',
                'description' => 'Une aggravation des risques économiques et financiers qui pèsent sur le débiteur le conduirait  vraisemblablement à faire défaut sur ses engagements financiers.De nouvelles opérations ne peuvent être envisagées qu\'avec une extrême rigueur dans la structuration et uniquement dans le cadre de politiques de crédit dûment autorisées.',
            ],
            [
                'note' => 9,
                'name' => 'SME8',
                'mention' => 'Très vulnérable',
                'description' => 'En l\'absence d\'amélioration de l\'environnement (conjoncture et marché), et/ou de mesures drastiques de restructuration industrielle ou financière, la survie de l\'entreprise serait en question. Ce niveau doit être suivi de près et assorti d\'objectifs précis pour réduire les risques (réduction des concours, garanties, etc.). Normalement, il exclut  une entrée en relation ou un accroissement des engagements.',
            ],
            [
                'note' => 10,
                'name' => 'SME8-',
                'mention' => 'Douteux et/ou compromis',
                'description' => 'L\'existence d\'échéances financières impayées en principal et/ou en intérêts depuis plus de 3 mois entraîne le classement dans cette catégorie. Les débiteurs les plus faibles de cette catégorie sont dans une situation nettement dégradée et préoccupante quant à la bonne fin des crédits. Un dépôt de bilan est fortement possible. Ce niveau doit être suivi de très près. Le suivi doit être assortie d\'objectifs précis (réduction des concours, amélioration des garanties, etc.). La gestion doit être centralisée (prise en charge par les équipes spécialisées dans la prévention de la défaillance).',
            ],
        ];

        foreach ($rows as $row) {
            SmeNote::query()->updateOrCreate(
                ['note' => $row['note']],
                $row,
            );
        }
    }
}
