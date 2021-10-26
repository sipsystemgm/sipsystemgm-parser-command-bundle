<?php

namespace Sip\ParserCommand\Entity;

use Sip\ParserCommand\Repository\SiteItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 *  @ORM\Table(name="site_item",
 *     indexes={
*           @ORM\Index(name="site_item_url_hash", columns={"url_hash"}),
 *          @ORM\Index(name="site_item_images_length", columns={"images_length"})
 *     },
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="url_hash",
 *            columns={"url_hash"})
 *    }
 * )
 * @ORM\Entity(repositoryClass=SiteItemRepository::class)
 */
class SiteItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $urlHash;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=4)
     */
    private $executionTime;

    /**
     * @ORM\Column(type="smallint")
     */
    private $deep;

    /**
     * @ORM\Column(type="integer")
     */
    private $imagesLength;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class, inversedBy="item", cascade={"all"})
     */
    private $site;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrlHash(): ?string
    {
        return $this->urlHash;
    }

    public function setUrlHash(string $urlHash): self
    {
        $this->urlHash = $urlHash;

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

    public function getExecutionTime(): ?string
    {
        return $this->executionTime;
    }

    public function setExecutionTime(string $executionTime): self
    {
        $this->executionTime = $executionTime;

        return $this;
    }

    public function getDeep(): ?int
    {
        return $this->deep;
    }

    public function setDeep(int $deep): self
    {
        $this->deep = $deep;

        return $this;
    }

    public function getImagesLength(): ?int
    {
        return $this->imagesLength;
    }

    public function setImagesLength(int $imagesLength): self
    {
        $this->imagesLength = $imagesLength;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }
}

