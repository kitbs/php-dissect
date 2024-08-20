<?php

namespace Dissect\Parser\LALR1\Analysis;

/**
 * The result of a grammar analysis.
 *
 * @author Jakub Lédl <jakubledl@gmail.com>
 */
class AnalysisResult
{
    protected Automaton $automaton;

    protected array $parseTable;

    protected array $resolvedConflicts;

    /**
     * Constructor.
     *
     * @param  array  $parseTable  The parse table.
     * @param  array  $conflicts  An array of conflicts resolved during parse table
     *                            construction.
     */
    public function __construct(array $parseTable, Automaton $automaton, array $conflicts)
    {
        $this->parseTable = $parseTable;
        $this->automaton = $automaton;
        $this->resolvedConflicts = $conflicts;
    }

    /**
     * Returns the handle-finding FSA.
     */
    public function getAutomaton(): Automaton
    {
        return $this->automaton;
    }

    /**
     * Returns the resulting parse table.
     *
     * @return array The parse table.
     */
    public function getParseTable(): array
    {
        return $this->parseTable;
    }

    /**
     * Returns an array of resolved parse table conflicts.
     *
     * @return array The conflicts.
     */
    public function getResolvedConflicts(): array
    {
        return $this->resolvedConflicts;
    }
}
