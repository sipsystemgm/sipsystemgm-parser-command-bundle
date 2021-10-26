<?php

namespace Sip\ParserCommand\Entity;

use Sip\ParserCommand\Repository\SiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 *  @ORM\Table(name="site",
 *     indexes={
 *          @ORM\Index(name="site_url", columns={"url"})
 *    },
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="url",
 *            columns={"url"})
 *    }
 * )
 * @ORM\Entity(repositoryClass=SiteRepository::class)
 */
class Site
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\OneToMany(targetEntity=SiteItem::class, mappedBy="site", cascade={"all"})
     */
    private $item;

    public function __construct()
    {
        $this->item = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection|SiteItem[]
     */
    public function getItem(): Collection
    {
        return $this->item;
    }

    public function addItem(SiteItem $item): self
    {
        if (!$this->item->contains($item)) {
            $this->item[] = $item;
            $item->setSite($this);
        }

        return $this;
    }

    public function removeItem(SiteItem $item): self
    {
        if ($this->item->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getSite() === $this) {
                $item->setSite(null);
            }
        }

        return $this;
    }
}

