<?php

namespace App\Validator;

use App\Utils\DateUtils;

class AnalyticsRequestValidator
{
    public function validate(?string $fromRaw, ?string $toRaw,  ?string $groupByRaw): array
    {
        $errors = [];

        $fromDate = DateUtils::convertDate($fromRaw);
        $toDate   = DateUtils::convertDate($toRaw);

        if ($fromRaw && !$fromDate) {
            $errors[] = [
                'code' => 'DATE_INVALIDE',
                'message' => 'Le paramètre "from" n\'est pas une date valide.',
                'details' => [sprintf('Valeur fournie : %s', $fromRaw)]
            ];
        }

        if ($toRaw && !$toDate) {
            $errors[] = [
                'code' => 'DATE_INVALIDE',
                'message' => 'Le paramètre "to" n\'est pas une date valide.',
                'details' => [sprintf('Valeur fournie : %s', $toRaw)]
            ];
        }

        if ($fromDate && $toDate && $fromDate > $toDate) {
            $errors[] = [
                'code' => 'PLAGE_DATES_INVALIDE',
                'message' => 'La date "from" ne peut pas être postérieure à la date "to".',
                'details' => [
                    sprintf('Valeurs fournies : from=%s, to=%s', $fromRaw, $toRaw)
                ]
            ];
        }

        $allowedGroups = ['none', 'day', 'month', 'year'];

        $groupBy = $groupByRaw ?: 'none';

        if (!in_array($groupBy, $allowedGroups, true)) {
            $errors[] = [
                'code' => 'GROUPBY_INVALIDE',
                'message' => 'Le paramètre "groupBy" doit être l\'un de : none, day, month, year.',
                'details' => [sprintf('Valeur fournie : %s', $groupByRaw)]
            ];
        }

        return [
            'errors' => $errors,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'groupBy'  => $groupBy,
        ];
    }
}
