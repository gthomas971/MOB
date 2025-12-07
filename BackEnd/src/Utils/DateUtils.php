<?php

namespace App\Utils;

final class DateUtils
{
    /**
     * Convertit une chaîne en DateTimeImmutable.
     * Retourne null si la date est invalide ou null.
     */
    public static function convertDate(?string $value): ?\DateTimeImmutable
    {
        if ($value === null) {
            return null;
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return null;
        }

        try {
            $date = new \DateTimeImmutable($value);

            if ($date->format('Y-m-d') !== $value) {
                return null;
            }

            return $date;

        } catch (\Exception $e) {
            return null;
        }
    }

}
