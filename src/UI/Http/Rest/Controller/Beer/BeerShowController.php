<?php
namespace App\UI\Http\Rest\Controller\Beer;

use App\Application\Beer\BeerShowRequest;
use App\Application\Beer\BeerShowService;
use App\Domain\Beer\Beer;
use App\UI\Http\Rest\Util\ApiResponseTrait;
use Assert\AssertionFailedException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

class BeerShowController extends AbstractController
{
    use ApiResponseTrait;

    private const CACHE_TTL = 3600;

    private $beerShowService;
    private $cache;

    public function __construct(BeerShowService $beerShowService, CacheInterface $cache)
    {
        $this->beerShowService = $beerShowService;
        $this->cache = $cache;
    }

    /**
     * Show details of a beer by ID.
     *
     * @Route("/api/beers/{id}", name="api.beers.show", methods={"GET"})
     * @OA\Tag(name="beers")
     *
     * @param int $id The ID of the beer.
     * @return JsonResponse
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns the details of a beer",
     *     @OA\JsonContent(ref=@Model(type=Beer::class, groups={"full"}))
     * )
     * @OA\Response(
     *     response=404,
     *     description="Beer not found"
     * )
     * @OA\Response(
     *     response=500,
     *     description="Internal server error"
     * )
     */
    public function show(int $id): JsonResponse
    {
        return $this->cache->get('beer_' . $id, function (ItemInterface $item) use ($id) {
            $item->expiresAfter(self::CACHE_TTL);

            try {
                $beersShowRequest = new BeerShowRequest($id);
                $beersShowResult = $this->beerShowService->execute($beersShowRequest);
                $beer = new Beer(
                    $beersShowResult['id'],
                    $beersShowResult['name'],
                    $beersShowResult['tagline'],
                    $beersShowResult['first_brewed'],
                    $beersShowResult['description'],
                    $beersShowResult['image_url'] ?? ''
                );

                return $this->successResponse(['beer' => $beer]);
            }
            catch (AssertionFailedException $e) {
                return $this->errorResponse($e->getMessage(),);
            } catch (NotFoundHttpException $e) {
                return $this->errorResponse('Beer not found.', Response::HTTP_NOT_FOUND);
            }
            catch (\Exception $e) {
                return $this->errorResponse('Something went wrong.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        });
    }
}