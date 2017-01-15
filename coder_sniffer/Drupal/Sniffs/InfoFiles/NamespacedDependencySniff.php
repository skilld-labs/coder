<?php

/**
 * Drupal_Sniffs_InfoFiles_NamespacedDependencySniff.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @link     http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Checks that all declared dependencies are namespaced.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @link     http://pear.php.net/package/PHP_CodeSniffer
 */
class Drupal_Sniffs_InfoFiles_NamespacedDependencySniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_INLINE_HTML);

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The current file being processed.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return int
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $end    = (count($tokens) + 1);

        $fileExtension = strtolower(substr($phpcsFile->getFilename(), -9));
        if ($fileExtension !== '.info.yml') {
            return $end;
        }

        if (preg_match('/^dependencies:/', $tokens[$stackPtr]['content']) === 0 ) {
            return;
        }

        $nextLine = $stackPtr + 1;

        if (isset ($tokens[$nextLine]) === true
            && preg_match('/^[\s]+- [^:]+[\s]*$/', $tokens[$nextLine]['content']) === 1
        ) {
            $error = 'All dependencies must be prefixed with the project name, for example "drupal:"';
            $phpcsFile->addError($error, $nextLine, 'NonNamespaced');
        }

    }//end process()


}//end class

