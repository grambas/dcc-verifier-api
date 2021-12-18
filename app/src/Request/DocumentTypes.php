<?php

declare(strict_types=1);

namespace App\Request;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="Document Types",
 *     required={"vaccination", "recovery", "pcrTest", "rapidTest"},
 * )
 */
class DocumentTypes
{
    /** @var bool */
    public $vaccination;

    /** @var bool */
    public $recovery;

    /** @var bool */
    public $pcrTest;

    /** @var bool */
    public $rapidTest;
}
