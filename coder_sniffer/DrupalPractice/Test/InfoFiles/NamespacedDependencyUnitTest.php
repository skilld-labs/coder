<?php

class DrupalPractice_Sniffs_InfoFiles_NamespacedDependencyUnitTest extends CoderSniffUnitTest
{

    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @return array(int => int)
     */
    public function getErrorList($testFile)
    {
        return array();

    }//end getErrorList()


    /**
     * {@inheritdoc}
     */
    public function getWarningList($testFile)
    {
        switch ($testFile) {
            case 'dependencies_test.info.yml':
                return array(
                    9 => 1,
                    11 => 1,
                    13 => 1,
                );
            case 'theme_test.info.yml':
            case 'profile_test.info.yml':
                return array();
        }
    }//end getWarningList()

    /**
     * Returns a list of test files that should be checked.
     *
     * @return array The list of test files.
     */
    protected function getTestFiles() {
        return array(
            __DIR__ . '/dependencies_test.info.yml',
            __DIR__ . '/theme_test.info.yml',
            __DIR__ . '/profile_test.info.yml',
        );
    }


}//end class
