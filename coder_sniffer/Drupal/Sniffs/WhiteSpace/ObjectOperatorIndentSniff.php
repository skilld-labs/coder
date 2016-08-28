<?php
/**
 * Drupal_Sniffs_WhiteSpace_ObjectOperatorIndentSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Drupal_Sniffs_WhiteSpace_ObjectOperatorIndentSniff.
 *
 * Checks that object operators are indented 2 spaces if they are the first
 * thing on a line.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: 1.2.0RC3
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class Drupal_Sniffs_WhiteSpace_ObjectOperatorIndentSniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_OBJECT_OPERATOR);

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile All the tokens found in the document.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check that there is only whitespace before the object operator and there
        // is nothing else on the line.
        if ($tokens[($stackPtr - 1)]['code'] !== T_WHITESPACE || $tokens[($stackPtr - 1)]['column'] !== 1) {
            return;
        }

        $previousLine = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 2), null, true, null, true);

        if ($previousLine === false) {
            return;
        }

        // Find the first non whitespace character on the previous line.
        $startOfLine = $previousLine;
        while ($tokens[($startOfLine - 1)]['line'] === $tokens[$startOfLine]['line']) {
            $startOfLine--;
        }

        if ($tokens[$startOfLine]['code'] === T_WHITESPACE) {
            $startOfLine++;
        }

        if ($tokens[$startOfLine]['code'] === T_OBJECT_OPERATOR) {
            // If there is some wrapping in function calls then there should be an
            // additional level of indentation.
            if (isset($tokens[$stackPtr]['nested_parenthesis']) === true
                && (empty($tokens[$startOfLine]['nested_parenthesis']) === true
                || $tokens[$startOfLine]['nested_parenthesis'] !== $tokens[$stackPtr]['nested_parenthesis'])
            ) {
                $additionalIndent = 2;
            } else {
                $additionalIndent = 0;
            }
        } else if (isset($tokens[$startOfLine]['nested_parenthesis']) === true
            && (empty($tokens[$stackPtr]['nested_parenthesis']) === true
            || $tokens[$startOfLine]['nested_parenthesis'] !== $tokens[$stackPtr]['nested_parenthesis'])
        ) {
            $additionalIndent = 0;
        } else {
            $additionalIndent = 2;
        }

        if ($tokens[$stackPtr]['column'] !== ($tokens[$startOfLine]['column'] + $additionalIndent)) {
            $error          = 'Object operator not indented correctly; expected %s spaces but found %s';
            $expectedIndent = ($tokens[$startOfLine]['column'] + $additionalIndent - 1);
            $data           = array(
                               $expectedIndent,
                               $tokens[$stackPtr]['column'] - 1,
                              );
            $fix            = $phpcsFile->addFixableError($error, $stackPtr, 'Indent', $data);

            if ($fix === true) {
                $phpcsFile->fixer->replaceToken(($stackPtr - 1), str_repeat(' ', $expectedIndent));
            }
        }

    }//end process()


}//end class
