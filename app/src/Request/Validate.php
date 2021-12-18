<?php

declare(strict_types=1);

namespace App\Request;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="Validation Request",
 *     required={"certificate", "documentTypes"},
 * )
 */
class Validate
{
    /**
     * @OA\Property(
     *     description="qr code content which starts with HC1:",
     *     example="HC1:....",
     *     nullable=false
     * )
     *
     * @var string
     */
    public $certificate;

    /**
     * @OA\Property(description="first name if check is needed", nullable=true)
     *
     * @var string
     */
    public $firstName;

    /**
     * @OA\Property(description="last name if check is needed", nullable=true)
     *
     * @var string
     */
    public $lastName;

    /**
     * @OA\Property(description="list of accepted document types", nullable=false)
     *
     * @var DocumentTypes
     */
    public $documentTypes;
}
