<?php
namespace App\UI\Http\Rest\Controller\Beer;

use App\Application\Beer\BeerIndexRequest;
use App\Application\Beer\BeerIndexService;
use App\Domain\Beer\Beer;
use App\UI\Http\Rest\Util\ApiResponseTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

class BeerIndexController extends AbstractController
{
    use ApiResponseTrait;

    private const CACHE_TTL = 3600;

    /**
     * @var BeerIndexService
     */
    private $beerIndexService;

    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(BeerIndexService $beerIndexService, CacheInterface $cache)
    {
        $this->beerIndexService = $beerIndexService;
        $this->cache = $cache;
    }

    /**
     * List the beers based on the specified food.
     *
     * @Route("/api/beers/food/{food}", methods={"GET"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns a list of beers based on the specified food",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Beer::class, groups={"full"}))
     *     )
     * )
     *
     * @OA\Parameter(
     *      name="limit",
     *      in="query",
     *      description="The maximum number of items to return per page",
     *      @OA\Schema(type="integer")
     *  )
     * @OA\Parameter(
     *      name="page",
     *      in="query",
     *      description="The page number for paginated results",
     *      @OA\Schema(type="integer")
     *  )
     *
     * @OA\Tag(name="beers")
     *
     * @param string $food
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index($food, Request $request): JsonResponse
    {
        if (empty($food)) {
            return $this->errorResponse('The "food" parameter is required.');
        }

        try {

            $params = $request->query->all();
            $params['food'] = $food;

            $cacheKey = md5(serialize($params));

            return $this->cache->get($cacheKey, function (ItemInterface $item) use ($request) {
                $item->expiresAfter(self::CACHE_TTL);

                $beersIndexRequest = new BeerIndexRequest(
                    $request->get('food'),
                    $request->query->get('page'),
                    $request->query->get('limit'),
                );

                $beersIndexResult = $this->beerIndexService->execute($beersIndexRequest);
                $beers = [];

                foreach ($beersIndexResult as $beerData) {

                    $beer = new Beer(
                        $beerData['id'],
                        $beerData['name'],
                        $beerData['tagline'],
                        $beerData['first_brewed'],
                        $beerData['description'],
                        $beerData['image_url'] ?? ''
                    );

                    $beers[] = $beer->toArray();
                }
                return $this->successResponse(['beers' => $beers]);

            });

        }  catch (\Exception $e) {
            return $this->errorResponse('Something went wrong.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}