<?php

declare(strict_types=1);

namespace App\Response;

use DateTime;
use DateTimeInterface;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @OA\Schema(
 *     title="Validation Response",
 *     required={"isValid"},
 * )
 */
class Validate
{
    /**
     * @OA\Property(
     *     description="Certificate validation start time in ISO8601 format",
     *     example="2021-12-12T10:00:00+0000",
     *     nullable=true
     * )
     *
     * @var string
     */
    public $validFrom;

    /**
     * @OA\Property(
     *     description="Certificate validation end time in ISO8601 format",
     *     example="2021-12-12T10:00:00+0000",
     *     nullable=true
     * )
     *
     * @var string
     */
    public $validTo;

    /**
     * @var Error[]
     */
    public $errors = [];

    /**
     * @SerializedName("isValid")
     *
     * @OA\Property(description="true if certificate is signed by authorative and conforms given documentType ruleset")
     */
    public function isValid(): bool
    {
        return empty($this->errors);
    }

    public function addError(int $code, string $message)
    {
        $this->errors[] = new Error($code, $message);
    }

    public function setValidFrom(DateTime $date)
    {
        $this->validFrom = $date->format(DateTimeInterface::ISO8601);
    }

    public function setValidTo(DateTime $date)
    {
        $this->validTo = $date->format(DateTimeInterface::ISO8601);
    }
}
