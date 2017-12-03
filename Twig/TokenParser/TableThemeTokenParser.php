<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Twig\TokenParser;

use Nours\TableBundle\Twig\Node\TableThemeNode;

/**
 * Class TableThemeTokenParser
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TableThemeTokenParser extends \Twig_TokenParser
{
    /**
     * {@inheritdoc}
     */
    public function parse(\Twig_Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        $table = $this->parser->getExpressionParser()->parseExpression();

        $resources = new \Twig_Node_Expression_Array(array(), $stream->getCurrent()->getLine());
        do {
            $resources->addElement($this->parser->getExpressionParser()->parseExpression());
        } while (!$stream->test(\Twig_Token::BLOCK_END_TYPE));

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return new TableThemeNode($table, $resources, $lineno, $this->getTag());
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'table_theme';
    }
}