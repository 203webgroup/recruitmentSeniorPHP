<?php

use Behat\Behat\Context\ClosuredContextInterface;
use Behat\Behat\Context\TranslatedContextInterface;
use Behat\Behat\Context\BehatContext;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

use Guzzle\Http\Client;

require_once __DIR__ . '/../../vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';

class FeatureContext extends BehatContext
{
    private $config = null;
    private $basePath = __DIR__;

    private static $dataFilePath;
    private static $data;

    public function __construct($config)
    {
        $this->client = new Client($config['base_url']);
        $this->configFile = __DIR__ . $config['config_file'];
        static::$dataFilePath = __DIR__ . $config['data_file'];
    }

    /**
     * @BeforeSuite
     */
    public static function initializeData()
    {
        $data = [
            'specifications' => [
                'password' => [
                    'min_length' => rand(3, 6)
                ],
                'username' => [
                    'min_length' => rand(3, 6)
                ]
            ],
            'data' => [
                'credentials' => []
            ]
        ];
        static::writeAppData($data);
    }

    private function getAppConfig()
    {
        if (is_null($this->config)) {
            $this->config = static::readJsonFile($this->configFile);
        }

        return $this->config;
    }


    private function writeAppConfig(array $config)
    {
        $this->config = $config;
        static::writeJsonFile($this->configFile, $config);

        return $this;
    }

    private function getAppData()
    {
        if (is_null(static::$data)) {
            static::$data = static::readJsonFile(static::$dataFilePath);
        }

        return static::$data;
    }

    private static function writeAppData($data)
    {
        static::$data = $data;
        static::writeJsonFile(static::$dataFilePath, $data);
    }

    private function readJsonFile($filePath)
    {
        return json_decode(file_get_contents($filePath), true);
    }

    private static function writeJsonFile($filePath, $content)
    {
        file_put_contents($filePath, json_encode($content, JSON_PRETTY_PRINT));
    }


    private function sendRequest($uri)
    {
        $this->request = $this->client->get($uri);
        $this->response = $this->request->send();
    }

    /**
     * @Given /^a minimal length for password of (\d+) characters$/
     */
    public function aMinimalLengthForPasswordOfCharacters($passwordMinLength)
    {
        $config = $this->getAppConfig();
        $config['specifications']['password']['min_length'] = $passwordMinLength;
        $this->writeAppConfig($config);
    }

    /**
     * @Given /^a password \'([^\']*)\'$/
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @When /^I check if it satisfies our Password Specification$/
     */
    public function checkIfSatisfiesOurPasswordSpecification()
    {
        $this->sendRequest(sprintf('user/check_password/%s', $this->password));
    }

    /**
     * @Then /^I should get a \'(Error: [^\']*)\' message$/
     */
    public function iShouldGetAErrorMessage($message)
    {
        $response = $this->response->json();
        $serviceErrorMsg = !empty($response['error_msg']) ? $response['error_msg'] : null;
        assertEquals(
            $message,
            $serviceErrorMsg
        );
    }

    /**
     * @Given /^a minimal length for username of (\d+) characters$/
     */
    public function aMinimalLengthForUsernameOfCharacters($usernameMinLength)
    {
        $config = $this->getAppConfig();
        $config['specifications']['username']['min_length'] = $usernameMinLength;
        $this->writeAppConfig($config);
    }

    /**
     * @Given /^a username \'([^\']*)\'$/
     */
    public function aUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @When /^I check if it satisfies our Username Specification$/
     */
    public function checkIfSatisfiesOurUsernameSpecification($username = null)
    {
        if (is_null($username)) {
            $username = $this->username;
        }

        $this->sendRequest(sprintf('user/check_username/%s', $username));
    }

    /**
     * @Given /^a user named \'([^\']*)\'$/
     */
    public function aUserNamed($username)
    {
        $this->username = $username;
        $data = $this->getAppData();
        $data['credentials'][] = [
            'username' => $username,
            'password' => 'aPasw@rd'
        ];
        static::writeAppData($data);
    }

    /**
     * @Given /^a new password \'([^\']*)\' that satisfies our Password Specification$/
     */
    public function aNewPasswordThatSatisfiesOurPasswordSpecification($newPassword)
    {
        $this->setPassword($newPassword);
        $this->checkIfSatisfiesOurPasswordSpecification();
        assertEquals(
            'Valid password',
            (string) $this->response->json()
        );
    }

    /**
     * @When /^I change his password credentials for the new one$/
     */
    public function iChangeHisPasswordCredentialsForTheNewOne()
    {
        $this->sendRequest(
            sprintf(
                'user/%s/update_password/%s',
                $this->username,
                $this->password
            )
        );
    }

    /**
     * @Then /^the result should be successfully$/
     */
    public function theResultShouldBeSuccessfully()
    {
        $response = $this->response->json();
        assertTrue(empty($response['error_msg']));
    }

    /**
     * @Given /^a new username \'([^\']*)\' that satisfies our Username Specification$/
     */
    public function aNewUsernameThatSatisfiesOurUsernameSpecification($newUsername)
    {
        $this->newUsername = $newUsername;
        $this->checkIfSatisfiesOurUsernameSpecification($newUsername);
    }

    /**
     * @When /^I change his username credentials for the new one$/
     */
    public function iChangeHisUsernameCredentialsForTheNewOne()
    {
        $this->updateUsername($this->username, $this->newUsername);
    }

    private function updateUsername($username, $newUsername, $newUsernameConfirmation = null)
    {
        if (is_null($newUsernameConfirmation)) {
            $newUsernameConfirmation = $newUsername;
        }

        $this->sendRequest(
            sprintf(
                'user/%s/update_username/%s/%s',
                $username,
                $newUsername,
                $newUsernameConfirmation
            )
        );
    }

    /**
     * @Given /^exists a user named \'([^\']*)\' in the User Collection$/
     */
    public function existsAUserNamedInTheUserCollection($existingUsername)
    {
        $data = $this->getAppData();
        $data['credentials'] = [
            [
                'username' => $existingUsername,
                'password' => 'some_password'
            ]
        ];
        static::writeAppData($data);
    }

    /**
     * @Given /^a username \'([^\']*)\' and its username confirmation \'([^\']*)\'$/
     */
    public function aUsernameAndItsUsernameConfirmation($newUsername, $newUsernameConfirmation)
    {
        $this->newUsername = $newUsername;
        $this->newUsernameConfirmation = $newUsernameConfirmation;
    }

    /**
     * @When /^I check if it satisfies our Change Username Specification$/
     */
    public function iCheckIfItSatisfiesOurChangeUsernameSpecification()
    {
        $this->updateUsername(
            $this->username,
            $this->newUsername,
            $this->newUsernameConfirmation
        );
    }

    /**
     * @Given /^the username \'([^\']*)\' does not satisfy Username Specification$/
     */
    public function theUsernameDoesNotSatisfyUsernameSpecification($wrongUsername)
    {
        $this->checkIfSatisfiesOurUsernameSpecification($wrongUsername);
        $this->hasSomeError();
    }

    private function hasSomeError()
    {
        assertFalse(
            empty($this->response->json()['error_msg'])
        );
    }
}
