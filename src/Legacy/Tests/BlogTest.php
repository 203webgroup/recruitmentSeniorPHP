<?php

namespace Legacy\Tests;

use Legacy\Blog;

class BlogTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->phaseSearcherMock = $this->getMock('Legacy\SearchPhraseHelper');
        $this->urlHelperMock = $this->getMock('Legacy\UrlHelper');
        $this->urlHelperMock
            ->expects($this->any())
            ->method('deurlize')
            ->willReturnArgument(0);
        $this->siteLibMock = $this->getMock('Legacy\SiteLib');
        $this->configMock = $this->getMock('Legacy\Config');
        $this->searchLibMock = $this->getMockBuilder('Legacy\Recipe\Searcher')
            ->disableOriginalConstructor()
            ->getMock();
        $this->templateMock = $this->getMock('Legacy\Template');
        $this->paginatorMock = $this->getMock('Legacy\Paginator');
        $this->sut = $this->getMockBuilder('Legacy\Blog')
                        ->setConstructorArgs(
                            [
                                $this->phaseSearcherMock,
                                $this->urlHelperMock,
                                $this->siteLibMock,
                                $this->configMock,
                                $this->searchLibMock,
                                $this->templateMock,
                                $this->paginatorMock
                            ]
                        )->setMethods(['redirectPermanently'])
                        ->getMock();
    }

    public function tearDown()
    {
        unset($this->phaseSearcherMock);
        unset($this->urlHelperMock);
        unset($this->siteLibMock);
        unset($this->configMock);
        unset($this->searchLibMock);
        unset($this->templateMock);
        unset($this->paginatorMock);
        unset($this->sut);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Redirecting
     */
    public function testViewInvalidSite()
    {
        $this->setRedirectPermanentlyExpectation('blogosphere');
        $this->sut->view(4);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Redirecting
     */
    public function testViewValidSiteWithRedirection()
    {
        $keyword = 'cake';
        $page = 3;

        $this->setRedirectPermanentlyExpectation(
            sprintf('mytaste.loc/%s/%d/', $keyword, $page)
        );
        $this->siteLibMock
            ->expects($this->once())
            ->method('get')
            ->with(4, true)
            ->willReturn(
                [
                    'site_id' => 4,
                    'on_page_url' => 'mytaste.loc/'
                ]
            );
        $_SERVER['REQUEST_URI'] = 'mytaste.loc';

        $this->sut->view(4, $keyword, $page);
    }

    public function testViewValidSiteWithoutRedirection()
    {
        $keyword = 'cake';
        $page = 3;

        $this->siteLibMock
            ->expects($this->once())
            ->method('get')
            ->with(4, true)
            ->willReturn(
                [
                    'name' => 'other_taste',
                    'site_id' => 4,
                    'on_page_url' => 'other_taste.loc/'
                ]
            );
        $this->searchLibMock
            ->expects($this->once())
            ->method('getPaginatorAsArray')
            ->willReturn([]);
        $this->searchLibMock
            ->expects($this->any())
            ->method('getOrderBy')
            ->willReturn('a');
        $this->searchLibMock
            ->expects($this->any())
            ->method('getFoundCount')
            ->willReturn(rand(1, 100));

        $_SERVER['REQUEST_URI'] = 'mytaste.loc';

        $this->configMock
            ->expects($this->at(0))
            ->method('item')
            ->with('site_url')
            ->willReturn('mytaste.loc');

        $this->sut->view(4, $keyword, $page);
    }

    private function setRedirectPermanentlyExpectation($url)
    {
        $this->sut
            ->expects($this->once())
            ->method('redirectPermanently')
            ->with($url)
            ->willThrowException(
                new \Exception(sprintf('Redirecting to %s', $url))
            );
    }
}
