<?php

declare(strict_types=1);

namespace App\Tool\ArgumentResolving;

use App\Tool\ExceptionHandling\RequestParsingError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestDTOResolver implements ValueResolverInterface
{
    private ValidatorInterface $validator;

    private SerializerInterface $serializer;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
        $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonDecode()]);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $type = $argument->getType();

        if (null === $type) {
            return [];
        }

        if (!is_subclass_of($type, RequestDTO::class)) {
            return [];
        }

        $dto = $this->deserializeRequestToDto($request, $type);

        yield $dto;
    }

    /**
     * @param class-string<RequestDTO> $type
     * @throws RequestParsingError
     */
    private function deserializeRequestToDto(Request $request, string $type): RequestDTO
    {
        try {
            $dto = $this->serializer->deserialize($request->getContent(), $type, 'json');
        } catch (\Exception $e) {
            throw new RequestParsingError('Data deserialization error: '.$e->getMessage(), $e);
        }

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMsg = '';
            foreach ($errors as $error) {
                $errorMsg .= $error->getMessage()."\n";
            }
            throw new RequestParsingError('Request validation error: '.$errorMsg);
        }

        return $dto;
    }
}
