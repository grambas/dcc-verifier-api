<?php

declare(strict_types=1);

namespace App\Service;

use App\Request\DocumentTypes;
use App\Request\Validate;
use Grambas\Model\CertificateInterface;
use Grambas\Model\DCC;

class ValidationHelper
{
    public static function getBinaryRepresentation(DocumentTypes $documents): int
    {
        $check = 0;
        if (true === $documents->vaccination) {
            $check += CertificateInterface::VACCINATION;
        }

        if (true === $documents->recovery) {
            $check += CertificateInterface::RECOVERY;
        }

        if (true === $documents->pcrTest) {
            $check += CertificateInterface::PCR_TEST;
        }

        if (true === $documents->rapidTest) {
            $check += CertificateInterface::RAPID_TEST;
        }

        return $check;
    }

    public static function isSamePerson(Validate $request, DCC $dcc): bool
    {
        if (!empty($request->firstName)) {
            if (strtolower($request->firstName) !== strtolower($dcc->firstName)) {
                return false;
            }
        }

        if (!empty($request->lastName)) {
            if (strtolower($request->lastName) !== strtolower($dcc->lastName)) {
                return false;
            }
        }

        return true;
    }
}
