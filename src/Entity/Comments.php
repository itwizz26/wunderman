<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentsRepository::class)
 * @ORM\Table(name="comments")
 */
class Comments
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $commentId;

    /**
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json")
     */
    private $kids = [];

    public function getCommentId(): ?int
    {
        return $this->commentId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getKids()
    {
        if($this->kids == null) return array();
        $kids = unserialize($this->kids);

        return $kids;
    }

    public function setKids($kids): self
    {
        $this->kids = serialize($kids);
        return $this;
    }
}
