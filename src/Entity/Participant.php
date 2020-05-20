<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 * @UniqueEntity(fields={"mail"})
 */
class Participant implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(max="50")
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max="50")
     * @Assert\NotBlank()
     */
    private $mot_de_passe;

    /**
     * @ORM\Column(type="boolean")
     */
    private $administrateur;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="participants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="organisateur")
     */
    private $sortie_organisee;

    /**
     * @ORM\ManyToMany(targetEntity=Sortie::class, inversedBy="participants")
     */
    private $sortie_participee;

    public function __construct()
    {
        $this->sortie_organisee = new ArrayCollection();
        $this->sortie_participee = new ArrayCollection();
    }




    //Pas sauvegardé en base
    private $roles;











    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }



    public function getAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(bool $administrateur): self
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSortieOrganisee(): Collection
    {
        return $this->sortie_organisee;
    }

    public function addSortieOrganisee(Sortie $sortieOrganisee): self
    {
        if (!$this->sortie_organisee->contains($sortieOrganisee)) {
            $this->sortie_organisee[] = $sortieOrganisee;
            $sortieOrganisee->setOrganisateur($this);
        }

        return $this;
    }

    public function removeSortieOrganisee(Sortie $sortieOrganisee): self
    {
        if ($this->sortie_organisee->contains($sortieOrganisee)) {
            $this->sortie_organisee->removeElement($sortieOrganisee);
            // set the owning side to null (unless already changed)
            if ($sortieOrganisee->getOrganisateur() === $this) {
                $sortieOrganisee->setOrganisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSortieParticipee(): Collection
    {
        return $this->sortie_participee;
    }

    public function addSortieParticipee(Sortie $sortieParticipee): self
    {
        if (!$this->sortie_participee->contains($sortieParticipee)) {
            $this->sortie_participee[] = $sortieParticipee;
        }

        return $this;
    }

    public function removeSortieParticipee(Sortie $sortieParticipee): self
    {
        if ($this->sortie_participee->contains($sortieParticipee)) {
            $this->sortie_participee->removeElement($sortieParticipee);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->getMail();
    }

    /**
     * @param mixed $username
     */
    public function setUsername($mail): void
    {
        $this->mail = $mail;
    }

    /**
     * @return mixed
     */
    public function getMotDePasse()
    {
        return $this->mot_de_passe;
    }

    /**
     * @param mixed $mot_de_passe
     */
    public function setMotDePasse($mot_de_passe): void
    {
        $this->mot_de_passe = $mot_de_passe;
    }




    public function getPassword(): ?string
    {
        return $this->getMotDePasse();
    }

    public function setPassword(string $mot_de_passe): self
    {
        $this->mot_de_passe = $mot_de_passe;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return ["ROLE_USER"];
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles): void
    {
        $this->roles = $roles;
    }



    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }


}

