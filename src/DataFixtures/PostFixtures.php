<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class PostFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param  ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 100; $i++) {
            $post = new Post;
            $post->setTitle('Article ' . $i)
                ->setContent('Content ' . $i)
                ->setUser($this->getReference('user' . (($i % 10) + 1)))
                ->setImage('https://picsum.photos/400/300');
            $manager->persist($post);

            for ($j = 1; $j <= rand(5, 15); $j++) {
                $comment = new Comment;
                $comment->setAuthor('Auteur ' . $j)
                    ->setContent('Commentaire ' . $i . ' | ' . $j)
                    ->setPost($post);
                $manager->persist($comment);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
