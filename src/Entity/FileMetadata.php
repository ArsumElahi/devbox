<?php

namespace App\Entity;

use App\Repository\FileMetadataRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FileMetadataRepository::class)
 */
class FileMetadata
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $file_name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_rows;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $error_line_no;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    public function setFileName(?string $file_name): self
    {
        $this->file_name = $file_name;

        return $this;
    }

    public function getTotalRows(): ?int
    {
        return $this->total_rows;
    }

    public function setTotalRows(?int $total_rows): self
    {
        $this->total_rows = $total_rows;

        return $this;
    }

    public function getErrorLineNo(): ?int
    {
        return $this->error_line_no;
    }

    public function setErrorLineNo(?int $error_line_no): self
    {
        $this->error_line_no = $error_line_no;

        return $this;
    }
}
