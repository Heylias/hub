<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Tags;
use App\Entity\User;
use App\Entity\Chapter;
use App\Entity\Comment;
use App\Entity\FanficImage;
use App\Entity\UserImage;
use App\Entity\Fanfiction;
use App\Entity\Genre;
use App\Entity\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function load(ObjectManager $manager)
    {
        // $languageRepository = $manager->getRepository(Language::class);
        // $languageList = $languageRepository->findAll();


        $languageRepository = array(
            ['Deutsche', 'DE'],
            ['English', 'EN']
        );

        $faker = Factory::create('fr_FR');

        $userList = array();
        $adminUser = new User();

        $adminUser->setPseudonym('Heylias')
                ->setEmail('nicolasheyligers@hotmail.com')
                ->setPassword($this->passwordEncoder->encodePassword($adminUser,'password'))
                ->setRoles(['ROLE_ADMIN']);
        $manager->persist($adminUser);
        array_push($userList, $adminUser);


        $genreList = array();
        for($g = 0; $g < 20; $g++){
            $genre = new Genre();
            $genre->setName($faker->word());
            $manager->persist($genre);
            array_push($genreList, $genre);
        }

        $languageList = array();
        foreach($languageRepository as list($name, $short)){
            $language = new Language();
            $language->setName($name)
                    ->setShort($short);
            $manager->persist($language);
            array_push($languageList, $language);
            
        }

        $taglist = array();
        for($t = 0; $t < 30; $t++){
            $tag = new Tags();
            $tag->setName($faker->word())
                ->setDescription($faker->text($maxNbChars = 300));
            $manager->persist($tag);
            array_push($taglist, $tag);
        }

        for($u = 0; $u < 30; $u++){
            $user = new User();
            $user->setPseudonym($faker->userName())
                ->setEmail($faker->freeEmail())
                ->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
            $manager->persist($user);
            array_push($userList, $user);

            $fanficList = array();
            for($f = 0; $f < mt_rand(0,10); $f++){
                $flanguage = array_rand($languageList);
                $fanfic = new Fanfiction();
                $fanfic->setTitle($faker->sentence($nbWords = mt_rand(5,10), $variableNbWords = true))
                        ->setSummary($faker->text($maxNbChars = 300))
                        ->setAuthor($user)
                        ->setCoverImage("http://placehold.it/150x228")
                        ->setLanguage($languageList[$flanguage]);
                $manager->persist($fanfic);
                array_push($fanficList, $fanfic);

                for($t = 0; $t < mt_rand(5, 20); $t++){
                    $ftag = array_rand($taglist);
                    $fanfic->addTag($taglist[$ftag]);
                    $manager->persist($fanfic);
                }

                for($g = 0; $g < mt_rand(1,5); $g++){
                    $fgenre = array_rand($genreList);
                    $fanfic->addGenre($genreList[$fgenre]);
                    $manager->persist($fanfic);
                }

                for($c = 0; $c < mt_rand(0, 10); $c++){
                    $comment = new Comment();
                    $commentAuth = array_rand($userList);
                    $commentFanfic = array_rand($fanficList);
                    $comment->setAuthor($userList[$commentAuth])
                            ->setFanfiction($fanficList[$commentFanfic])
                            ->setRating(mt_rand(0, 5))
                            ->setCommentary($faker->text($maxNbChars = 100))
                            ->setCreationDate($faker->dateTimeBetween($startDate = '-30 days', $endDate = 'now', $timezone = date_default_timezone_get()));
                    $manager->persist($comment);
                }

                for($chap = 0; $chap < mt_rand(10,20); $chap++){
                    $chapter = new Chapter();
                    $chapter->setFanfiction($fanfic)
                            ->setAddedAt($faker->dateTimeBetween($startDate = '-30 days', $endDate = 'now', $timezone = date_default_timezone_get()))
                            ->setTitle($faker->sentence($nbWords = mt_rand(1,5)))
                            ->setChapter($chap + 1)
                            ->setLink($faker->url);
                    $manager->persist($chapter);
                }
            }
        }
        
        $manager->flush();
    }
}
