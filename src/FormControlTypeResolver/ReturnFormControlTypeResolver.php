<?php

declare(strict_types=1);

namespace Rector\Nette\FormControlTypeResolver;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Nette\Contract\FormControlTypeResolverInterface;
use Rector\Nette\NodeResolver\MethodNamesByInputNamesResolver;

final class ReturnFormControlTypeResolver implements FormControlTypeResolverInterface
{
    private MethodNamesByInputNamesResolver $methodNamesByInputNamesResolver;

    public function __construct(
        private BetterNodeFinder $betterNodeFinder
    ) {
    }

    /**
     * @required
     */
    public function autowireReturnFormControlTypeResolver(
        MethodNamesByInputNamesResolver $methodNamesByInputNamesResolver
    ): void {
        $this->methodNamesByInputNamesResolver = $methodNamesByInputNamesResolver;
    }

    /**
     * @return array<string, string>
     */
    public function resolve(Node $node): array
    {
        if (! $node instanceof Return_) {
            return [];
        }

        if (! $node->expr instanceof Variable) {
            return [];
        }

        $initialAssign = $this->betterNodeFinder->findPreviousAssignToExpr($node->expr);
        if (! $initialAssign instanceof Assign) {
            return [];
        }

        return $this->methodNamesByInputNamesResolver->resolveExpr($node);
    }
}
