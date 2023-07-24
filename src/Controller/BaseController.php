<?php

declare(strict_types=1);

namespace App\Controller;

use App\Map\MapperInterface;
use App\Model\ResourceModel\ResourceInterface;
use App\Serializer\JsonSerializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    public function __construct(
        protected SerializerInterface $serializer,
        protected JsonSerializer $jsonSerializer
    ) {
    }

    protected function parseBody(Request $request, string $type): ResourceInterface
    {
        try {
            $resource = $this->serializer->deserialize($request->getContent(), $type, 'json', [
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
            ]);
        } catch (MissingConstructorArgumentsException $exception) {
            throw new BadRequestHttpException('Invalid request. ' . $exception->getMessage());
        }

        return $resource;
    }

    protected function parseQuery(Request $request, MapperInterface $mapper): ?ResourceInterface
    {
        $params = array_merge(
            $request->query->getIterator()->getArrayCopy(),
            $this->jsonSerializer->deserialize($request->getContent()) ?? []
        );
        try {
            $resource = $mapper->fromArray($params);
        } catch (\Exception $exception) {
            if (strpos($exception->getMessage(), 'Undefined array key')) {
                return null;
            }
            throw $exception;
        }

        return $resource;
    }
}
