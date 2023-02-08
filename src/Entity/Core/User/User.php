<?php

namespace App\Entity\Core\User;

use App\Entity\Core\AccessGrantEnum;
use App\Entity\Core\Badge\BadgeUser;
use App\Entity\Core\Division\DivisionMember;
use App\Entity\Core\GenderEnum;
use App\Entity\Core\Notification\NotificationUser;
use App\Entity\Core\Role\Role;
use App\Entity\Core\Role\RoleUser;
use App\Entity\Modules\SecretSanta\Participant;
use App\Entity\Modules\Survey\SurveyUserAnonymous;
use App\Entity\Modules\Survey\Entry;
use App\Entity\Shop\Order\Order;
use App\Entity\Shop\OrderItem\OrderItem;
use App\Entity\Shop\RedemptionCode\RedemptionCode;
use App\Entity\Shop\WalletTransaction;
use App\Repository\Core\User\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity("username", message: "Ce pseudo est déjà utilisé")]
#[UniqueEntity("votingCode", message: "Ce code de vote est déjà utilisé")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    // Le champ email n'est pas renseigné comme unique, car quand l'utilisateur est "supprimé", il sera gardé dans la BDD avec un mail générique commun à tous les comptes "supprimés" : deleted@pictocraft.fr
    // TODO : il faudra donc développer une vérification du mail manuelle, ce qui permet d'ignorer ce mail générique
    #[ORM\Column(length: 180)]
    #[Assert\Email(message: "L'adresse mail renseignée est invalide")]
    #[Assert\NotBlank]
    private ?string $email = null;

    // Structure de $roles par défaut sur Symfony, à reprendre en cas de problème
    /*#[ORM\Column]
    private array $roles = [];*/

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: RoleUser::class, orphanRemoval: true, cascade: ["persist", "remove"])]
    private Collection $roles;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 32, unique: true)]
    #[Assert\Length(min: 3, max: 32, minMessage: "Le pseudo doit comporter au moins {{ limit }} caractères", maxMessage: "Le pseudo ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $username = null;

    #[ORM\Column(length: 32, nullable: true)]
    #[Assert\Length(max: 32, maxMessage: "Le prénom ne peut pas dépasser {{ limit }} caractères. Contacte le staff en cas de problème")]
    #[Assert\Regex("/[\w .'-]+/u")] // a-zàâçéèêëîïôûùüÿæœA-ZÀÂÇÉÈÊËÎÏÔÛÙÜŸÆŒ .'- // TODO : Vérifier si regex compatible HTML dans les formulaires (voir si attribut htmlPattern ou si changement de la regex nécessaire)
    private ?string $firstName = null;

    #[ORM\Column(length: 32, nullable: true)]
    #[Assert\Length(max: 32, maxMessage: "Le nom de famille ne peut pas dépasser {{ limit }} caractères. Contacte le staff en cas de problème")]
    #[Assert\Regex("/[\w .'-]+/u")] // a-zàâçéèêëîïôûùüÿæœA-ZÀÂÇÉÈÊËÎÏÔÛÙÜŸÆŒ .'- // TODO : Vérifier si regex compatible HTML dans les formulaires (voir si attribut htmlPattern ou si changement de la regex nécessaire)
    private ?string $lastName = null;

    #[ORM\Column(type: "gender_enum")]
    #[Assert\NotBlank]
    private ?GenderEnum $gender = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\Date]
    #[Assert\NotBlank]
    private ?DateTimeImmutable $birthday = null;

    #[ORM\Column(length: 64)]
    #[Assert\Length(max: 64, maxMessage: "Le texte ne peut pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $firstLogin = null;

    #[ORM\Column(options: ["default" => 0])]
    #[Assert\Range(min: 0, max: 4, minMessage: "Le nombre d'avertissements ne peut pas être négatif", maxMessage: "Le nombre d'avertissements ne peut pas dépasser {{ limit }}")]
    #[Assert\NotBlank]
    private ?int $warnings = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: DivisionMember::class, orphanRemoval: true)]
    private Collection $divisionRoles;

    #[ORM\Column(length: 10, unique: true)]
    #[Assert\Length(exactly: 10, exactMessage: "Le code de vote doit compter exactement {{ limit }} chiffres")]
    #[Assert\Regex('/1\d{9}/', message: "Le code de vote doit être un entier entre 1000000000 et 1999999999")]
    private ?string $votingCode = null;

    #[ORM\Column(options: ["default" => false])]
    private ?bool $secretSantaEligible = null;

    #[ORM\Column(options: ["default" => false])]
    private ?bool $christmasGiftEligible = null;

    #[ORM\Column(options: ["default" => false])]
    private ?bool $enabled = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Profile $profile = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Settings $settings = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Stats $stats = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Order::class)]
    private Collection $orders;

    #[ORM\OneToMany(mappedBy: 'giftedTo', targetEntity: OrderItem::class)]
    private Collection $giftedItems;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: RedemptionCode::class)]
    private Collection $redemptionCodes;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserSteamGame::class, orphanRemoval: true)]
    private Collection $steamGames;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Participant::class, orphanRemoval: true)]
    private Collection $secretSantaParticipations;

    // ------ Ces éléments sont utilisés dans le cas où un jeune mineur souhaite devenir membre. Il doit dans ce cas avoir un lien de parenté avec un autre membre actif qui en est responsable. ------ \\
    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'responsibleOfUsers')]
    private ?self $relativeUser = null;

    #[ORM\OneToMany(mappedBy: 'relativeUser', targetEntity: self::class)]
    private Collection $responsibleOfUsers;
    // --------------------------------- \\

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Entry::class)]
    private Collection $surveyEntries;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: SurveyUserAnonymous::class, orphanRemoval: true)]
    private Collection $surveysAnsweredAnonymously;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: WalletTransaction::class, orphanRemoval: true)]
    private Collection $walletTransactions;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Consents $userConsents = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: NotificationUser::class, orphanRemoval: true)]
    private Collection $notifications;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: BadgeUser::class, orphanRemoval: true)]
    private Collection $badges;

    // À remplir lorsque l'utilisateur est autorisé à accéder au serveur Discord, et donc à certaines fonctionnalités du site en étant connecté, en tant que visiteur
    #[ORM\Column(type: "access_grant_enum", nullable: true)]
    private $accessGrantedType = null;

    // Remplir si $accessGrantedBy == "member"
    #[ORM\ManyToOne(targetEntity: self::class)]
    private ?self $accessGrantedBy = null;

    public function __construct()
    {
        $this->warnings = 0;
        $this->secretSantaEligible = false;
        $this->christmasGiftEligible = false;
        $this->enabled = false;
        $this->orders = new ArrayCollection();
        $this->giftedItems = new ArrayCollection();
        $this->redemptionCodes = new ArrayCollection();
        $this->steamGames = new ArrayCollection();
        $this->divisionRoles = new ArrayCollection();
        $this->responsibleOfUsers = new ArrayCollection();
        $this->roles_disabled = new ArrayCollection();
        $this->roles = new ArrayCollection();
        $this->surveyEntries = new ArrayCollection();
        $this->walletTransactions = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->badges = new ArrayCollection();
        $this->secretSantaParticipations = new ArrayCollection();
        $this->surveysAnsweredAnonymously = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    // Méthode décrite par UserInterface, ne peut renvoyer que les rôles sous forme de tableau de string. Pour obtenir les entités, utiliser getFullRoles() qui est une méthode personnalisée
    /**
     * @see UserInterface
     */
    /** @return string[] */
    public function getRoles(): array
    {
        /** @var Collection<int, Role> $fullRoles */
        /** @var RoleUser $fullRole */
        $fullRoles = $this->getFullRoles()->map(fn($fullRole) => $fullRole->getRole());

        /** @var string[] $roles */
        /** @var Role $role */
        $roles = $fullRoles->map(fn($role) => $role->getInternalName())->getValues();

        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @return Collection<int, RoleUser>
     */
    public function getFullRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(RoleUser $roleUser): self
    {
        if (!$this->roles->contains($roleUser)) {
            $this->roles->add($roleUser);
            $roleUser->setUser($this);
        }

        return $this;
    }

    public function removeRole(RoleUser $roleUser): self
    {
        if ($this->roles->removeElement($roleUser)) {
            // set the owning side to null (unless already changed)
            if ($roleUser->getUser() === $this) {
                $roleUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getGender(): ?GenderEnum
    {
        return $this->gender;
    }

    public function setGender(GenderEnum $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getBirthday(): ?DateTimeImmutable
    {
        return $this->birthday;
    }

    public function setBirthday(DateTimeImmutable $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getFirstLogin(): ?string
    {
        return $this->firstLogin;
    }

    public function setFirstLogin(string $firstLogin): self
    {
        $this->firstLogin = $firstLogin;

        return $this;
    }

    public function getWarnings(): ?int
    {
        return $this->warnings;
    }

    public function setWarnings(int $warnings): self
    {
        $this->warnings = $warnings;

        return $this;
    }

    /**
     * @return Collection<int, DivisionMember>
     */
    public function getDivisionRoles(): Collection
    {
        return $this->divisionRoles;
    }

    public function addDivisionRole(DivisionMember $divisionRole): self
    {
        if (!$this->divisionRoles->contains($divisionRole)) {
            $this->divisionRoles->add($divisionRole);
            $divisionRole->setUser($this);
        }

        return $this;
    }

    public function removeDivisionRole(DivisionMember $divisionRole): self
    {
        if ($this->divisionRoles->removeElement($divisionRole)) {
            // set the owning side to null (unless already changed)
            if ($divisionRole->getUser() === $this) {
                $divisionRole->setUser(null);
            }
        }

        return $this;
    }

    public function getVotingCode(): ?int
    {
        return $this->votingCode;
    }

    public function setVotingCode(int $votingCode): self
    {
        $this->votingCode = $votingCode;

        return $this;
    }

    public function getShopBalance(): ?int
    {
        // TODO : faire la récupération du solde de boutique en prenant le dernier enregistrement de l'utilisateur dans WalletTransaction, sinon vaut 0.
        return 0;
    }

    public function isSecretSantaEligible(): ?bool
    {
        return $this->secretSantaEligible;
    }

    public function setSecretSantaEligible(bool $secretSantaEligible): self
    {
        $this->secretSantaEligible = $secretSantaEligible;

        return $this;
    }

    public function isChristmasGiftEligible(): ?bool
    {
        return $this->christmasGiftEligible;
    }

    public function setChristmasGiftEligible(bool $christmasGiftEligible): self
    {
        $this->christmasGiftEligible = $christmasGiftEligible;

        return $this;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(Profile $profile): self
    {
        // set the owning side of the relation if necessary
        if ($profile->getUser() !== $this) {
            $profile->setUser($this);
        }

        $this->profile = $profile;

        return $this;
    }

    public function getSettings(): ?Settings
    {
        return $this->settings;
    }

    public function setSettings(Settings $settings): self
    {
        // set the owning side of the relation if necessary
        if ($settings->getUser() !== $this) {
            $settings->setUser($this);
        }

        $this->settings = $settings;

        return $this;
    }

    public function getStats(): ?Stats
    {
        return $this->stats;
    }

    public function setStats(Stats $stats): self
    {
        // set the owning side of the relation if necessary
        if ($stats->getUser() !== $this) {
            $stats->setUser($this);
        }

        $this->stats = $stats;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getGiftedItems(): Collection
    {
        return $this->giftedItems;
    }

    /**
     * @return Collection<int, RedemptionCode>
     */
    public function getRedemptionCodes(): Collection
    {
        return $this->redemptionCodes;
    }

    public function addRedemptionCode(RedemptionCode $redemptionCode): self
    {
        if (!$this->redemptionCodes->contains($redemptionCode)) {
            $this->redemptionCodes->add($redemptionCode);
            $redemptionCode->setUser($this);
        }

        return $this;
    }

    public function removeRedemptionCode(RedemptionCode $redemptionCode): self
    {
        if ($this->redemptionCodes->removeElement($redemptionCode)) {
            // set the owning side to null (unless already changed)
            if ($redemptionCode->getUser() === $this) {
                $redemptionCode->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserSteamGame>
     */
    public function getSteamGames(): Collection
    {
        return $this->steamGames;
    }

    public function addSteamGame(UserSteamGame $steamGame): self
    {
        if (!$this->steamGames->contains($steamGame)) {
            $this->steamGames->add($steamGame);
            $steamGame->setUser($this);
        }

        return $this;
    }

    public function removeSteamGame(UserSteamGame $steamGame): self
    {
        if ($this->steamGames->removeElement($steamGame)) {
            // set the owning side to null (unless already changed)
            if ($steamGame->getUser() === $this) {
                $steamGame->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getSecretSantaParticipations(): Collection
    {
        return $this->secretSantaParticipations;
    }

    public function addSecretSantaParticipation(Participant $secretSantaParticipation): self
    {
        if (!$this->secretSantaParticipations->contains($secretSantaParticipation)) {
            $this->secretSantaParticipations->add($secretSantaParticipation);
            $secretSantaParticipation->setUser($this);
        }

        return $this;
    }

    public function removeSecretSantaParticipation(Participant $secretSantaParticipation): self
    {
        if ($this->secretSantaParticipations->removeElement($secretSantaParticipation)) {
            // set the owning side to null (unless already changed)
            if ($secretSantaParticipation->getUser() === $this) {
                $secretSantaParticipation->setUser(null);
            }
        }

        return $this;
    }

    // ------ Ces éléments sont utilisés dans le cas où un jeune mineur souhaite devenir membre. Il doit dans ce cas avoir un lien de parenté avec un autre membre actif qui en est responsable. ------ \\
    public function getRelativeUser(): ?self
    {
        return $this->relativeUser;
    }

    public function setRelativeUser(?self $relativeUser): self
    {
        $this->relativeUser = $relativeUser;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getResponsibleOfUsers(): Collection
    {
        return $this->responsibleOfUsers;
    }

    public function addResponsibleOfUser(self $responsibleOfUser): self
    {
        if (!$this->responsibleOfUsers->contains($responsibleOfUser)) {
            $this->responsibleOfUsers->add($responsibleOfUser);
            $responsibleOfUser->setRelativeUser($this);
        }

        return $this;
    }

    public function removeResponsibleOfUser(self $responsibleOfUser): self
    {
        if ($this->responsibleOfUsers->removeElement($responsibleOfUser)) {
            // set the owning side to null (unless already changed)
            if ($responsibleOfUser->getRelativeUser() === $this) {
                $responsibleOfUser->setRelativeUser(null);
            }
        }

        return $this;
    }
    // --------------------------------- \\

    /**
     * @return Collection<int, Entry>
     */
    public function getSurveyEntries(): Collection
    {
        return $this->surveyEntries;
    }

    // TODO : voir si méthode à supprimer, pour forcer la gestion d'Entry via Survey
    public function addSurveyEntry(Entry $surveyEntry): self
    {
        if (!$this->surveyEntries->contains($surveyEntry)) {
            $this->surveyEntries->add($surveyEntry);
            $surveyEntry->setUser($this);
        }

        return $this;
    }

    // TODO : voir si méthode à supprimer, pour forcer la gestion d'Entry via Survey
    public function removeSurveyEntry(Entry $surveyEntry): self
    {
        if ($this->surveyEntries->removeElement($surveyEntry)) {
            // set the owning side to null (unless already changed)
            if ($surveyEntry->getUser() === $this) {
                $surveyEntry->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SurveyUserAnonymous>
     */
    public function getSurveysAnsweredAnonymously(): Collection
    {
        return $this->surveysAnsweredAnonymously;
    }

    public function addSurveyAnsweredAnonymously(SurveyUserAnonymous $anonymousSurveyEntry): self
    {
        if (!$this->surveysAnsweredAnonymously->contains($anonymousSurveyEntry)) {
            $this->surveysAnsweredAnonymously->add($anonymousSurveyEntry);
            $anonymousSurveyEntry->setUser($this);
        }

        return $this;
    }

    public function removeSurveyAnsweredAnonymously(SurveyUserAnonymous $anonymousSurveyEntry): self
    {
        if ($this->surveysAnsweredAnonymously->removeElement($anonymousSurveyEntry)) {
            // set the owning side to null (unless already changed)
            if ($anonymousSurveyEntry->getUser() === $this) {
                $anonymousSurveyEntry->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, WalletTransaction>
     */
    public function getWalletTransactions(): Collection
    {
        return $this->walletTransactions;
    }

    public function addWalletTransaction(WalletTransaction $walletTransaction): self
    {
        if (!$this->walletTransactions->contains($walletTransaction)) {
            $this->walletTransactions->add($walletTransaction);
            $walletTransaction->setUser($this);
        }

        return $this;
    }

    public function removeWalletTransaction(WalletTransaction $walletTransaction): self
    {
        if ($this->walletTransactions->removeElement($walletTransaction)) {
            // set the owning side to null (unless already changed)
            if ($walletTransaction->getUser() === $this) {
                $walletTransaction->setUser(null);
            }
        }

        return $this;
    }

    public function getUserConsents(): ?Consents
    {
        return $this->userConsents;
    }

    public function setUserConsents(Consents $userConsents): self
    {
        // set the owning side of the relation if necessary
        if ($userConsents->getUser() !== $this) {
            $userConsents->setUser($this);
        }

        $this->userConsents = $userConsents;

        return $this;
    }

    /**
     * @return Collection<int, NotificationUser>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(NotificationUser $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(NotificationUser $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BadgeUser>
     */
    public function getBadges(): Collection
    {
        return $this->badges;
    }

    public function addBadge(BadgeUser $badge): self
    {
        if (!$this->badges->contains($badge)) {
            $this->badges->add($badge);
            $badge->setUser($this);
        }

        return $this;
    }

    public function removeBadge(BadgeUser $badge): self
    {
        if ($this->badges->removeElement($badge)) {
            // set the owning side to null (unless already changed)
            if ($badge->getUser() === $this) {
                $badge->setUser(null);
            }
        }

        return $this;
    }

    public function getAccessGrantedType(): ?AccessGrantEnum
    {
        return $this->accessGrantedType;
    }

    public function setAccessGrantedType(AccessGrantEnum $accessGrantedType): self
    {
        $this->accessGrantedType = $accessGrantedType;

        return $this;
    }

    public function getAccessGrantedBy(): ?self
    {
        return $this->accessGrantedBy;
    }

    public function setAccessGrantedBy(?self $accessGrantedBy): self
    {
        $this->accessGrantedBy = $accessGrantedBy;

        return $this;
    }
}
