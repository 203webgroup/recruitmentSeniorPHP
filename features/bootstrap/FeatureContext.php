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
    private $assertSession;

    public function __construct($config)
    {
        $this->configFile = __DIR__ . $config['config_file'];
        $this->client = new Client($config['base_url']);
        $this->config = null;
    }

    private function getAppConfig()
    {
        if (is_null($this->config)) {
            $config = json_decode(file_get_contents($this->configFile), true);
        }

        return $config;
    }

    private function writeAppConfig(array $config)
    {
        $this->config = $config;
        file_put_contents($this->configFile, json_encode($config, JSON_PRETTY_PRINT));

        return $this;
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
    public function aPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @When /^I check if it satisfies our Password Specification$/
     */
    public function iCheckIfItSatisfiesOurPasswordSpecification()
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
    public function iCheckIfItSatisfiesOurUsernameSpecification()
    {
        $this->sendRequest(sprintf('user/check_username/%s', $this->username));
    }

    /**
     * @Given /^a user named \'([^\']*)\'$/
     */
    public function aUserNamed($username)
    {
        $this->aUsername($username);
    }

    /**
     * @Given /^a new password \'([^\']*)\' that satisfies our Password Specification$/
     */
    public function aNewPasswordThatSatisfiesOurPasswordSpecification($password)
    {
        $this->aPassword($password);
        $this->iCheckIfItSatisfiesOurPasswordSpecification();
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
        $this->iCheckIfItSatisfiesOurUsernameSpecification($newUsername);
    }

    /**
     * @When /^I change his username credentials for the new one$/
     */
    public function iChangeHisUsernameCredentialsForTheNewOne()
    {
        $this->sendRequest(
            sprintf(
                'user/%s/update_username/%s',
                $this->username,
                $this->newUsername
            )
        );
    }
}
