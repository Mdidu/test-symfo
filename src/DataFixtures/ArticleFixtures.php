<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for($i = 0; $i < 3; $i++){
            $category = new Category();
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());

            $manager->persist($category);

            for($j = 1; $j <= mt_rand(4, 6); $j++){
                $article = new Article();

                $content = '<p>'.join($faker->paragraphs(5), '</p><p>').'</p>';
                
                $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                        ->setCategory($category);

                $manager->persist($article);

                for($k = 0; $k < mt_rand(4, 10); $k++){
                    $comment = new Comment();
                    $content .= '<p>'.join($faker->paragraphs(2), '</=><p>').'</p>';
                    $now = new \DateTime();
                    $days = $now->diff($article->getCreatedAt())->days;
                    $min = '-'.$days.'days';

                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween($min))
                            ->setArticle($article);
                    $manager->persist($comment);
                }
            }
        }
        

        $manager->flush();
    }
}
