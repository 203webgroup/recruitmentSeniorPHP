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
        $this->client = new Client($config['base_url']);
    }

    /**
     * @Given /^a minimal length for password of (\d+) characters$/
     */
    public function aMinimalLengthForPasswordOfCharacters($passwordMinLength)
    {
        $this->passwordMinLength = $passwordMinLength;
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
        $checkerUrl = sprintf('check_password/%s/%d', $this->password, $this->passwordMinLength);
        $this->request = $this->client->get($checkerUrl);
        $this->response = $this->request->send();
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
}
