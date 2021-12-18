<?php

declare(strict_types=1);

namespace App\Response;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="Error",
 *     required={"code", "message"},
 * )
 */
class Error
{
    /**
     * @OA\Property(description="error unique code")
     *
     * @var int
     */
    public $code;

    /**
     * @OA\Property(description="error description")
     *
     * @var string
     */
    public $message;

    public function __construct(int $code, string $message)
    {
        $this->code = $code;
        $this->message = $message;
    }
}
