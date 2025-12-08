<?php

namespace App\Validator;

use App\Repository\AnalyticCodeRepository;

class RoutesRequestValidator
{
    public function __construct(private AnalyticCodeRepository $codeRepo)
    {
    }

    /**
     * Valide les données d'une requête /routes
     *
     * @param array|null $data
     * @return array Tableau d'erreurs, vide si tout est OK
     */
    public function validate(null|array $data): array
    {
        $errors = [];

        if ($data === null) {
            $errors[] = [
                'code' => 'INVALID_JSON',
                'message' => 'Corps JSON invalide.',
                'details' => [],
            ];
            return $errors;
        }

        $fromStationId = $data['fromStationId'] ?? null;
        $toStationId = $data['toStationId'] ?? null;
        $analyticCodeStr = $data['analyticCode'] ?? null;

        if (!$fromStationId) {
            $errors[] = [
                'code' => 'MISSING_PARAM',
                'message' => 'Le paramètre "fromStationId" est requis.',
                'details' => []
            ];
        }

        if (!$toStationId) {
            $errors[] = [
                'code' => 'MISSING_PARAM',
                'message' => 'Le paramètre "toStationId" est requis.',
                'details' => []
            ];
        }

        if (!$analyticCodeStr) {
            $errors[] = [
                'code' => 'MISSING_PARAM',
                'message' => 'Le paramètre "analyticCode" est requis.',
                'details' => []
            ];
        }

        if ($analyticCodeStr && $this->codeRepo->findOneBy(['code' => $analyticCodeStr]) === null) {
            $errors[] = [
                'code' => 'ANALYTIC_CODE_NOT_FOUND',
                'message' => 'Le code analytique fourni est introuvable.',
                'details' => [sprintf('Valeur fournie : %s', $analyticCodeStr)]
            ];
        }

        return $errors;
    }
}
