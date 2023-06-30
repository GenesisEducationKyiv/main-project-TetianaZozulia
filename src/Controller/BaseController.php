<?php declare(strict_types=1);

namespace App\Controller;

use App\Model\ResourceModel\ResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    public function __construct(
        protected SerializerInterface $serializer
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
}
