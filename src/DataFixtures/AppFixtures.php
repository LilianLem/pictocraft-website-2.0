<?php

namespace App\DataFixtures;

use App\Entity\Core\GenderEnum;
use App\Entity\Core\Role\Role;
use App\Entity\Core\Role\RoleUser;
use App\Entity\Core\User\Consents;
use App\Entity\Core\User\Profile;
use App\Entity\Core\User\Settings;
use App\Entity\Core\User\Stats;
use App\Entity\Core\User\User;
use App\Entity\External\Geo\Country;
use App\Entity\External\Geo\France\CommunePostalData;
use App\Entity\External\Geo\France\Departement;
use App\Entity\External\Vat\VatRate;
use App\Entity\Shop\Attribute\Attribute;
use App\Entity\Shop\Attribute\Value;
use App\Entity\Shop\Category;
use App\Entity\Shop\Delivery\Delivery;
use App\Entity\Shop\Delivery\TypeEnum as DeliveryTypeEnum;
use App\Entity\Shop\Order\Order;
use App\Entity\Shop\Order\Status as OrderStatus;
use App\Entity\Shop\Order\StatusEnum as OrderStatusEnum;
use App\Entity\Shop\OrderItem\OrderItem;
use App\Entity\Shop\OrderItem\Status as OrderItemStatus;
use App\Entity\Shop\OrderItem\StatusEnum as OrderItemStatusEnum;
use App\Entity\Shop\Payment\Payment;
use App\Entity\Shop\Payment\PaymentMethod;
use App\Entity\Shop\Payment\PaymentMethodTypeEnum;
use App\Entity\Shop\Product;
use App\Entity\Shop\ProductCategory;
use App\Repository\External\Geo\CountryRepository;
use App\Repository\External\Geo\France\CommunePostalDataRepository;
use App\Repository\External\Geo\France\DepartementRepository;
use App\Repository\External\Vat\VatRateRepository;
use Bezhanov\Faker\Provider\Avatar;
use Bezhanov\Faker\Provider\Commerce;
use Bluemmb\Faker\PicsumPhotosProvider;
use Carbon\Carbon;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private LoggerInterface $logger;
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;
    private CountryRepository $countryRepository;
    private CommunePostalDataRepository $communePostalDataRepository;

    /** @var Departement[] $departements */
    private readonly array $departements;

    private readonly Country $france;
    private readonly int $highestCommunePostalDataId;

    /** @var string[] $addressLineBuildingInsideCollection */
    private readonly array $addressLineBuildingInsideCollection;

    /** @var string[] $addressLineBuildingOutsideCollection */
    private readonly array $addressLineBuildingOutsideCollection;

    /** @var VatRate[] $vatRates */
    private readonly array $vatRates;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $em, CountryRepository $countryRepository, CommunePostalDataRepository $communePostalDataRepository, DepartementRepository $departementRepository, VatRateRepository $vatRateRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
        $this->countryRepository = $countryRepository;
        $this->communePostalDataRepository = $communePostalDataRepository;
        $this->departements = $departementRepository->findAll();
        $this->france = $this->countryRepository->findOneBy(["isoCode_alpha2" => "FR"]);
        $this->addressLineBuildingInsideCollection = ["RDC", "1er étage", "2ème étage", "3ème étage"];
        $this->addressLineBuildingOutsideCollection = ["Bâtiment A", "Bâtiment B", "Bâtiment C", "Lotissement A", "Lotissement B", "Lotissement C"];
        $this->vatRates = $vatRateRepository->findAll();
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");
        $faker->addProvider(new Commerce($faker));
        $faker->addProvider(new Avatar($faker));
        $faker->addProvider(new PicsumPhotosProvider($faker));

        $this->highestCommunePostalDataId = $this->communePostalDataRepository->getMaxId();

        // ===== CORE ===== \\

        // ----- Role ----- \\

        /** @var Role[] $roles */
        $roles = [];

        $role_user = new Role();
        $role_user->setName("Utilisateur")
            ->setVisible(true)
            ->setDisplayedOnProfile(true)
            ->setProfileDisplayRow(2)
            ->setProfileDisplayPriority(-1)
            ->setColor("95a5a6")
            ->setInternalName("ROLE_USER");
        $roles[] = $role_user;

        $role_member = new Role();
        $role_member->setName("Membre")
            ->setVisible(true)
            ->setDisplayedOnProfile(true)
            ->setProfileDisplayRow(2)
            ->setProfileDisplayPriority(0)
            ->setColor("2ecc71")
            ->setInternalName("ROLE_MEMBER")
            ->setParent($role_user);
        $roles[] = $role_member;

        $role_vip = new Role();
        $role_vip->setName("VIP")
            ->setVisible(true)
            ->setDisplayedOnProfile(true)
            ->setProfileDisplayRow(2)
            ->setProfileDisplayPriority(1)
            ->setColor("f1c40f")
            ->setInternalName("ROLE_VIP");
        $roles[] = $role_vip;

        $role_vip_plus = new Role();
        $role_vip_plus->setName("VIP+")
            ->setVisible(true)
            ->setDisplayedOnProfile(true)
            ->setProfileDisplayRow(2)
            ->setProfileDisplayPriority(2)
            ->setColor("e67e22")
            ->setInternalName("ROLE_VIP_PLUS")
            ->setParent($role_vip);
        $roles[] = $role_vip_plus;

        $role_beta_tester = new Role();
        $role_beta_tester->setName("Bêta-testeur")
            ->setVisible(true)
            ->setColor("1abc9c")
            ->setInternalName("ROLE_BETA");
        $roles[] = $role_beta_tester;

        $role_builder = new Role();
        $role_builder->setName("Builder")
            ->setVisible(true)
            ->setColor("97f510")
            ->setInternalName("ROLE_MINECRAFT_BUILDER");
        $roles[] = $role_builder;

        $role_moderator = new Role();
        $role_moderator->setName("Modérateur")
            ->setVisible(true)
            ->setDisplayedOnProfile(true)
            ->setProfileDisplayRow(1)
            ->setProfileDisplayPriority(0)
            ->setColor("9b59b6")
            ->setInternalName("ROLE_DISCORD_ASSISTANT");
        $roles[] = $role_moderator;

        $role_admin = new Role();
        $role_admin->setName("Admin")
            ->setVisible(true)
            ->setDisplayedOnProfile(true)
            ->setProfileDisplayRow(1)
            ->setProfileDisplayPriority(1)
            ->setColor("e74c3c")
            ->setInternalName("ROLE_DISCORD_MANAGER")
            ->setParent($role_moderator);
        $roles[] = $role_admin;

        $role_founder = new Role();
        $role_founder->setName("Fondateur")
            ->setVisible(true)
            ->setDisplayedOnProfile(true)
            ->setProfileDisplayRow(1)
            ->setProfileDisplayPriority(10)
            ->setColor("992d22")
            ->setInternalName("ROLE_DISCORD_OFFICER")
            ->setParent($role_admin);
        $roles[] = $role_founder;

        $role_mc_admin = new Role();
        $role_mc_admin->setName("Admin")
            ->setVisible(true)
            ->setInternalName("ROLE_MINECRAFT_MANAGER");
        $roles[] = $role_mc_admin;

        $role_mc_founder = new Role();
        $role_mc_founder->setName("Fondateur")
            ->setVisible(true)
            ->setInternalName("ROLE_MINECRAFT_OFFICER")
            ->setParent($role_mc_admin);
        $roles[] = $role_mc_founder;

        $role_resident = new Role();
        $role_resident->setName("Résident")
            ->setVisible(true)
            ->setColor("11806a")
            ->setInternalName("ROLE_MINECRAFT_RESIDENT");
        $roles[] = $role_resident;

        foreach($roles as $role) {
            $manager->persist($role);
        }

        // ----- User ----- \\

        $usernameTemplates = ["pierre_durand","p_durand","pdurand","pierre61","pdurand61","randomstring61","randomstring"];
        $emailTemplates = ["pierre.durand","p.durand","pdurand","durand.pierre","pierre.durand61","pierredurand61","pierre61420"];
        /** @var string[] $generatedUsernames */
        $generatedUsernames = [];
        /** @var string[] $generatedEmails */
        $generatedEmails = [];

        $specialRolesCount = ["ROLE_DISCORD_ASSISTANT" => 0,"ROLE_MINECRAFT_MANAGER" => 0];

        /** @var User[] $users */
        $users = [];
        for($u = 1; $u <= 50; $u++) {
            $user = new User();

            $user->setLastName($faker->lastName())
                ->setGender($faker->boolean() ? GenderEnum::FEMALE : GenderEnum::MALE);

            if($user->getGender() === GenderEnum::FEMALE) {
                $user->setFirstName($faker->firstNameFemale());
            } else {
                $user->setFirstName($faker->firstNameMale());
            }

            $user->setPassword($this->passwordHasher->hashPassword($user, "RandomUser00"))
                ->setBirthday(DateTimeImmutable::createFromMutable($faker->dateTimeBetween("-30 years", "-13 years")->setTime(0, 0)))
                ->setVotingCode(mt_rand(1000000000, 1999999999))
                ->setFirstLogin($faker->dateTimeBetween("-5 years", "-15 days")->format("Y-m-d"))
                ->setEnabled($faker->boolean(70))
                ->setWarnings($faker->boolean(70) ? 0 : ($faker->boolean(40) ? 1 : mt_rand(2, 4)))
                ->addRole((new RoleUser())->setUser($user)->setRole($role_user));

            // TODO: modifier la génération des consentements pour qu'elle corresponde davantage à une réalité de prod (en fonction des autres éléments de l'utilisateur, surtout s'il a renseigné ou non les éléments concernés ou s'il est membre)
            $userConsents = new Consents();
            $userConsents->setDiscordAccountUsage(true)
                ->setEmailContactPurpose(true)
                ->setEmailServiceProvidersUsage(true)
                ->setMainAddressOtherUsage($faker->boolean(70))
                ->setMainAddressShopUsage($faker->boolean(80))
                ->setMinecraftAccountUsage(true)
                ->setPhoneContactPurpose(true)
                ->setProtectedBirthday(true)
                ->setPublicAge($faker->boolean(90))
                ->setPublicDepartement(true)
                ->setPublicFirstLogin(true)
                ->setPublicUsername(true)
                ->setReadAndAcceptedPenaltyTerms(true)
                ->setReadAndAcceptedRules(true)
                ->setRealPersonalInfo(true)
                ->setSecretSantaAddressUsage($faker->boolean())
                ->setStatisticalPurposes(true)
                ->setSteamAccountUsage(true)
                ->setUsernameCompliant(true);
            $user->setUserConsents($userConsents);
            $manager->persist($userConsents);

            $userProfile = new Profile();
            $userProfile->setDescription($faker->boolean(30) ? $faker->realText(255) : null);
            $user->setProfile($userProfile);
            $manager->persist($userProfile);

            $userSettings = new Settings();
            $userSettings->setAddressCountry($this->france)
                ->setAddressCommunePostalData($this->getRandomCommunePostalData())
                ->setDepartement($faker->boolean(85) ? $userSettings->getAddressCommunePostalData()->getCommune()->getDepartement() : $faker->randomElement($this->departements))
                ->setAddressLineStreet($faker->streetAddress())
                ->setAddressLineBuildingInside($faker->boolean() ? $faker->randomElement($this->addressLineBuildingInsideCollection) : null)
                ->setAddressLineBuildingOutside($faker->boolean() ? $faker->randomElement($this->addressLineBuildingOutsideCollection) : null)
                ->setAddressLineHamlet($userSettings->getAddressCommunePostalData()->getHamlet())
                ->setPhoneNumber(str_pad(strval(mt_rand("600000000", "799999999")), 10, "0", STR_PAD_LEFT))
                ->setAvoidDuplicateGames(false);
            $user->setSettings($userSettings);
            $manager->persist($userSettings);

            // Définition du pseudo et du mail
            $firstName_lower = mb_strtolower($user->getFirstName());
            $lastName_lower = str_replace(" ", "", mb_strtolower($user->getLastName()));
            $departement_lower = mb_strtolower(ltrim($user->getSettings()->getDepartement()->getInseeCode(), "0"));

            $firstName_lower = $this->removeAccentsOnLetters($firstName_lower);
            $lastName_lower = $this->removeAccentsOnLetters($lastName_lower);

            $i = 0;
            while(empty($user->getUsername()) || $i < 50) {
                /** @var int $usernameTemplate */
                $usernameTemplate = array_rand($usernameTemplates);

                $username = match($usernameTemplate) {
                    0 => $firstName_lower."_".$lastName_lower,
                    1 => $firstName_lower[0]."_".$lastName_lower,
                    2 => $firstName_lower[0].$lastName_lower,
                    3 => $firstName_lower.$departement_lower,
                    4 => $firstName_lower[0].$lastName_lower.$departement_lower,
                    5 => $faker->sentence(2, false).$departement_lower,
                    default => $faker->sentence(2, false)
                };

                if(!array_search($username, $generatedUsernames)) {
                    $user->setUsername($username);
                }

                $i++;
            }

            $i = 0;
            while(empty($user->getEmail()) || $i < 50) {
                /** @var int $emailTemplate */
                $emailTemplate = array_rand($emailTemplates);

                $email = match($emailTemplate) {
                    1 => $firstName_lower[0].".".$lastName_lower,
                    2 => $firstName_lower[0].$lastName_lower,
                    3 => "$lastName_lower.$firstName_lower",
                    4 => "$firstName_lower.$lastName_lower$departement_lower",
                    5 => $firstName_lower.$lastName_lower.$departement_lower,
                    6 => $firstName_lower.$user->getSettings()->getAddressCommunePostalData()->getPostalCode(),
                    default => "$firstName_lower.$lastName_lower",
                };

                $providerDiscriminator = str_pad(strval(mt_rand(1, 30)), 2, "0", STR_PAD_LEFT);
                $email .= "@pictocraft-fake$providerDiscriminator.fr";

                if(!array_search($email, $generatedEmails)) {
                    $user->setEmail($email);
                }

                $i++;
            }

            $userStats = new Stats();
            $userStats->setLastLoginAt($faker->dateTimeBetween(Carbon::createFromFormat("Y-m-d", $user->getFirstLogin())->isAfter(Carbon::parse("1 year ago")) ? $user->getFirstLogin() : "-1 year", "-3 days"))
                ->setLastLoginAttemptAt($faker->boolean(80) ? $userStats->getLastLoginAt() : $faker->dateTimeInInterval($userStats->getLastLoginAt()->format("Y-m-d H:i:s"), "+2 days"))
                ->setNbLoginAttempts($userStats->getLastLoginAt() === $userStats->getLastLoginAttemptAt() ? 0 : mt_rand(1, 3));
            $user->setStats($userStats);
            $manager->persist($userStats);

            // Données si l'utilisateur est membre
            if($faker->boolean(70)) {
                $user->addRole((new RoleUser())->setUser($user)->setRole($role_member))
                    ->setChristmasGiftEligible($faker->boolean(70))
                    ->setSecretSantaEligible($faker->boolean(70));
                $user->getStats()->setGifted($faker->boolean());
            }

            if(array_search("ROLE_MEMBER", $user->getRoles())) {
                if($user->isEnabled() && ($specialRolesCount["ROLE_DISCORD_ASSISTANT"] < 2 || $specialRolesCount["ROLE_MINECRAFT_MANAGER"] < 2)) {
                    if(empty($specialRolesCount["ROLE_DISCORD_ASSISTANT"]) && empty($specialRolesCount["ROLE_MINECRAFT_MANAGER"])) {
                        $user->addRole((new RoleUser())->setUser($user)->setRole($role_moderator))
                            ->addRole((new RoleUser())->setUser($user)->setRole($role_mc_admin));
                        $specialRolesCount["ROLE_DISCORD_ASSISTANT"]++;
                        $specialRolesCount["ROLE_MINECRAFT_MANAGER"]++;
                    } elseif($specialRolesCount["ROLE_DISCORD_ASSISTANT"] < 2) {
                        $user->addRole((new RoleUser())->setUser($user)->setRole($role_moderator));
                        $specialRolesCount["ROLE_DISCORD_ASSISTANT"]++;
                    } else {
                        $user->addRole((new RoleUser())->setUser($user)->setRole($role_mc_admin));
                        $specialRolesCount["ROLE_MINECRAFT_MANAGER"]++;
                    }
                }

                // TODO: Mettre ici les éléments spécifiques aux comptes membres
            }

            $manager->persist($user);

            $users[] = $user;
        }

        // On définit le dernier utilisateur comme étant celui qui a le rang le plus élevé
        $user->setUsername("SuperAdmin82")
            ->setEmail("superadmin-mail@pictocraft-fake01.fr")
            ->setPassword($this->passwordHasher->hashPassword($user, "Superadmin123"))
            ->setBirthday(DateTimeImmutable::createFromMutable($faker->dateTimeBetween("-30 years", "-18 years")->setTime(0, 0)))
            ->setFirstLogin("2017-01-01")
            ->setEnabled(true)
            ->setChristmasGiftEligible(false)
            ->setSecretSantaEligible(true)
            ->setWarnings(0)
            ->getUserConsents()->setMainAddressOtherUsage(true)
            ->setMainAddressShopUsage(true)
            ->setPublicAge(true)
            ->setSecretSantaAddressUsage(true);
        $user->getStats()->setGifted(false)
            ->setLastLoginAt($faker->dateTimeBetween("-1 week", "-3 days"))
            ->setLastLoginAttemptAt($userStats->getLastLoginAt());

        if(!array_search("ROLE_MEMBER", $user->getRoles())) {
            $user->addRole((new RoleUser())->setUser($user)->setRole($role_member));
        }
        $user->addRole((new RoleUser())->setUser($user)->setRole($role_founder))
            ->addRole((new RoleUser())->setUser($user)->setRole($role_mc_founder));

        // ===== SHOP ===== \\

        // ----- Category ----- \\

        /** @var Category[] $categories */
        $categories = [];

        $category = new Category();
        $category->setName("Catégorie par défaut")
            ->setEnabled(true);
        $manager->persist($category);

        for($c = 1; $c <= 10; $c++) {
            $category = new Category();
            $category->setName($faker->department(2))
                ->setEnabled($faker->boolean(70))
                ->setHidden($faker->boolean(20));

            if($faker->boolean(60)) {
                $category->setDefaultVatRate($faker->randomElement($this->vatRates));
            }

            $manager->persist($category);

            $subcategoryOwnVatRateProbability = $category->getDefaultVatRate() ? 20 : 80;
            for($sc = 1; $sc <= mt_rand(1, 3); $sc++) {
                $subcategory = new Category();
                $subcategory->setName($faker->department(2))
                    ->setEnabled($faker->boolean(90))
                    ->setHidden($faker->boolean(20))
                    ->setParent($category);

                if($faker->boolean($subcategoryOwnVatRateProbability)) {
                    $subcategory->setDefaultVatRate($faker->randomElement($this->vatRates));
                }

                $manager->persist($subcategory);
            }

            $categories[] = $category;
        }

        // ----- Attribute & Value ----- \\

        /** @var Attribute[] $attributes */
        $attributes = [];
        for($a = 1; $a <= 10; $a++) {
            $attribute = new Attribute();
            $attribute->setName(substr($faker->sentence(2, false), 0, -1));
            $manager->persist($attribute);

            $attributes[] = $attribute;

            // Check if an identical value is allowed for distinct attributes
            if($faker->boolean(30)) {
                $value = new Value();
                $value->setAttribute($attribute)
                    ->setValue("Doublon")
                    ->setHidden($faker->boolean());
                $manager->persist($value);
            }

            for($v = 1; $v <= mt_rand(2, 6); $v++) {
                $value = new Value();
                $value->setAttribute($attribute)
                    ->setValue(substr($faker->sentence(2, false), 0, -1))
                    ->setHidden($faker->boolean(30));
                $manager->persist($value);
            }
        }

        // ----- Delivery ----- \\

        /** @var Delivery[] $deliveries */
        $deliveries = [];
        for($d = 1; $d <= 10; $d++) {
            $deliveryType = $faker->boolean(10) ?
                DeliveryTypeEnum::MANUAL_SHOP :
                (
                    $faker->boolean() ?
                        DeliveryTypeEnum::MANUAL_USER :
                        DeliveryTypeEnum::AUTOMATIC
                );

            $delivery = new Delivery();
            $delivery->setName(substr($faker->sentence(3), 0, -1))
                ->setType($deliveryType);
            $manager->persist($delivery);

            $deliveries[] = $delivery;
        }

        // ----- Payment method ----- \\

        /** @var PaymentMethod[] $paymentMethods */
        $paymentMethods = [];
        /** @var PaymentMethod[] $availablePaymentMethods */
        $availablePaymentMethods = [];

        for($pm = 1; $pm <= 10; $pm++) {
            $paymentMethod = new PaymentMethod();
            $paymentMethod->setName(substr($faker->sentence(2, false), 0, -1))
                ->setEnabled($faker->boolean(80))
                ->setSelectable($faker->boolean())
                ->setType($faker->randomElement(PaymentMethodTypeEnum::cases()));
            $manager->persist($paymentMethod);

            $paymentMethods[] = $paymentMethod;

            if($paymentMethod->isEnabled() && $paymentMethod->isSelectable() && $paymentMethod->getType() === PaymentMethodTypeEnum::AUTOMATIC) {
                $availablePaymentMethods[] = $paymentMethod;
            }
        }

        // Flush before creating products, because we're checking constraints based on ID comparisons for some properties
        $manager->flush();

        foreach($attributes as $attribute) {
            $manager->refresh($attribute);
        }

        // ----- Product ----- \\

        /** @var Product[] $products */
        $products = [];
        /** @var string[] $productNames */
        $productNames = [];
        for($p = 1; $p <= 200; $p++) {
            $amount = $faker->boolean() ? -1 : mt_rand(0,10);

            $deliveriesForVirtualProducts = array_filter($deliveries, fn($delivery) => $delivery->getType() !== DeliveryTypeEnum::PHYSICAL);

            $i = 0;
            while($i < 100) {
                $productName = $faker->productName();

                if(!array_search($productName, $productNames)) {
                    $productNames[] = $productName;
                    break;
                }

                $i++;
            }

            $product = new Product();
            $product->setName($productName)
                ->setEnabled($faker->boolean(75))
                ->setBuyable($faker->boolean(70))
                ->setHidden($faker->boolean(30))
                ->setPriceTtc($faker->boolean(10) ? 0 : mt_rand(500, 5000))
                ->setQuantity($amount);
            $product->setDelivery($amount === -1 || $product->getPriceTtc() === 0 ? $faker->randomElement($deliveriesForVirtualProducts) : $faker->randomElement($deliveries))
                ->setReference($faker->regexify("[A-Z]{2}[A-Z0-9]{2}"))
                ->setDescription($faker->text(1800))
                ->setImage(explode("/id/", $faker->imageUrl(512, 512, true))[1]) // TODO: Corps à ajouter sur les URL en test (à mettre dans une variable env) : https://picsum.photos/id/
                ->setSubtitle($faker->boolean() ? str_replace(".", "", substr($faker->sentence(4, false), 0, 48))  : "");

            /** @var Attribute[] $productAttributes */
            $productAttributes = $faker->randomElements($attributes, mt_rand(2, 5));

            foreach($productAttributes as $attribute) {
                /** @var Value $attributeValue */
                $attributeValue = $faker->randomElement($attribute->getAttributeValues()->getValues());

                $product->addAttribute($attributeValue);
            }

            /** @var Category[] $productCategories */
            $productCategories = [];
            $categoriesNb = mt_rand(1, 4);
            $i = 0;
            while($i < 50 && count($productCategories) < $categoriesNb) {
                /** @var Category $category */
                $category = $faker->randomElement($categories);

                if(
                    !array_key_exists($category->getId(), $productCategories) &&
                    !array_key_exists($category->getParent()?->getId(), $productCategories) &&
                    $category->getSubcategories()->forAll(fn(int $key, Category $sc) => !array_key_exists($sc->getId(), $productCategories))
                ) {
                    $productCategories[$category->getId()] = $category;
                }

                $i++;
            }

            $isFirstCategory = true;
            foreach($productCategories as $category) {
                $productCategory = new ProductCategory();

                $productCategory->setCategory($category);

                if($isFirstCategory) {
                    $productCategory->setMain(true);

                    if(!$category->getApplicableVatRate() || $faker->boolean(10)) {
                        $product->setVatRate($faker->randomElement($this->vatRates));
                    }

                    $isFirstCategory = false;
                }

                $product->addProductCategory($productCategory);

                $manager->persist($productCategory);
            }

            $manager->persist($product);

            $products[] = $product;
        }

        // Flush after creating products, because it's needed for relation purposes on order processing
        //$manager->flush();

        $products_notPhysical = array_filter($products, fn(Product $product) => $product->getDelivery()->getType() !== DeliveryTypeEnum::PHYSICAL);

        // ----- Order ----- \\

        /** @var Order[] $orders */
        $orders = [];

        /** @var Order[] $ordersUserCartMatch */
        $ordersUserCartMatch = [];

        for($o = 1; $o <= 100; $o++) {
            /** @var User $user */
            $user = $faker->randomElement($users);

            $order = new Order();
            $order->setReference(mt_rand(100000000, 999999999))
                ->setUser($user)
                ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween($user->getFirstLogin(), $user->getStats()->getLastLoginAt())))
                ->setAddressLineStreet($user->getSettings()->getAddressLineStreet())
                ->setAddressLineBuildingInside($user->getSettings()->getAddressLineBuildingInside())
                ->setAddressLineBuildingOutside($user->getSettings()->getAddressLineBuildingOutside())
                ->setAddressLineHamlet($user->getSettings()->getAddressLineHamlet())
                ->setAddressCommunePostalData($user->getSettings()->getAddressCommunePostalData())
                ->setAddressCountry($user->getSettings()->getAddressCountry());

            // --- Managing statuses | Start ---

            $order->addStatusToHistory(
                (new OrderStatus())->setStatus(OrderStatusEnum::CART_CURRENT)
                    ->setDate($order->getCreatedAt())
            );

            $finalStatusArray = [
                0 => OrderStatusEnum::CART_CURRENT,
                1 => OrderStatusEnum::CART_ABORTED,
                2 => OrderStatusEnum::CART_ABORTED,
                3 => OrderStatusEnum::PAYMENT_PENDING,
                4 => OrderStatusEnum::ORDER_CONFIRMED,
                5 => OrderStatusEnum::ORDER_CONFIRMED,
                6 => OrderStatusEnum::ORDER_CONFIRMED,
                7 => OrderStatusEnum::ORDER_EXPIRED,
                8 => OrderStatusEnum::ORDER_ABORTED,
                9 => OrderStatusEnum::ORDER_CANCELLED
            ];

            $finalStatus = $finalStatusArray[$faker->randomDigit()];
            $nextStatusDate = $faker->dateTimeBetween(DateTime::createFromImmutable($order->getCreatedAt()), $user->getStats()->getLastLoginAt());

            if($finalStatus === OrderStatusEnum::CART_CURRENT) {
                if(isset($ordersUserCartMatch[$user->getId()])) {
                    continue;
                }

                $nextStatusDate = DateTime::createFromImmutable($order->getCreatedAt());
                $order->setUpdatedAt($nextStatusDate);
                $ordersUserCartMatch[$user->getId()] = $order;
            } elseif($finalStatus === OrderStatusEnum::CART_ABORTED) {
                $order->addStatusToHistory(
                    (new OrderStatus())->setStatus(OrderStatusEnum::CART_ABORTED)
                        ->setDate($nextStatusDate)
                );
            } else {
                $order->addStatusToHistory(
                    (new OrderStatus())->setStatus(OrderStatusEnum::PAYMENT_PENDING)
                        ->setDate($nextStatusDate)
                );

                $nextStatusDate = $faker->dateTimeBetween($nextStatusDate, $user->getStats()->getLastLoginAt());

                switch($finalStatus) {
                    case OrderStatusEnum::ORDER_CONFIRMED:
                    case OrderStatusEnum::ORDER_CANCELLED:
                        $order->addStatusToHistory(
                            (new OrderStatus())->setStatus(OrderStatusEnum::ORDER_CONFIRMED)
                                ->setDate($nextStatusDate)
                        );

                        if($finalStatus === OrderStatusEnum::ORDER_CANCELLED) {
                            $nextStatusDate = $faker->dateTimeBetween($nextStatusDate, $user->getStats()->getLastLoginAt());

                            $order->addStatusToHistory(
                                (new OrderStatus())->setStatus(OrderStatusEnum::ORDER_CANCELLED)
                                    ->setDate($nextStatusDate)
                            );
                        }

                        break;
                    case OrderStatusEnum::ORDER_EXPIRED:
                        $order->addStatusToHistory(
                            (new OrderStatus())->setStatus(OrderStatusEnum::ORDER_EXPIRED)
                                ->setDate($nextStatusDate)
                        );
                        break;
                    default:
                        $order->addStatusToHistory(
                            (new OrderStatus())->setStatus(OrderStatusEnum::ORDER_ABORTED)
                                ->setDate($nextStatusDate)
                        );
                        break;
                }
            }

            // --- Managing statuses | End ---

            $mostRecentItemUpdateDate = null;
            $orderNbProducts = $faker->boolean(80) ? 1 : ($faker->boolean(75) ? 2 : 3);
            for($p = 1; $p <= $orderNbProducts; $p++) {
                $productValid = false;
                $i = 0;
                while(!$productValid && $i < 30) {
                    $i++;

                    /** @var Product $product */
                    $product = $faker->randomElement($products_notPhysical);

                    if(!$order->getItems()->exists(fn(int $key, OrderItem $item) => $product->getId() === $item->getProduct()->getId())) {
                        $productValid = true;
                    }
                }

                if(!$productValid) {
                    continue;
                }

                $item = new OrderItem();
                $item->setProductAndBasePriceTtcPerUnit($product)
                    ->setQuantity(1)
                    ->setDelivery($product->getDelivery())
                    ->setCreatedAt($order->getCreatedAt());

                // --- Managing statuses | Start ---

                $item->addStatusToHistory(
                    (new OrderItemStatus())->setStatus(OrderItemStatusEnum::CART_CURRENT)
                        ->setDate($order->getStatusDetails(OrderStatusEnum::CART_CURRENT)->getDate())
                );

                if($finalStatus === OrderStatusEnum::CART_ABORTED) {
                    $item->addStatusToHistory(
                        (new OrderItemStatus())->setStatus(OrderItemStatusEnum::CART_ABORTED)
                            ->setDate($order->getStatusDetails(OrderStatusEnum::CART_ABORTED)->getDate())
                    );
                } elseif($finalStatus !== OrderStatusEnum::CART_CURRENT) {
                    $item->addStatusToHistory(
                        (new OrderItemStatus())->setStatus(OrderItemStatusEnum::PAYMENT_PENDING)
                            ->setDate($order->getStatusDetails(OrderStatusEnum::PAYMENT_PENDING)->getDate())
                    );

                    if($finalStatus === OrderStatusEnum::ORDER_EXPIRED) {
                        $item->addStatusToHistory(
                            (new OrderItemStatus())->setStatus(OrderItemStatusEnum::ITEM_CANCELLED)
                                ->setDate($order->getStatusDetails(OrderStatusEnum::ORDER_EXPIRED)->getDate())
                        );
                    } elseif($finalStatus === OrderStatusEnum::ORDER_ABORTED) {
                        $item->addStatusToHistory(
                            (new OrderItemStatus())->setStatus(OrderItemStatusEnum::ITEM_CANCELLED)
                                ->setDate($order->getStatusDetails(OrderStatusEnum::ORDER_ABORTED)->getDate())
                        );
                    } elseif($finalStatus !== OrderStatusEnum::PAYMENT_PENDING) {
                        // ORDER_CONFIRMED ou ORDER_CANCELLED
                        // TODO: uniquement produits virtuels pour le moment, produits physiques à faire

                        $maxNextStatusDateWhenNotYetCancelled = $finalStatus === OrderStatusEnum::ORDER_CONFIRMED ? $user->getStats()->getLastLoginAt() : DateTime::createFromImmutable($order->getStatusDetails(OrderStatusEnum::ORDER_CANCELLED)->getDate());
                        $orderConfirmedStatusDate = DateTime::createFromImmutable($order->getStatusDetails(OrderStatusEnum::ORDER_CONFIRMED)->getDate());
                        $nextItemStatusDate = $orderConfirmedStatusDate;

                        switch($item->getDelivery()->getType()) {
                            case DeliveryTypeEnum::AUTOMATIC:
                                if($finalStatus !== OrderStatusEnum::ORDER_CANCELLED && $faker->boolean(10)) {
                                    // Si la livraison automatique échoue
                                    $item->addStatusToHistory(
                                        (new OrderItemStatus())->setStatus(OrderItemStatusEnum::ITEM_REQUEST_SENT)
                                            ->setDate($nextItemStatusDate)
                                    );

                                    if($faker->boolean(20)) {
                                        break;
                                    }

                                    $nextItemStatusDate = $faker->dateTimeBetween($orderConfirmedStatusDate, $maxNextStatusDateWhenNotYetCancelled);
                                    $item->addStatusToHistory(
                                        (new OrderItemStatus())->setStatus(OrderItemStatusEnum::ITEM_ACTIVATED)
                                            ->setDate($nextItemStatusDate)
                                    );
                                } else {
                                    $item->addStatusToHistory(
                                        (new OrderItemStatus())->setStatus($faker->boolean() ? OrderItemStatusEnum::ITEM_ACTIVATED : OrderItemStatusEnum::DELIVERY_DONE)
                                            ->setDate($nextItemStatusDate)
                                    );
                                }

                                break;
                            case DeliveryTypeEnum::MANUAL_SHOP:
                                $nextItemStatusDate = $faker->dateTimeBetween($orderConfirmedStatusDate, $maxNextStatusDateWhenNotYetCancelled);
                                $item->addStatusToHistory(
                                    (new OrderItemStatus())->setStatus(OrderItemStatusEnum::ITEM_REQUEST_SENT)
                                        ->setDate($nextItemStatusDate)
                                );

                                if($finalStatus !== OrderStatusEnum::ORDER_CANCELLED && $faker->boolean(20)) {
                                    break;
                                }

                                $nextItemStatusDate = $faker->dateTimeBetween($nextItemStatusDate, $maxNextStatusDateWhenNotYetCancelled);
                                $item->addStatusToHistory(
                                    (new OrderItemStatus())->setStatus($faker->boolean() ? OrderItemStatusEnum::ITEM_ACTIVATED : OrderItemStatusEnum::DELIVERY_DONE)
                                        ->setDate($nextItemStatusDate)
                                );

                                break;
                            case DeliveryTypeEnum::MANUAL_USER:
                                $item->addStatusToHistory(
                                    (new OrderItemStatus())->setStatus(OrderItemStatusEnum::ITEM_ACTIVATION_PENDING)
                                        ->setDate($nextItemStatusDate)
                                );

                                if($faker->boolean(20)) {
                                    break;
                                }

                                $itemActivationStatus = $faker->boolean(80) ? OrderItemStatusEnum::ITEM_ACTIVATED : ($faker->boolean() ? OrderItemStatusEnum::ITEM_PARTIALLY_ACTIVATED_DISCORD : OrderItemStatusEnum::ITEM_PARTIALLY_ACTIVATED_MINECRAFT);
                                $nextItemStatusDate = $faker->dateTimeBetween($orderConfirmedStatusDate, $maxNextStatusDateWhenNotYetCancelled);
                                $item->addStatusToHistory(
                                    (new OrderItemStatus())->setStatus($itemActivationStatus)
                                        ->setDate($nextItemStatusDate)
                                );

                                if($itemActivationStatus === OrderItemStatusEnum::ITEM_ACTIVATED || $faker->boolean()) {
                                    break;
                                }

                                $nextItemStatusDate = $faker->dateTimeBetween($nextItemStatusDate, $maxNextStatusDateWhenNotYetCancelled);
                                $item->addStatusToHistory(
                                    (new OrderItemStatus())->setStatus(OrderItemStatusEnum::ITEM_ACTIVATED)
                                        ->setDate($nextItemStatusDate)
                                );

                                break;
                            default:
                                throw new Exception("Ce type de livraison n'est pas encore pris en charge");
                        }

                        $cancellationPath = false;
                        if($finalStatus === OrderStatusEnum::ORDER_CANCELLED || $faker->boolean(30)) {
                            // Retours et rétractations
                            $withdrawalAndReturnStatusArray = [
                                "withdrawal" => [
                                    "sent" => OrderItemStatusEnum::WITHDRAWAL_REQUEST_SENT,
                                    "accepted" => OrderItemStatusEnum::WITHDRAWAL_REQUEST_ACCEPTED,
                                    "rejected" => OrderItemStatusEnum::WITHDRAWAL_REQUEST_REJECTED
                                ],
                                "return" => [
                                    "sent" => OrderItemStatusEnum::RETURN_REQUEST_SENT,
                                    "accepted" => OrderItemStatusEnum::RETURN_REQUEST_ACCEPTED,
                                    "rejected" => OrderItemStatusEnum::RETURN_REQUEST_REJECTED
                                ]
                            ];

                            /** @var OrderItemStatusEnum[] $requestStatusArray */
                            $requestStatusArray = $withdrawalAndReturnStatusArray[$faker->boolean(70) ? "withdrawal" : "return"];

                            $nextItemStatusDate = $faker->dateTimeBetween($nextItemStatusDate, $maxNextStatusDateWhenNotYetCancelled);
                            $item->addStatusToHistory(
                                (new OrderItemStatus())->setStatus($requestStatusArray["sent"])
                                    ->setDate($nextItemStatusDate)
                            );

                            if($finalStatus === OrderStatusEnum::ORDER_CANCELLED || $faker->boolean(80)) {
                                $requestStatusAnswer =
                                    $finalStatus === OrderStatusEnum::ORDER_CANCELLED || $faker->boolean(80)
                                    ? $requestStatusArray["accepted"]
                                    : $requestStatusArray["rejected"]
                                ;

                                $nextItemStatusDate = $faker->dateTimeBetween($nextItemStatusDate, $maxNextStatusDateWhenNotYetCancelled);
                                $item->addStatusToHistory(
                                    (new OrderItemStatus())->setStatus($requestStatusAnswer)
                                        ->setDate($nextItemStatusDate)
                                );

                                if($requestStatusAnswer === $requestStatusArray["accepted"]) {
                                    $cancellationPath = true;
                                }
                            }
                        }

                        if($cancellationPath) {
                            $nextItemStatusDate = $faker->dateTimeBetween($nextItemStatusDate, $maxNextStatusDateWhenNotYetCancelled);
                            $item->addStatusToHistory(
                                (new OrderItemStatus())->setStatus(OrderItemStatusEnum::RETURN_PENDING)
                                    ->setDate($nextItemStatusDate)
                            );

                            $nextItemStatusDate = $faker->dateTimeBetween($nextItemStatusDate, $maxNextStatusDateWhenNotYetCancelled);
                            $item->addStatusToHistory(
                                (new OrderItemStatus())->setStatus(OrderItemStatusEnum::RETURN_IN_PROGRESS)
                                    ->setDate($nextItemStatusDate)
                            );

                            if($finalStatus === OrderStatusEnum::ORDER_CANCELLED || $faker->boolean(90)) {
                                $nextItemStatusDate = $faker->dateTimeBetween($nextItemStatusDate, $maxNextStatusDateWhenNotYetCancelled);
                                $item->addStatusToHistory(
                                    (new OrderItemStatus())->setStatus(OrderItemStatusEnum::RETURN_RECEIVED)
                                        ->setDate($nextItemStatusDate)
                                );

                                $returnStatus = ($finalStatus === OrderStatusEnum::ORDER_CANCELLED || $faker->boolean(70))
                                    ? OrderItemStatusEnum::RETURN_CONFIRMED
                                    : OrderItemStatusEnum::RETURN_NON_COMPLIANT
                                ;
                                $nextItemStatusDate = $faker->dateTimeBetween($nextItemStatusDate, $maxNextStatusDateWhenNotYetCancelled);
                                $item->addStatusToHistory(
                                    (new OrderItemStatus())->setStatus($returnStatus)
                                        ->setDate($nextItemStatusDate)
                                );

                                if($returnStatus === OrderItemStatusEnum::RETURN_CONFIRMED) {
                                    $nextItemStatusDate = $faker->dateTimeBetween($nextItemStatusDate, $maxNextStatusDateWhenNotYetCancelled);
                                    $item->addStatusToHistory(
                                        (new OrderItemStatus())->setStatus(OrderItemStatusEnum::REFUND_PENDING)
                                            ->setDate($nextItemStatusDate)
                                    );

                                    if($finalStatus === OrderStatusEnum::ORDER_CANCELLED || $faker->boolean(75)) {
                                        $nextItemStatusDate = $order->getCurrentStatus()->getDate();

                                        $item->addStatusToHistory(
                                            (new OrderItemStatus())->setStatus(OrderItemStatusEnum::REFUND_DONE)
                                                ->setDate($nextItemStatusDate)
                                        );

                                        $item->addStatusToHistory(
                                            (new OrderItemStatus())->setStatus(OrderItemStatusEnum::ITEM_CANCELLED)
                                                ->setDate($nextItemStatusDate)
                                        );
                                    }
                                }
                            }
                        }
                    }
                }

                // --- Managing statuses | End ---

                $item->setUpdatedAt($item->getCurrentStatus()->getDate());
                if(is_null($mostRecentItemUpdateDate) || $mostRecentItemUpdateDate > $item->getCurrentStatus()->getDate()) {
                    $mostRecentItemUpdateDate = $item->getCurrentStatus()->getDate();
                }

                // TODO : discount

                $order->addItem($item);
                $manager->persist($item);
            }

            if($order->getItems()->isEmpty()) {
                unset($order);
                continue;
            }

            $order->setUpdatedAt($mostRecentItemUpdateDate);
            $order->updateTotals();

            // TODO : payments / discounts globaux

            /*if($order->getTotalAmountTTC() === 0) {

            }*/

            // TODO: finir les propriétés de commande

            $orders[] = $order;
            $manager->persist($order);
        }

        $manager->flush();
    }

    private function getRandomCommunePostalData(): CommunePostalData
    {
        $randomId = mt_rand(1, $this->highestCommunePostalDataId);
        return $this->communePostalDataRepository->find($randomId);
    }

    // TODO : à déplacer dans une classe utilitaire
    private function removeAccentsOnLetters(string $string): string
    {
        $string = str_replace(["à","â","ä"],"a", $string);
        $string = str_replace(["é","è","ê","ë"],"e", $string);
        $string = str_replace(["î","ï"],"i", $string);
        $string = str_replace(["ô","ö"],"o", $string);
        $string = str_replace(["ù","ü"],"u", $string);
        $string = str_replace(["ÿ","ç"], ["y","c"], $string);
        $string = str_replace(["À","Â","Ä"],"A", $string);
        $string = str_replace(["É","È","Ê","Ë"],"E", $string);
        $string = str_replace(["Î","Ï"],"I", $string);
        $string = str_replace(["Ô","Ö"],"O", $string);
        $string = str_replace(["Ù","Ü"],"U", $string);
        return str_replace(["Ÿ","Ç"], ["Y", "C"], $string);
    }
}
