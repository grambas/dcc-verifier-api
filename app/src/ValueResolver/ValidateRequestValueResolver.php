<?php

declare(strict_types=1);

namespace App\ValueResolver;

use App\Dto\Request\Validate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

class ValidateRequestValueResolver implements ArgumentValueResolverInterface
{
    protected $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return Validate::class === $argument->getType() && 'POST' === $request->getMethod();
    }

    /**
     * @return iterable<Validate>
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        try {
            $request = $this->serializer->deserialize(
                $request->getContent(),
                Validate::class,
                'json'
            );
        } catch (Throwable $exception) {
            $request = null;
        }

        yield $request;
    }
}
