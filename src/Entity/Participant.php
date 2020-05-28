<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 * @UniqueEntity(fields={"mail"})
 * @Vich\Uploadable()
 */
class Participant implements UserInterface, \Serializable
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
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $pseudo;

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
     * @Vich\UploadableField(mapping="participant_avatar", fileNameProperty="avatar", size="avatarSize")
     * @Assert\Image(
     *    maxSize="1024k",
     *     maxSizeMessage="Fichier trop volumineux (maximum 1024k)",
     *     mimeTypes={"image/png", "image/jpg", "image/jpeg"},
     *     mimeTypesMessage="Format de fichier non autorisé (uniquement png, jpg, jpeg)" ,
     *
     * )
     */
    private $avatarFile;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $avatarSize;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $updateVersion;

    /**
     * @return mixed
     */
    public function getAvatarFile()
    {
        return $this->avatarFile;
    }

    /**
     * @param mixed $avatarFile
     */
    public function setAvatarFile(File $avatarFile): void
    {
        $this->avatarFile = $avatarFile;
        if (null != $avatarFile){
            $this->setUpdateVersion($this->updateVersion + 1);
        }
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @return mixed
     */
    public function getAvatarSize()
    {
        return $this->avatarSize;
    }

    /**
     * @param mixed $avatarSize
     */
    public function setAvatarSize($avatarSize): void
    {
        $this->avatarSize = $avatarSize;
    }

    /**
     * @return mixed
     */
    public function getUpdateVersion()
    {
        return $this->updateVersion;
    }

    /**
     * @param mixed $updateVersion
     */
    public function setUpdateVersion($updateVersion): void
    {
        $this->updateVersion = $updateVersion;
    }

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

    //Pas sauvegardé en base
    private $roles;

    public function __construct()
    {
        $this->sortie_organisee = new ArrayCollection();
        $this->sortie_participee = new ArrayCollection();
    }

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

    /**
     * @return mixed
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * @param mixed $pseudo
     */
    public function setPseudo($pseudo): void
    {
        $this->pseudo = $pseudo;
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
        return $this->getPseudo();
    }

    /**
     * @param mixed $username
     */
    public function setUsername(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
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
        if ($this->administrateur == 0)
        {
            return ["ROLE_USER"];
        }
        elseif ($this->administrateur == 1)
        {
            return ["ROLE_ADMIN"];
        }
        return $this->roles;
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

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->pseudo,
            $this->mot_de_passe
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->pseudo,
            $this->mot_de_passe,
            ) = unserialize($serialized, array('allowed_classes' => false));
    }
}
