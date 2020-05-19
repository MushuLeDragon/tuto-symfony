<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
  public function load(ObjectManager $manager)
  {

    $faker = \Faker\Factory::create('fr_FR');

    /**
     * Créer 3 catégories fakées
     */
    for ($i = 1; $i <= 3; $i++) {
      $category = new Category;
      $category->setTitle($faker->sentence())
        ->setDescription($faker->paragraph());

      $manager->persist($category);

      /**
       * Créer entre 4 et 6 articles
       */
      for ($j = 1; $j <= mt_rand(4, 6); $j++) {
        $content = '<p>' . join($faker->paragraphs(5), '</p><p>') . '</p>';

        $article = new Article();
        $article->setTitle($faker->sentence())
          ->setContent($content)
          ->setImage($faker->imageUrl())
          ->setCreatedAt($faker->dateTimeBetween('-6 months'))
          ->setCategory($category);

        $manager->persist($article);

        /**
         * Génération des commentaires pour l'article
         */
        for ($k = 1; $k <= mt_rand(4, 10); $k++) {
          $content = '<p>' . join($faker->paragraphs(2), '</p><p>') . '</p>';

          // $now = new \DateTime();
          // $interval = $now->diff($article->getCreatedAt());
          // $days = $interval->days;
          $days = (new \DateTime())->diff($article->getCreatedAt())->days;

          $comment = new Comment;
          $comment->setAuthor($faker->name)
            ->setContent($content)
            ->setCreatedAt($faker->dateTimeBetween('-' . $days . ' days'))
            ->setArticle($article);

          $manager->persist($comment);
        }
      }
    }
    $manager->flush();
  }
}
