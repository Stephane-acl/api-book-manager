<?php

namespace App\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Library;
use App\Entity\Author;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $security;
    private $auth;

    public function __construct(Security $security, AuthorizationCheckerInterface $checker)
    {
        $this->security = $security;
        $this->auth = $checker;
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass)
    {

        $user = $this->security->getUser();

        if ((
                $resourceClass === Library::class ||
                $resourceClass === Book::class ||
                $resourceClass === Author::class ||
                $resourceClass === Category::class ||
                $resourceClass === User::class
            )
            && !$this->auth->isGranted("ROLE_ADMIN") && $user instanceof User) {

            $rootAlias = $queryBuilder->getRootAliases()[0];

            if ($resourceClass === Library::class) {
                $queryBuilder->andWhere(":user MEMBER OF $rootAlias.user");
                $queryBuilder->setParameter("user", $user);
            } elseif (
                $resourceClass === Book::class ||
                $resourceClass === Author::class ||
                $resourceClass === Category::class
            ) {
                $queryBuilder
                    ->join("$rootAlias.library", "l")
                    ->andWhere(":user MEMBER OF l.user");
                $queryBuilder->setParameter("user", $user);
            } else if ($resourceClass === User::class) {
                $queryBuilder->andWhere("$rootAlias.id = :user_id");
                $queryBuilder->setParameter("user_id", $user->getId());
            } else {
                $queryBuilder
                    ->join("$rootAlias.user", "u")
                    ->andWhere("u.id = :user_id");
                $queryBuilder->setParameter("user_id", $user->getId());
            }
        }
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = [])
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }
}