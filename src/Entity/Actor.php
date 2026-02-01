<?php

namespace App\Entity;

use App\Repository\ActorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActorRepository::class)]
class Actor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, BlogEntry>
     */
    #[ORM\ManyToMany(targetEntity: BlogEntry::class, mappedBy: 'actors')]
    private Collection $blogEntries;

    public function __construct()
    {
        $this->blogEntries = new ArrayCollection();
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

    /**
     * @return Collection<int, BlogEntry>
     */
    public function getBlogEntries(): Collection
    {
        return $this->blogEntries;
    }

    public function addBlogEntry(BlogEntry $blogEntry): static
    {
        if (!$this->blogEntries->contains($blogEntry)) {
            $this->blogEntries->add($blogEntry);
            $blogEntry->addActor($this);
        }

        return $this;
    }

    public function removeBlogEntry(BlogEntry $blogEntry): static
    {
        if ($this->blogEntries->removeElement($blogEntry)) {
            $blogEntry->removeActor($this);
        }

        return $this;
    }
}
