<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

use App\Enum\RecipeStatus;
use App\Repository\RecipeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(
        message: 'Le nom de la recette doit être renseigné.',
        normalizer: 'trim',
    )]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: 'Le nom de la recette doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Le nom de la recette ne peut pas dépasser {{ limit }} caractères.',
        normalizer: 'trim',
    )]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Assert\NotNull(message: 'L\'auteur de la recette doit être renseigné.')]
    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[Assert\NotNull(message: 'Le nombre de portions doit être renseigné.')]
    #[Assert\Positive(message: 'Le nombre de portions doit être un nombre positif.')]
    #[ORM\Column(nullable: false)]
    private ?int $servings = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Assert\NotNull(message: 'Le temps de préparation doit être renseigné.')]
    #[Assert\PositiveOrZero(message: 'Le temps de préparation doit être 0 ou un nombre positif.')]
    #[ORM\Column(nullable: false)]
    private ?int $preparation_time = null;

    #[Assert\NotNull(message: 'Le temps de cuisson doit être renseigné.')]
    #[Assert\PositiveOrZero(message: 'Le temps de cuisson doit être 0 ou un nombre positif.')]
    #[ORM\Column(nullable: false)]
    private ?int $cooking_time = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(enumType: RecipeStatus::class)]
    private ?RecipeStatus $status = RecipeStatus::PUBLISHED;

    /**
     * @var Collection<int, RecipeIngredient>
     */
    #[Assert\Valid]
    #[ORM\OneToMany(targetEntity: RecipeIngredient::class, mappedBy: 'recipe', orphanRemoval: true, cascade: ['persist'])]
    private Collection $recipeIngredients;

    /**
     * @var Collection<int, Keywords>
     */
    #[ORM\ManyToMany(targetEntity: Keywords::class, inversedBy: 'recipe')]
    private Collection $keywords;

    public function __construct()
    {
        $this->recipeIngredients = new ArrayCollection();
        $this->keywords = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $now = new \DateTimeImmutable();

        $this->created_at = $now;
        $this->updated_at = $now;
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updated_at = new \DateTimeImmutable();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getServings(): ?int
    {
        return $this->servings;
    }

    public function setServings(?int $servings): static
    {
        $this->servings = $servings;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPreparationTime(): ?int
    {
        return $this->preparation_time;
    }

    public function setPreparationTime(?int $preparation_time): static
    {
        $this->preparation_time = $preparation_time;

        return $this;
    }

    public function getCookingTime(): ?int
    {
        return $this->cooking_time;
    }

    public function setCookingTime(?int $cooking_time): static
    {
        $this->cooking_time = $cooking_time;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return RecipeStatus|null
     */
    public function getStatus(): ?RecipeStatus
    {
        return $this->status;
    }

    public function setStatus(?RecipeStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, RecipeIngredient>
     */
    public function getRecipeIngredients(): Collection
    {
        return $this->recipeIngredients;
    }

    public function addRecipeIngredient(RecipeIngredient $recipeIngredient): static
    {
        if (!$this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients->add($recipeIngredient);
            $recipeIngredient->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredient $recipeIngredient): static
    {
        if ($this->recipeIngredients->removeElement($recipeIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeIngredient->getRecipe() === $this) {
                $recipeIngredient->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Keywords>
     */
    public function getKeywords(): Collection
    {
        return $this->keywords;
    }

    public function addKeyword(Keywords $keyword): static
    {
        if (!$this->keywords->contains($keyword)) {
            $this->keywords->add($keyword);
            $keyword->addRecipe($this);
        }

        return $this;
    }

    public function removeKeyword(Keywords $keyword): static
    {
        if ($this->keywords->removeElement($keyword)) {
            $keyword->removeRecipe($this);
        }

        return $this;
    }
}
