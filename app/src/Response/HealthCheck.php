<?php

declare(strict_types=1);

namespace App\Response;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="Health Check Response",
 *     required={"trustListLastCheck", "trustListLastModification"},
 * )
 */
class HealthCheck
{
    public function __construct(?string $trustListLastCheck, ?string $trustListLastModification)
    {
        $this->trustListLastCheck = $trustListLastCheck;
        $this->trustListLastModification = $trustListLastModification;
    }

    /**
     * @OA\Property(
     *     type="datetime", description="UTC datetime of last trust list check in ISO8601 format",
     *     example="2021-12-17T16:54:37+0000",
     *     nullable=true
     * )
     *
     * @var ?string
     */
    public $trustListLastCheck;

    /**
     * @OA\Property(
     *     type="datetime", description="UTC datetime of last trust list update in ISO8601 format",
     *     example="2021-12-17T16:54:37+0000",
     *     nullable=true
     * )
     *
     * @var ?string
     */
    public $trustListLastModification;
}
