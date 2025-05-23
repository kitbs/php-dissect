<?php

namespace Dissect\Parser\LALR1\Analysis\Exception;

use Dissect\Parser\LALR1\Analysis\Automaton;
use Dissect\Parser\Rule;

/**
 * Thrown when a grammar is not LALR(1) and exhibits
 * a reduce/reduce conflict.
 *
 * @author Jakub Lédl <jakubledl@gmail.com>
 */
class ReduceReduceConflictException extends ConflictException
{
    /**
     * The exception message template.
     */
    const MESSAGE = <<<'EOT'
The grammar exhibits a reduce/reduce conflict on rules:

  %d. %s -> %s

vs:

  %d. %s -> %s

(on lookahead "%s" in state %d). Restructure your grammar or choose a conflict resolution mode.
EOT;

    protected Rule $firstRule;

    protected Rule $secondRule;

    protected string $lookahead;

    /**
     * Constructor.
     *
     * @param  int  $state  The number of the inadequate state.
     * @param  Rule  $firstRule  The first conflicting grammar rule.
     * @param  Rule  $secondRule  The second conflicting grammar rule.
     * @param  string  $lookahead  The conflicting lookahead.
     * @param  Automaton  $automaton  The faulty automaton.
     */
    public function __construct(int $state, Rule $firstRule, Rule $secondRule, string $lookahead, Automaton $automaton)
    {
        $components1 = $firstRule->getComponents();
        $components2 = $secondRule->getComponents();

        parent::__construct(
            sprintf(
                self::MESSAGE,
                $firstRule->getNumber(),
                $firstRule->getName(),
                empty($components1) ? '/* empty */' : implode(' ', $components1),
                $secondRule->getNumber(),
                $secondRule->getName(),
                empty($components2) ? '/* empty */' : implode(' ', $components2),
                $lookahead,
                $state
            ),
            $state,
            $automaton
        );

        $this->firstRule = $firstRule;
        $this->secondRule = $secondRule;
        $this->lookahead = $lookahead;
    }

    /**
     * Returns the first conflicting rule.
     *
     * @return Rule The first conflicting rule.
     */
    public function getFirstRule(): Rule
    {
        return $this->firstRule;
    }

    /**
     * Returns the second conflicting rule.
     *
     * @return Rule The second conflicting rule.
     */
    public function getSecondRule(): Rule
    {
        return $this->secondRule;
    }

    /**
     * Returns the conflicting lookahead.
     *
     * @return string The conflicting lookahead.
     */
    public function getLookahead(): string
    {
        return $this->lookahead;
    }
}
