<?php

namespace Dissect\Lexer\Exception;

use RuntimeException;

/**
 * Thrown when a lexer is unable to extract another token.
 *
 * @author Jakub Lédl <jakubledl@gmail.com>
 */
class RecognitionException extends RuntimeException
{
    protected int $sourceLine;

    protected int $sourceColumn;

    /**
     * Constructor.
     *
     * @param  int  $line  The line in the source.
     */
    public function __construct(int $line, int $column)
    {
        $this->sourceLine = $line;
        $this->sourceColumn = $column;

        parent::__construct(sprintf('Cannot extract another token on line %d at column %d.', $line, $column));
    }

    /**
     * Returns the source line number where the exception occured.
     *
     * @return int The source line number.
     */
    public function getSourceLine(): int
    {
        return $this->sourceLine;
    }

    /**
     * Returns the source column number where the exception occured.
     *
     * @return int The source column number.
     */
    public function getSourceColumn(): int
    {
        return $this->sourceColumn;
    }
}
