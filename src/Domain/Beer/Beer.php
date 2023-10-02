<?php
namespace App\Domain\Beer;

use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     description="Beer object",
 *     title="Beer",
 *     @OA\Property(property="id", type="integer", description="The unique identifier for the beer."),
 *     @OA\Property(property="name", type="string", description="The name of the beer."),
 *     @OA\Property(property="tagline", type="string", description="The tagline or slogan of the beer."),
 *     @OA\Property(property="first_brewed", type="string", description="The date when the beer was first brewed."),
 *     @OA\Property(property="description", type="string", description="A description of the beer."),
 *     @OA\Property(property="image_url", type="string", description="The URL of an image representing the beer."),
 * )
 */
class Beer
{

    private $id;

    private $name;

    private $tagline;

    private $firstBrewed;

    private string $description;

    private $image;


    public function __construct(
        int $id,
        string $name,
        string $tagline,
        string $firstBrewed,
        string $description,
        string $image
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->tagline = $tagline;
        $this->firstBrewed = $firstBrewed;
        $this->description = $description;
        $this->image = $image;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTagline(): string
    {
        return $this->tagline;
    }

    public function getFirstBrewed(): string
    {
        return $this->firstBrewed;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'tagline' => $this->tagline,
            'first_brewed' => $this->firstBrewed,
            'description' => $this->description,
            'image_url' => $this->image,
        ];
    }
}