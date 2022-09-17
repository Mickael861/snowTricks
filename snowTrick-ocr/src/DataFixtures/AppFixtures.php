<?php

namespace App\DataFixtures;

use App\Entity\Discussions;
use App\Entity\Users;
use App\Entity\Figures;
use App\Entity\FiguresGroups;
use App\Entity\FiguresImages;
use App\Entity\FiguresVideos;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $users = $this->saveUser($manager);

        $this->saveFigureGroup($users, $manager);

        $this->saveFigures($users, $manager);
    }

    private function saveFigures(Users $users, ObjectManager $manager)
    {
        $repository = $manager->getRepository(FiguresGroups::class);

        $datasFigures = [
            0 => [
                "user_id" => $users,
                "figure_group_id" => $repository->findOneBy(array('name' => 'Grabs')),
                "name" => "Big Air",
                "slug" => "big-air",
                "descritpion" => "Le Big Air, ou grand saut1, est une discipline sportive de la famille des sports d'hiver qui utilise une structure de neige commune au ski ou au snowboard, un tremplin permettant d'effectuer des figures dans les airs. Le Big Air est l'une des six disciplines du ski freestyle. Il désigne également la structure ou module sur lequel s'effectue les acrobaties. Le Big Air devient une discipline olympique, en snowboard à PeyongChang en 2018 et à skis à Pékin en 2022.",
                "discussions" => [
                    "Magnifique Figures je la trouve exceptionelle, un jour je serai aussi fort que toi c'est sur",
                    "Je n'arrive jamais à faire cette figure c'est trop triste, mais je continu et j'y arriverai"
                ],
                "figures_videos" => [
                    'a4GqP1LWoTk',
                    'wKY0-M_gAHE'
                ],
                "figures_images" => [
                    '360.jpg'
                ]
            ],
            1 => [
                "user_id" => $users,
                "figure_group_id" => $repository->findOneBy(array('name' => 'Rotations')),
                "name" => "Hip",
                "slug" => "hip",
                "descritpion" => "Un hip est une structure de neige utilisée pour le ski freestyle ou le snowboard. Il s'agit d'un tremplin permettant d'effectuer des figures en l'air.",
                "discussions" => [
                    "Magnifique Figures je la trouve exceptionelle, un jour je serai aussi fort que toi c'est sur",
                    "Je n'arrive jamais à faire cette figure c'est trop triste, mais je continu et j'y arriverai"
                ],
                "figures_videos" => [
                    'slnj1CDmqMQ',
                    'OKscADDYxrg'
                ],
                "figures_images" => [
                    '720.jpg'
                ]
            ],
            2 => [
                "user_id" => $users,
                "figure_group_id" => $repository->findOneBy(array('name' => 'Flips')),
                "name" => "Step-up",
                "slug" => "step-up",
                "descritpion" => "Un step-up est une structure de neige utilisée pour le ski freestyle ou le snowboard. Il s'agit d'un tremplin permettant d'effectuer des figures en l'air.",
                "discussions" => [
                    "Magnifique Figures je la trouve exceptionelle, un jour je serai aussi fort que toi c'est sur",
                    "Je n'arrive jamais à faire cette figure c'est trop triste, mais je continu et j'y arriverai"
                ],
                "figures_videos" => [
                    'XVPCA5yCBGw',
                    '6S-TiJrzOmg'
                ],
                "figures_images" => [
                    '1080.jpg'
                ]
            ],
            3 => [
                "user_id" => $users,
                "figure_group_id" => $repository->findOneBy(array('name' => 'Rotations désaxées')),
                "name" => "Half-pipe",
                "slug" => "half-pipe",
                "descritpion" => "La rampe, ou half-pipe (ou halfpipe1), est un des types de modules de skatepark que l'on peut trouver dans les skateparks. C'est également le nom d'une discipline du skateboard, du roller et du BMX. On l'appelle également la « big », la « vert' » (venant de « verticale »), ou encore la « courbe ». C'est également une épreuve olympique en surf des neiges et en ski freestyle.",
                "discussions" => [
                    "Magnifique Figures je la trouve exceptionelle, un jour je serai aussi fort que toi c'est sur",
                    "Je n'arrive jamais à faire cette figure c'est trop triste, mais je continu et j'y arriverai"
                ],
                "figures_videos" => [
                    'M489XaI6CFI',
                    '9uXw9hugmfE'
                ],
                "figures_images" => [
                    'backflip.jpg'
                ]
            ],
            4 => [
                "user_id" => $users,
                "figure_group_id" => $repository->findOneBy(array('name' => 'Flips')),
                "name" => "Quarter-pipe",
                "slug" => "quarter-pipe",
                "descritpion" => "Le quarter-pipe (littéralement « quart de tube » en anglais) est une structure utilisée pour les sports de glisse comme le skateboard, le roller, la trottinette ou le BMX. Il s'agit d'un tremplin permettant d'effectuer des figures en l'air. Il s'agit également d'un type de module de skatepark. Dans le cas du ski freestyle ou du snowboard on parle plutôt de « big air », auquel cas il est fait de neige.",
                "discussions" => [
                    "Magnifique Figures je la trouve exceptionelle, un jour je serai aussi fort que toi c'est sur",
                    "Je n'arrive jamais à faire cette figure c'est trop triste, mais je continu et j'y arriverai"
                ],
                "figures_videos" => [
                    'd5E92QXS9aE',
                    'caLvgeES7Qw'
                ],
                "figures_images" => [
                    'frontflip.jpg'
                ]
            ],
            5 => [
                "user_id" => $users,
                "figure_group_id" => $repository->findOneBy(array('name' => 'Grabs')),
                "name" => "Barre de slide",
                "slug" => "barre-de-slide",
                "descritpion" => "Une barre de slide ou rail est une structure métallique utilisée dans la pratique du ski freestyle ou du snowboard et permet d'évoluer en glissant en équilibre sur le module.",
                "discussions" => [
                    "Magnifique Figures je la trouve exceptionelle, un jour je serai aussi fort que toi c'est sur",
                    "Je n'arrive jamais à faire cette figure c'est trop triste, mais je continu et j'y arriverai"
                ],
                "figures_videos" => [
                    'O1iG6-E-5rI',
                    'zhAMvSOLXV8'
                ],
                "figures_images" => [
                    'mute.jpg'
                ],
            ],
            6 => [
                "user_id" => $users,
                "figure_group_id" => $repository->findOneBy(array('name' => 'Flips')),
                "name" => "Waterslide",
                "slug" => "waterslide",
                "descritpion" => "Un waterslide ou watherride est une structure utilisée dans la pratique du ski freestyle et du snowboard. Le terme waterslide désigne aussi le fait d'emprunter cette structure.",
                "discussions" => [
                    "Magnifique Figures je la trouve exceptionelle, un jour je serai aussi fort que toi c'est sur",
                    "Je n'arrive jamais à faire cette figure c'est trop triste, mais je continu et j'y arriverai"
                ],
                "figures_videos" => [
                    '3RsoUTU9NMo',
                    '9Pjr670xrzc'
                ],
                "figures_images" => [
                    'noseslide.jpg'
                ]
            ],
            7 => [
                "user_id" => $users,
                "figure_group_id" => $repository->findOneBy(array('name' => 'Rotations désaxées')),
                "name" => "Road gap",
                "slug" => "road-gap",
                "descritpion" => "Un road gap est un obstacle à franchir en skis, snowboard ou en VTT. Il consiste le plus souvent en une route à franchir en sautant d'une pente en amont vers une deuxième pente en aval par-dessus une route. Généralement, un kick est disposé à la fin de la première pente.",
                "discussions" => [
                    "Magnifique Figures je la trouve exceptionelle, un jour je serai aussi fort que toi c'est sur",
                    "Je n'arrive jamais à faire cette figure c'est trop triste, mais je continu et j'y arriverai"
                ],
                "figures_videos" => [
                    'AwZiyg3gj4Y',
                    '-5ZNn2Sg30I'
                ],
                "figures_images" => [
                    'truckDriver.jpg'
                ]
            ],
            8 => [
                "user_id" => $users,
                "figure_group_id" => $repository->findOneBy(array('name' => 'Grabs')),
                "name" => "Little Air",
                "slug" => "little-air",
                "descritpion" => "Le little-air, ou petit saut, est une discipline sportive de la famille des sports d'hiver qui utilise une structure de neige commune au ski ou au snowboard, un tremplin permettant d'effectuer des figures dans les airs. Le Big Air est l'une des six disciplines du ski freestyle. Il désigne également la structure ou module sur lequel s'effectue les acrobaties. Le Big Air devient une discipline olympique, en snowboard à PeyongChang en 2018 et à skis à Pékin en 2022.",
                "discussions" => [
                    "Magnifique Figures je la trouve exceptionelle, un jour je serai aussi fort que toi c'est sur",
                    "Je n'arrive jamais à faire cette figure c'est trop triste, mais je continu et j'y arriverai"
                ],
                "figures_videos" => [
                    'a4GqP1LWoTk',
                    'wKY0-M_gAHE'
                ],
                "figures_images" => [
                    'tailslide.jpg'
                ]
            ],
            9 => [
                "user_id" => $users,
                "figure_group_id" => $repository->findOneBy(array('name' => 'Rotations désaxées')),
                "name" => "Road-pipe",
                "slug" => "road-pipe",
                "descritpion" => "La rampe, ou road-pipe (ou road-pipe), est un des types de modules de skatepark que l'on peut trouver dans les skateparks. C'est également le nom d'une discipline du skateboard, du roller et du BMX. On l'appelle également la « big », la « vert' » (venant de « verticale »), ou encore la « courbe ». C'est également une épreuve olympique en surf des neiges et en ski freestyle.",
                "discussions" => [
                    "Magnifique Figures je la trouve exceptionelle, un jour je serai aussi fort que toi c'est sur",
                    "Je n'arrive jamais à faire cette figure c'est trop triste, mais je continu et j'y arriverai"
                ],
                "figures_videos" => [
                    'slnj1CDmqMQ',
                    'OKscADDYxrg'
                ],
                "figures_images" => [
                    'hip-1.jpg',
                    'hip-2.jpg'
                ]
            ]
        ];

        foreach ($datasFigures as $figure) {
            $figures = new Figures;

            $figures
                ->setUser($figure['user_id'])
                ->setFigureGroup($figure['figure_group_id'])
                ->setName($figure['name'])
                ->setSlug($figure['slug'])
                ->setDescription($figure['descritpion']);

            foreach ($figure['figures_videos'] as $video) {
                $figuresVideos = new FiguresVideos;
                $figuresVideos
                    ->setFigure($figures)
                    ->setSiteUrl($video);
    
                $figures->addFiguresVideo($figuresVideos);
            }

            foreach ($figure['figures_images'] as $image) {
                $figuresImages = new FiguresImages;

                $file_path = new File('public/images/figures/' . $image);
                $file = $image . $file_path->guessExtension();
                $filesystem = new Filesystem();
                $filesystem->mkdir('public/images/figures/');
                if ($filesystem->exists($file)) {
                    $file_path->move('public/images/figures/', $file);
                }
                
                $figuresImages
                ->setFigure($figures)
                ->setFilePath($image);
    
                $figures->addFiguresImage($figuresImages);
            }

            foreach ($figure['discussions'] as $discussion) {
                $discussions = new Discussions;
                $discussions
                ->setFigure($figures)
                ->setUser($users)
                ->setContent($discussion);
    
                $figures->addDiscussion($discussions);
            }

            $manager->persist($figures);
            $manager->flush();
        }
    }

    /**
     * save of different groups
     */
    private function saveFigureGroup(Users $users, ObjectManager $manager)
    {
        $groups = [
            'Grabs',
            'Rotations',
            'Flips',
            'Rotations désaxées'
        ];

        foreach ($groups as $group) {
            $figureGroup = new FiguresGroups;
            $figureGroup
                ->setName($group);
    
            $manager->persist($figureGroup);
            $manager->flush();
        }
    }

    /**
     * Save Users
     */
    private function saveUser(ObjectManager $manager): Users
    {
        $users = new Users();

        $hashedPassword = $this->hasher->hashPassword(
            $users,
            "123456789M@"
        );
        $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');

        $users
            ->setEmail("m-iicka86@hotmail.fr")
            ->setRoles(["ROLE_USER"])
            ->setPassword($hashedPassword)
            ->setUserName("Freaks")
            ->setFilePath("profil-2.png")
            ->setIsValidate(true)
            ->setToken($token);

        $manager->persist($users);
        $manager->flush();

        return $users;
    }
}
