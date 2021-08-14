<?php


namespace App\Controller;


use App\Document\Company;
use App\Message\TestMessage;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(MessageBusInterface $bus): Response
    {
        $number = random_int(0, 100);

        $this->dispatchMessage(new TestMessage('Look! I created a message! Lucky number: '.$number), [new DelayStamp(5000)]);
        
        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }

    /**
     * @Route("/company", name="company_list", methods={"GET"})
     */
    public function listCompany(Request $request, DocumentManager $dm)
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $name = $request->query->has('name') ? strtolower($request->query->get('name')) : '';
        $company = $dm->getRepository(Company::class)->findBy(['name' => new \MongoDB\BSON\Regex("^$name", 'i') ]);


        if (! $company) {
            //throw $this->createNotFoundException('No company found');
            throw new NotFoundHttpException('No company found');
        }

        return JsonResponse::fromJsonString($serializer->serialize($company, 'json'));
    }

    /**
     * @Route("/create", name="company_create", methods={"POST"})
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function createCompany(Request $request, DocumentManager $dm, SerializerInterface $serializer)
    {
        $this->isValidJson($request);

        $company = $serializer->deserialize($request->getContent(), Company::class, 'json');
        $dm->persist($company);
        $dm->flush();

        return JsonResponse::fromJsonString($serializer->serialize($company, 'json'));
    }

    /**
     * Validate if $request content-type is json and syntax is correct
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    private function isValidJson(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new HttpException(400, 'Invalid json');
        }

        if ('application/json' !== $request->headers->get('content-type')) {
            throw new HttpException(415, 'Invalid content-type. Accepted only application/json');
        }
    }
}